<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217101833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE radiologist ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE radiologist ADD CONSTRAINT FK_96E0ED1CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96E0ED1CA76ED395 ON radiologist (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE radiologist DROP FOREIGN KEY FK_96E0ED1CA76ED395');
        $this->addSql('DROP INDEX UNIQ_96E0ED1CA76ED395 ON radiologist');
        $this->addSql('ALTER TABLE radiologist DROP user_id');
    }
}
