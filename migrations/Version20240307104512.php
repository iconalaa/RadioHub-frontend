<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307104512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE donateur (id INT AUTO_INCREMENT NOT NULL, nom_donateur VARCHAR(255) NOT NULL, prenom_donateur VARCHAR(255) NOT NULL, type_donateur VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone BIGINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gratification (id INT AUTO_INCREMENT NOT NULL, id_donateur_id INT NOT NULL, date_grat DATE DEFAULT NULL, titre_grat VARCHAR(255) NOT NULL, desc_grat LONGTEXT DEFAULT NULL, type_grat VARCHAR(255) NOT NULL, montant INT DEFAULT NULL, type_machine VARCHAR(255) DEFAULT NULL, INDEX IDX_E311A15A128E4D14 (id_donateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gratification ADD CONSTRAINT FK_E311A15A128E4D14 FOREIGN KEY (id_donateur_id) REFERENCES donateur (id)');
        $this->addSql('ALTER TABLE image CHANGE dateajout dateajout DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gratification DROP FOREIGN KEY FK_E311A15A128E4D14');
        $this->addSql('DROP TABLE donateur');
        $this->addSql('DROP TABLE gratification');
        $this->addSql('ALTER TABLE image CHANGE dateajout dateajout DATE DEFAULT NULL');
    }
}
