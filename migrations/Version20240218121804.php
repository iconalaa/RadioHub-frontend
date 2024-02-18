<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218121804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64938A7F06B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496B899279');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64987F4FB17');
        $this->addSql('DROP INDEX UNIQ_8D93D64938A7F06B ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6496B899279 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D64987F4FB17 ON user');
        $this->addSql('ALTER TABLE user DROP doctor_id, DROP patient_id, DROP radiologist_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD doctor_id INT DEFAULT NULL, ADD patient_id INT DEFAULT NULL, ADD radiologist_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64938A7F06B FOREIGN KEY (radiologist_id) REFERENCES radiologist (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64987F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64938A7F06B ON user (radiologist_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496B899279 ON user (patient_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64987F4FB17 ON user (doctor_id)');
    }
}
