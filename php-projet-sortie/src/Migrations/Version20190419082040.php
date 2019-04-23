<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190419082040 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trip ADD location_id INT NOT NULL');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('CREATE INDEX IDX_7656F53B64D218E ON trip (location_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B64D218E');
        $this->addSql('DROP INDEX IDX_7656F53B64D218E ON trip');
        $this->addSql('ALTER TABLE trip DROP location_id');
    }
}
