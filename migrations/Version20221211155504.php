<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221211155504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, bien_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C53D045FBD95B80F (bien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FBD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id)');
        $this->addSql('ALTER TABLE offre CHANGE nom_role type_offre VARCHAR(25) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE prenom prenom VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FBD95B80F');
        $this->addSql('DROP TABLE image');
        $this->addSql('ALTER TABLE offre CHANGE type_offre nom_role VARCHAR(25) NOT NULL');
        $this->addSql('ALTER TABLE `user` CHANGE prenom prenom VARCHAR(50) DEFAULT NULL');
    }
}
