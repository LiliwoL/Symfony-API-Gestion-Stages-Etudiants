<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308120605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE internship (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_student_id INTEGER NOT NULL, id_company_id INTEGER NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, CONSTRAINT FK_10D1B00C6E1ECFCD FOREIGN KEY (id_student_id) REFERENCES student (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_10D1B00C32119A01 FOREIGN KEY (id_company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_10D1B00C6E1ECFCD ON internship (id_student_id)');
        $this->addSql('CREATE INDEX IDX_10D1B00C32119A01 ON internship (id_company_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__company AS SELECT id, name, street, zipcode, city, website FROM company');
        $this->addSql('DROP TABLE company');
        $this->addSql('CREATE TABLE company (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) DEFAULT NULL, zipcode INTEGER DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO company (id, name, street, zipcode, city, website) SELECT id, name, street, zipcode, city, website FROM __temp__company');
        $this->addSql('DROP TABLE __temp__company');
        $this->addSql('CREATE TEMPORARY TABLE __temp__student AS SELECT id, name, firstname, picture, date_of_birth, grade FROM student');
        $this->addSql('DROP TABLE student');
        $this->addSql('CREATE TABLE student (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, date_of_birth DATE NOT NULL, grade VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO student (id, name, firstname, picture, date_of_birth, grade) SELECT id, name, firstname, picture, date_of_birth, grade FROM __temp__student');
        $this->addSql('DROP TABLE __temp__student');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE internship');
        $this->addSql('CREATE TEMPORARY TABLE __temp__company AS SELECT id, name, street, zipcode, city, website FROM company');
        $this->addSql('DROP TABLE company');
        $this->addSql('CREATE TABLE company (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(10) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO company (id, name, street, zipcode, city, website) SELECT id, name, street, zipcode, city, website FROM __temp__company');
        $this->addSql('DROP TABLE __temp__company');
        $this->addSql('CREATE TEMPORARY TABLE __temp__student AS SELECT id, name, firstname, picture, date_of_birth, grade FROM student');
        $this->addSql('DROP TABLE student');
        $this->addSql('CREATE TABLE student (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, date_of_birth DATE NOT NULL, grade VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO student (id, name, firstname, picture, date_of_birth, grade) SELECT id, name, firstname, picture, date_of_birth, grade FROM __temp__student');
        $this->addSql('DROP TABLE __temp__student');
    }
}
