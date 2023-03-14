<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221211223323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FBD95B80F');
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC386924DD2B5');
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC3864CC8505A');
        $this->addSql('DROP TABLE bien');
        $this->addSql('DROP INDEX IDX_C53D045FBD95B80F ON image');
        $this->addSql('ALTER TABLE image DROP bien_id');
        $this->addSql('ALTER TABLE immeuble ADD offre_id INT DEFAULT NULL, ADD localite_id INT DEFAULT NULL, CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE immeuble ADD CONSTRAINT FK_467D53F94CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE immeuble ADD CONSTRAINT FK_467D53F9924DD2B5 FOREIGN KEY (localite_id) REFERENCES localite (id)');
        $this->addSql('CREATE INDEX IDX_467D53F94CC8505A ON immeuble (offre_id)');
        $this->addSql('CREATE INDEX IDX_467D53F9924DD2B5 ON immeuble (localite_id)');
        $this->addSql('ALTER TABLE maison ADD offre_id INT DEFAULT NULL, ADD localite_id INT DEFAULT NULL, CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE maison ADD CONSTRAINT FK_F90CB66D4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE maison ADD CONSTRAINT FK_F90CB66D924DD2B5 FOREIGN KEY (localite_id) REFERENCES localite (id)');
        $this->addSql('CREATE INDEX IDX_F90CB66D4CC8505A ON maison (offre_id)');
        $this->addSql('CREATE INDEX IDX_F90CB66D924DD2B5 ON maison (localite_id)');
        $this->addSql('ALTER TABLE terrain ADD offre_id INT DEFAULT NULL, ADD localite_id INT DEFAULT NULL, CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B14CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B1924DD2B5 FOREIGN KEY (localite_id) REFERENCES localite (id)');
        $this->addSql('CREATE INDEX IDX_C87653B14CC8505A ON terrain (offre_id)');
        $this->addSql('CREATE INDEX IDX_C87653B1924DD2B5 ON terrain (localite_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bien (id INT AUTO_INCREMENT NOT NULL, localite_id INT NOT NULL, offre_id INT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, disponibilite TINYINT(1) NOT NULL, INDEX IDX_45EDC386924DD2B5 (localite_id), INDEX IDX_45EDC3864CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC386924DD2B5 FOREIGN KEY (localite_id) REFERENCES localite (id)');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC3864CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE image ADD bien_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FBD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FBD95B80F ON image (bien_id)');
        $this->addSql('ALTER TABLE immeuble DROP FOREIGN KEY FK_467D53F94CC8505A');
        $this->addSql('ALTER TABLE immeuble DROP FOREIGN KEY FK_467D53F9924DD2B5');
        $this->addSql('DROP INDEX IDX_467D53F94CC8505A ON immeuble');
        $this->addSql('DROP INDEX IDX_467D53F9924DD2B5 ON immeuble');
        $this->addSql('ALTER TABLE immeuble DROP offre_id, DROP localite_id, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE maison DROP FOREIGN KEY FK_F90CB66D4CC8505A');
        $this->addSql('ALTER TABLE maison DROP FOREIGN KEY FK_F90CB66D924DD2B5');
        $this->addSql('DROP INDEX IDX_F90CB66D4CC8505A ON maison');
        $this->addSql('DROP INDEX IDX_F90CB66D924DD2B5 ON maison');
        $this->addSql('ALTER TABLE maison DROP offre_id, DROP localite_id, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B14CC8505A');
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B1924DD2B5');
        $this->addSql('DROP INDEX IDX_C87653B14CC8505A ON terrain');
        $this->addSql('DROP INDEX IDX_C87653B1924DD2B5 ON terrain');
        $this->addSql('ALTER TABLE terrain DROP offre_id, DROP localite_id, CHANGE description description VARCHAR(255) DEFAULT NULL');
    }
}
