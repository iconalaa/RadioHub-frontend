<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221032527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE interpretation (id INT AUTO_INCREMENT NOT NULL, radiologist_id INT DEFAULT NULL, image_id INT DEFAULT NULL, interpretation VARCHAR(255) NOT NULL, sendat VARCHAR(255) NOT NULL, INDEX IDX_EBDBD11738A7F06B (radiologist_id), INDEX IDX_EBDBD1173DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE interpretation ADD CONSTRAINT FK_EBDBD11738A7F06B FOREIGN KEY (radiologist_id) REFERENCES radiologist (id)');
        $this->addSql('ALTER TABLE interpretation ADD CONSTRAINT FK_EBDBD1173DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE image CHANGE filename filename VARCHAR(255) NOT NULL, CHANGE dateajout dateajout DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE interpretation DROP FOREIGN KEY FK_EBDBD11738A7F06B');
        $this->addSql('ALTER TABLE interpretation DROP FOREIGN KEY FK_EBDBD1173DA5256D');
        $this->addSql('DROP TABLE interpretation');
        $this->addSql('ALTER TABLE image CHANGE filename filename VARCHAR(255) DEFAULT NULL, CHANGE dateajout dateajout DATE DEFAULT NULL');
    }
}
