<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210614000318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE element_data ADD element_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE element_data ADD CONSTRAINT FK_EECDD29A1F1F2A24 FOREIGN KEY (element_id) REFERENCES element (id)');
        $this->addSql('CREATE INDEX IDX_EECDD29A1F1F2A24 ON element_data (element_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE element_data DROP FOREIGN KEY FK_EECDD29A1F1F2A24');
        $this->addSql('DROP INDEX IDX_EECDD29A1F1F2A24 ON element_data');
        $this->addSql('ALTER TABLE element_data DROP element_id');
    }
}
