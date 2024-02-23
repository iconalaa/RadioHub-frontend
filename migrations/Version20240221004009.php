<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221004009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB38A7F06B');
        $this->addSql('DROP INDEX IDX_1ADAD7EB38A7F06B ON patient');
        $this->addSql('ALTER TABLE patient DROP radiologist_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient ADD radiologist_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB38A7F06B FOREIGN KEY (radiologist_id) REFERENCES radiologist (id)');
        $this->addSql('CREATE INDEX IDX_1ADAD7EB38A7F06B ON patient (radiologist_id)');
    }
}
