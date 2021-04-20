<?php

namespace App\Controller\v1;

use App\Api\ApiProblem;
use App\Api\ApiProblemException;
use App\Entity\Actividad;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/resultados")
 */
class ResultsController extends AbstractFOSRestController
{
    private function verifyCode($code)
    {
        if ((strlen($code) !== 64) || (preg_match("/[0-9a-z]/", $code) !== 1)) {
            throw new ApiProblemException(
                new ApiProblem(
                    Response::HTTP_BAD_REQUEST,
                    "Formato de código erróneo",
                    "Ocurrió un error"
                )
            );
        }
    }

    private function getResultsFromCollect($codigo)
    {
        $client = new \GuzzleHttp\Client(
            [
                'base_uri' => $_ENV["COLLECT_BASE_URL"]
            ]
        );
        try {
            $options = [
                "headers" => [
                    "Authorization" => $this->getUser()->getToken(),
                    "X-Authorization-OAuth" => $this->getUser()->getOAuth()
                ],
            ];
            $response = $client->get(sprintf("/api/v1.0/entries/%s", $codigo), $options);
            $data = json_decode((string) $response->getBody(), true);

            if (!array_key_exists("results", $data)) {
                throw new ApiProblemException(
                    new ApiProblem(
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        "No se pudo obtener los resultados",
                        "No se pudo obtener los resultados"
                    )
                );
            }
            return $data["results"];
        } catch (Exception $e) {
            if ($e instanceof RequestException) {
                $response = $e->getResponse();
                if (!is_null($response)) {
                    $data = json_decode((string) $response->getBody(), true);
                    throw new ApiProblemException(
                        new ApiProblem(
                            $response->getStatusCode(),
                            $data["developer_message"],
                            $data["user_message"]
                        )
                    );
                } else {
                    $this->logger->error($e->getMessage());
                    throw new ApiProblemException(
                        new ApiProblem(
                            Response::HTTP_INTERNAL_SERVER_ERROR,
                            "Ocurrió un error al traer los resultados",
                            "Ocurrió un error"
                        )
                    );
                }
            } else {
                $this->logger->error($e->getMessage());
                throw new ApiProblemException(
                    new ApiProblem(
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        "Ocurrió un error al traer los resultados",
                        "Ocurrió un error"
                    )
                );
            }
        }
    }

    /**
     * @Rest\Get(name="get_results")
     */
    public function getAllResults(Request $request)
    {
        $code = $request->query->get("code");
        if (is_null($code)) {
            throw new ApiProblemException(
                new ApiProblem(
                    Response::HTTP_BAD_REQUEST,
                    "El código es obligatorio",
                    "El código es obligatorio"
                )
            );
        }

        $this->verifyCode($code);

        $this->getDoctrine()->getRepository(Actividad::class);

        $actividad = $this->getDoctrine()->getRepository(Actividad::class)->findOneBy(["codigo" => $code]);
        if (is_null($actividad)) {
            //TODO: get activity from define if not found
            throw new ApiProblemException(
                new ApiProblem(
                    Response::HTTP_FORBIDDEN,
                    "La actividad aún no fue publicada",
                    "La actividad aún no fue publicada"
                )
            );
        }
        if ($this->getUser()->getGoogleid() !== $actividad->getAutor()) {
            throw new ApiProblemException(
                new ApiProblem(
                    Response::HTTP_FORBIDDEN,
                    "La actividad no pertenece al usuario",
                    "La actividad no pertenece al usuario"
                )
            );
        }

        $tareas = $actividad->getTareas();
        $nombresTareas = $tareas->map(function ($t) {
            return $t->getNombre();
        });
        $results = $this->getResultsFromCollect($code);

        $respuestas = [];
        foreach ($results as $result) {
            $responses = $result["responses"];
            $resps = [];
            foreach ($responses as $response) {
                $key = array_keys($response)[0];
                $resps[$key] = $response[$key];
            }

            $row = [];
            foreach ($tareas as $tarea) {
                if (array_key_exists($tarea->getCodigo(), $resps)) {
                    $taskEntry = $resps[$tarea->getCodigo()];
                    $tipo = $tarea->getTipo();
                    if ($tipo->getCodigo()) {
                        //match opciones con Extra
                    }
                    if (is_array($taskEntry)) {
                        $output = json_encode($taskEntry, JSON_PRETTY_PRINT);
                    } else {
                        $output = $taskEntry;
                    }
                    $row[] = $output;
                } else {
                    $row[] = "<Sin respuesta>";
                }
            }
            $respuestas[] = $row;
        }

        return $this->handleView($this->view(["tareas" => $nombresTareas, "respuestas" => $respuestas]));
    }
}
