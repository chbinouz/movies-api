<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231120095619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE favori (id INT AUTO_INCREMENT NOT NULL, movie_id INT DEFAULT NULL, owner_id INT NOT NULL, rank INT NOT NULL, UNIQUE INDEX UNIQ_EF85A2CC8F93B6FC (movie_id), INDEX IDX_EF85A2CC7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, release_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', cover_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CC8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CC7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CC8F93B6FC');
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CC7E3C61F9');
        $this->addSql('DROP TABLE favori');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE `user`');
    }
}
