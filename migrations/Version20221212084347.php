<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221212084347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD nom VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE image ADD appartements_id INT DEFAULT NULL, ADD immeubles_id INT DEFAULT NULL, ADD maisons_id INT DEFAULT NULL, ADD terrains_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FCC24952C FOREIGN KEY (appartements_id) REFERENCES appartement (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F5174E696 FOREIGN KEY (immeubles_id) REFERENCES immeuble (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FA1856484 FOREIGN KEY (maisons_id) REFERENCES maison (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FE620E1A8 FOREIGN KEY (terrains_id) REFERENCES terrain (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FCC24952C ON image (appartements_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F5174E696 ON image (immeubles_id)');
        $this->addSql('CREATE INDEX IDX_C53D045FA1856484 ON image (maisons_id)');
        $this->addSql('CREATE INDEX IDX_C53D045FE620E1A8 ON image (terrains_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP nom');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FCC24952C');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F5174E696');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FA1856484');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FE620E1A8');
        $this->addSql('DROP INDEX IDX_C53D045FCC24952C ON image');
        $this->addSql('DROP INDEX IDX_C53D045F5174E696 ON image');
        $this->addSql('DROP INDEX IDX_C53D045FA1856484 ON image');
        $this->addSql('DROP INDEX IDX_C53D045FE620E1A8 ON image');
        $this->addSql('ALTER TABLE image DROP appartements_id, DROP immeubles_id, DROP maisons_id, DROP terrains_id');
    }
}
