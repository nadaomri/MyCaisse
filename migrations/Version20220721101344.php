<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220721101344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE caisse ADD solde_id INT DEFAULT NULL, DROP solde');
        $this->addSql('ALTER TABLE caisse ADD CONSTRAINT FK_B2A353C8BC7F70A9 FOREIGN KEY (solde_id) REFERENCES machine (id)');
        $this->addSql('CREATE INDEX IDX_B2A353C8BC7F70A9 ON caisse (solde_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE caisse DROP FOREIGN KEY FK_B2A353C8BC7F70A9');
        $this->addSql('DROP INDEX IDX_B2A353C8BC7F70A9 ON caisse');
        $this->addSql('ALTER TABLE caisse ADD solde DOUBLE PRECISION NOT NULL, DROP solde_id');
    }
}
