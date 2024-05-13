<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513091412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP likes');
        $this->addSql('ALTER TABLE rendez_vous CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE rendez_vous RENAME INDEX fk_user TO IDX_65E8AA0AA76ED395');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD likes INT DEFAULT 0');
        $this->addSql('ALTER TABLE rendez_vous CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rendez_vous RENAME INDEX idx_65e8aa0aa76ed395 TO fk_user');
    }
}
