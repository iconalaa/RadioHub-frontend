<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307235622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526C7294869C (article_id), INDEX IDX_9474526C79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte_rendu (id INT AUTO_INCREMENT NOT NULL, id_doctor_id INT DEFAULT NULL, id_image_id INT DEFAULT NULL, interpretation_med VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL, interpretation_rad VARCHAR(255) DEFAULT NULL, is_edited TINYINT(1) NOT NULL, INDEX IDX_D39E69D27C14730 (id_doctor_id), UNIQUE INDEX UNIQ_D39E69D26D7EC9F8 (id_image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doctor (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, matricule VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1FC0F36AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donateur (id INT AUTO_INCREMENT NOT NULL, nom_donateur VARCHAR(255) NOT NULL, prenom_donateur VARCHAR(255) NOT NULL, type_donateur VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone BIGINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE droit (id INT AUTO_INCREMENT NOT NULL, radioloqist_id INT DEFAULT NULL, image_id INT DEFAULT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_CB7AA7513CA9599F (radioloqist_id), INDEX IDX_CB7AA7513DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gratification (id INT AUTO_INCREMENT NOT NULL, id_donateur_id INT NOT NULL, date_grat DATE DEFAULT NULL, titre_grat VARCHAR(255) NOT NULL, desc_grat LONGTEXT DEFAULT NULL, type_grat VARCHAR(255) NOT NULL, montant INT DEFAULT NULL, type_machine VARCHAR(255) DEFAULT NULL, INDEX IDX_E311A15A128E4D14 (id_donateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, radiologist_id INT NOT NULL, patient_id INT DEFAULT NULL, bodypart VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) NOT NULL, aquisation_date DATE NOT NULL, dateajout DATE NOT NULL, INDEX IDX_C53D045F38A7F06B (radiologist_id), INDEX IDX_C53D045F6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interpretation (id INT AUTO_INCREMENT NOT NULL, radiologist_id INT DEFAULT NULL, image_id INT DEFAULT NULL, interpretation VARCHAR(255) NOT NULL, sendat VARCHAR(255) NOT NULL, urgency VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_EBDBD11738A7F06B (radiologist_id), INDEX IDX_EBDBD1173DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, cas_med VARCHAR(255) NOT NULL, n_cnam VARCHAR(255) NOT NULL, assurance VARCHAR(255) NOT NULL, num_assurance VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1ADAD7EBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prescription (id INT AUTO_INCREMENT NOT NULL, compterendu_id INT DEFAULT NULL, contenu VARCHAR(255) NOT NULL, date DATE NOT NULL, signature_filename VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1FBFB8D955B04C6 (compterendu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE radiologist (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, mat_cnom VARCHAR(255) NOT NULL, dispo TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_96E0ED1CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, id_user VARCHAR(255) NOT NULL, etat_rec TINYINT(1) NOT NULL, date_rec DATETIME NOT NULL, desc_rec VARCHAR(255) NOT NULL, type_rec VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, salle_id INT DEFAULT NULL, date_rv DATETIME NOT NULL, type_exam VARCHAR(255) NOT NULL, nom_patient VARCHAR(255) NOT NULL, prenom_patient VARCHAR(255) NOT NULL, mail_patient VARCHAR(255) NOT NULL, INDEX IDX_65E8AA0ADC304035 (salle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, reclamation_id INT DEFAULT NULL, desc_rep VARCHAR(255) NOT NULL, date_rep DATETIME NOT NULL, UNIQUE INDEX UNIQ_5FB6DEC72D6BA2D9 (reclamation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, num_salle VARCHAR(255) NOT NULL, num_dep VARCHAR(255) NOT NULL, etat_salle VARCHAR(255) NOT NULL, type_salle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, brochure_filename VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) NOT NULL, date_birth DATE NOT NULL, gender VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE compte_rendu ADD CONSTRAINT FK_D39E69D27C14730 FOREIGN KEY (id_doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE compte_rendu ADD CONSTRAINT FK_D39E69D26D7EC9F8 FOREIGN KEY (id_image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE doctor ADD CONSTRAINT FK_1FC0F36AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE droit ADD CONSTRAINT FK_CB7AA7513CA9599F FOREIGN KEY (radioloqist_id) REFERENCES radiologist (id)');
        $this->addSql('ALTER TABLE droit ADD CONSTRAINT FK_CB7AA7513DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE gratification ADD CONSTRAINT FK_E311A15A128E4D14 FOREIGN KEY (id_donateur_id) REFERENCES donateur (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F38A7F06B FOREIGN KEY (radiologist_id) REFERENCES radiologist (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE interpretation ADD CONSTRAINT FK_EBDBD11738A7F06B FOREIGN KEY (radiologist_id) REFERENCES radiologist (id)');
        $this->addSql('ALTER TABLE interpretation ADD CONSTRAINT FK_EBDBD1173DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D955B04C6 FOREIGN KEY (compterendu_id) REFERENCES compte_rendu (id)');
        $this->addSql('ALTER TABLE radiologist ADD CONSTRAINT FK_96E0ED1CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0ADC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC72D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C79F37AE5');
        $this->addSql('ALTER TABLE compte_rendu DROP FOREIGN KEY FK_D39E69D27C14730');
        $this->addSql('ALTER TABLE compte_rendu DROP FOREIGN KEY FK_D39E69D26D7EC9F8');
        $this->addSql('ALTER TABLE doctor DROP FOREIGN KEY FK_1FC0F36AA76ED395');
        $this->addSql('ALTER TABLE droit DROP FOREIGN KEY FK_CB7AA7513CA9599F');
        $this->addSql('ALTER TABLE droit DROP FOREIGN KEY FK_CB7AA7513DA5256D');
        $this->addSql('ALTER TABLE gratification DROP FOREIGN KEY FK_E311A15A128E4D14');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F38A7F06B');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F6B899279');
        $this->addSql('ALTER TABLE interpretation DROP FOREIGN KEY FK_EBDBD11738A7F06B');
        $this->addSql('ALTER TABLE interpretation DROP FOREIGN KEY FK_EBDBD1173DA5256D');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBA76ED395');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D955B04C6');
        $this->addSql('ALTER TABLE radiologist DROP FOREIGN KEY FK_96E0ED1CA76ED395');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0ADC304035');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC72D6BA2D9');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE compte_rendu');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('DROP TABLE donateur');
        $this->addSql('DROP TABLE droit');
        $this->addSql('DROP TABLE gratification');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE interpretation');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE prescription');
        $this->addSql('DROP TABLE radiologist');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
