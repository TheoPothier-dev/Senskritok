<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261104103042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, tilte, description, year FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, year INTEGER NOT NULL)');
        $this->addSql('INSERT INTO movie (id, title, description, year) SELECT id, tilte, description, year FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, title, description, year FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tilte VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, year INTEGER NOT NULL)');
        $this->addSql('INSERT INTO movie (id, tilte, description, year) SELECT id, title, description, year FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
    }
}
