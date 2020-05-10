<?php

namespace App\Controller\v1;

use App\Api\ApiProblem;
use App\Api\ApiProblemException;
use App\Entity\Actividad;
use App\Entity\Tarea;
use App\Entity\TipoTarea;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/actividades")
 */
class ActividadesController extends AbstractFOSRestController
{

    private function checkRequiredFields($fields, $array)
    {
        foreach ($fields as $field) {
            if (!array_key_exists($field, $array) || is_null($array[$field]) || $array[$field] === "") {
                throw new ApiProblemException(
                    new ApiProblem(
                        Response::HTTP_BAD_REQUEST,
                        "Faltan campos en el request: " . $field,
                        "Hubo un problema con la petición"
                    )
                );
            }
        }
    }

    private function checkCodigoNotExists($codigo, $class, $em)
    {
        if ($em->getRepository($class)->findOneBy(["codigo" => $codigo])) {
            throw new ApiProblemException(
                new ApiProblem(
                    Response::HTTP_BAD_REQUEST,
                    "El codigo ya existe",
                    "El código ya existe"
                )
            );
        }
    }

    private function getJson($request)
    {
        $data = json_decode($request->getContent(), true);
        if (is_null($data)) {
            throw new ApiProblemException(
                new ApiProblem(
                    Response::HTTP_BAD_REQUEST,
                    "No hay campos en el json",
                    "La petición está vacía"
                )
            );
        }
        return $data;
    }

    /**
     * @Rest\Post(name="post_actividad")
     */
    public function postActividad(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getJson($request);
        $this->checkRequiredFields(["codigo", "nombre", "tareas"], $data);

        $this->checkCodigoNotExists($data["codigo"], Actividad::class, $em);

        $actividad = new Actividad();
        $actividad->setNombre($data["nombre"]);
        $actividad->setCodigo($data["codigo"]);

        $actividad->setAutor($this->getUser()->getGoogleId());
        $em->persist($actividad);

        $tareas = $data["tareas"];
        if (!is_array($tareas) || count($tareas) === 0) {
            throw new ApiProblemException(
                new ApiProblem(
                    Response::HTTP_BAD_REQUEST,
                    "El campo tareas es inválido",
                    "Hubo un problema con la petición"
                )
            );
        }
        foreach ($tareas as $tareaArray) {
            if (!is_array($tareaArray)) {
                throw new ApiProblemException(
                    new ApiProblem(
                        Response::HTTP_BAD_REQUEST,
                        "La tarea es inválida",
                        "Hubo un problema con la petición"
                    )
                );
            }
            $this->checkRequiredFields(["codigo", "nombre", "tipo"], $tareaArray);
            $tarea = $em->getRepository(Tarea::class)->findOneBy(["codigo" => $data["codigo"]]) ?: new Tarea();
            $tarea->setNombre($tareaArray["nombre"]);
            $tarea->setCodigo($tareaArray["codigo"]);
            $tipo = $em->getRepository(TipoTarea::class)->findOneBy(["codigo" => $tareaArray["tipo"]]);
            if (is_null($tipo)) {
                throw new ApiProblemException(
                    new ApiProblem(
                        Response::HTTP_BAD_REQUEST,
                        "El tipo de tarea no existe: " . $tareaArray["tipo"],
                        "El tipo de tarea no existe"
                    )
                );
            }
            $tarea->setTipo($tipo);
            if (array_key_exists("extra", $tareaArray)) {
                $tarea->setExtra($tareaArray["extra"]);
            }
            $actividad->addTarea($tarea);
            $em->persist($tarea);
        }
        $em->flush();
        $this->handleView($this->view($actividad, Response::HTTP_CREATED));
    }
}
