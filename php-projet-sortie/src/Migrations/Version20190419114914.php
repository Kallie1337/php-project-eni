<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190419114914 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE historic (id INT AUTO_INCREMENT NOT NULL, trip_id INT DEFAULT NULL, motivation VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_AD52EF56A5BC2E0E (trip_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE historic ADD CONSTRAINT FK_AD52EF56A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
        $this->addSql('DROP INDEX UNIQ_301C5D83A76ED395 ON trip_user_love');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_301C5D83A76ED395 ON trip_user_love (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE historic');
        $this->addSql('DROP INDEX UNIQ_301C5D83A76ED395 ON trip_user_love');
        $this->addSql('CREATE INDEX UNIQ_301C5D83A76ED395 ON trip_user_love (user_id)');
    }
}
