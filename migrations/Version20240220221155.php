<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220221155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE droit (id INT AUTO_INCREMENT NOT NULL, radioloqist_id INT DEFAULT NULL, image_id INT DEFAULT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_CB7AA7513CA9599F (radioloqist_id), INDEX IDX_CB7AA7513DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE droit ADD CONSTRAINT FK_CB7AA7513CA9599F FOREIGN KEY (radioloqist_id) REFERENCES radiologist (id)');
        $this->addSql('ALTER TABLE droit ADD CONSTRAINT FK_CB7AA7513DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE droit DROP FOREIGN KEY FK_CB7AA7513CA9599F');
        $this->addSql('ALTER TABLE droit DROP FOREIGN KEY FK_CB7AA7513DA5256D');
        $this->addSql('DROP TABLE droit');
    }
}
