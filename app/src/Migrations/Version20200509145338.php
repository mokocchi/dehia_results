<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200509145338 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("INSERT INTO `tipo_tarea` (`id`, `codigo`, `tiene_opciones`) VALUES
        (1,	'simple',	0),
        (2,	'textInput',	0),
        (3,	'numberInput',	0),
        (4,	'cameraInput',	0),
        (5,	'select',	1),
        (6,	'multiple',	1),
        (7,	'counters',	0),
        (8,	'collect',	1),
        (9,	'deposit',	0),
        (10,	'GPSInput',	0),
        (11,	'audioInput',	0);
        ");

    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql("DELETE FROM `tipo_tarea` WHERE `tipo_tarea`.`id` = 1");
        $this->addSql("DELETE FROM `tipo_tarea` WHERE `tipo_tarea`.`id` = 2");
        $this->addSql("DELETE FROM `tipo_tarea` WHERE `tipo_tarea`.`id` = 3");
        $this->addSql("DELETE FROM `tipo_tarea` WHERE `tipo_tarea`.`id` = 4");
        $this->addSql("DELETE FROM `tipo_tarea` WHERE `tipo_tarea`.`id` = 5");
        $this->addSql("DELETE FROM `tipo_tarea` WHERE `tipo_tarea`.`id` = 6");
        $this->addSql("DELETE FROM `tipo_tarea` WHERE `tipo_tarea`.`id` = 7");
        $this->addSql("DELETE FROM `tipo_tarea` WHERE `tipo_tarea`.`id` = 8");
        $this->addSql("DELETE FROM `tipo_tarea` WHERE `tipo_tarea`.`id` = 9");
        $this->addSql("DELETE FROM `tipo_tarea` WHERE `tipo_tarea`.`id` = 10");
        $this->addSql("DELETE FROM `tipo_tarea` WHERE `tipo_tarea`.`id` = 11");
    }
}
