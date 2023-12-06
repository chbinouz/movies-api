<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231120105851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favori ADD movie_id INT NOT NULL');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CC8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('CREATE INDEX IDX_EF85A2CC8F93B6FC ON favori (movie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CC8F93B6FC');
        $this->addSql('DROP INDEX IDX_EF85A2CC8F93B6FC ON favori');
        $this->addSql('ALTER TABLE favori DROP movie_id');
    }
}
