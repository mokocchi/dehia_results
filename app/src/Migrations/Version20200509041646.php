<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200509041646 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tarea (id INT AUTO_INCREMENT NOT NULL, actividad_id INT DEFAULT NULL, tipo_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, codigo VARCHAR(255) NOT NULL, extra JSON DEFAULT NULL, INDEX IDX_3CA053666014FACA (actividad_id), INDEX IDX_3CA05366A9276E6C (tipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tarea ADD CONSTRAINT FK_3CA053666014FACA FOREIGN KEY (actividad_id) REFERENCES actividad (id)');
        $this->addSql('ALTER TABLE tarea ADD CONSTRAINT FK_3CA05366A9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_tarea (id)');
        $this->addSql('ALTER TABLE tipo_tarea_tipo_grafico ADD CONSTRAINT FK_4D33C4D8BB9E8528 FOREIGN KEY (tipo_tarea_id) REFERENCES tipo_tarea (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tipo_tarea_tipo_grafico ADD CONSTRAINT FK_4D33C4D86069EF86 FOREIGN KEY (tipo_grafico_id) REFERENCES tipo_grafico (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tarea');
        $this->addSql('ALTER TABLE tipo_tarea_tipo_grafico DROP FOREIGN KEY FK_4D33C4D8BB9E8528');
        $this->addSql('ALTER TABLE tipo_tarea_tipo_grafico DROP FOREIGN KEY FK_4D33C4D86069EF86');
    }
}
