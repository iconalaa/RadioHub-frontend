<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220220755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD radiologist_id INT NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F38A7F06B FOREIGN KEY (radiologist_id) REFERENCES radiologist (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F38A7F06B ON image (radiologist_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F38A7F06B');
        $this->addSql('DROP INDEX IDX_C53D045F38A7F06B ON image');
        $this->addSql('ALTER TABLE image DROP radiologist_id');
    }
}
