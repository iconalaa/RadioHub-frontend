<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240215162059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compte_rendu (id INT AUTO_INCREMENT NOT NULL, id_medecin_id INT DEFAULT NULL, id_image_id INT DEFAULT NULL, interpretation_med VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL, interpretation_rad VARCHAR(255) DEFAULT NULL, INDEX IDX_D39E69D2A1799A53 (id_medecin_id), UNIQUE INDEX UNIQ_D39E69D26D7EC9F8 (id_image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, radiologist_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, owner VARCHAR(255) NOT NULL, guest VARCHAR(255) NOT NULL, INDEX IDX_E01FBE6A38A7F06B (radiologist_id), INDEX IDX_E01FBE6A6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medecin (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(15) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ordonnance (id INT AUTO_INCREMENT NOT NULL, compterendu_id INT DEFAULT NULL, contenu VARCHAR(255) NOT NULL, date DATE NOT NULL, UNIQUE INDEX UNIQ_924B326C55B04C6 (compterendu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE radiologist (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compte_rendu ADD CONSTRAINT FK_D39E69D2A1799A53 FOREIGN KEY (id_medecin_id) REFERENCES medecin (id)');
        $this->addSql('ALTER TABLE compte_rendu ADD CONSTRAINT FK_D39E69D26D7EC9F8 FOREIGN KEY (id_image_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A38A7F06B FOREIGN KEY (radiologist_id) REFERENCES radiologist (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C55B04C6 FOREIGN KEY (compterendu_id) REFERENCES compte_rendu (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte_rendu DROP FOREIGN KEY FK_D39E69D2A1799A53');
        $this->addSql('ALTER TABLE compte_rendu DROP FOREIGN KEY FK_D39E69D26D7EC9F8');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A38A7F06B');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A6B899279');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C55B04C6');
        $this->addSql('DROP TABLE compte_rendu');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE medecin');
        $this->addSql('DROP TABLE ordonnance');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE radiologist');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
