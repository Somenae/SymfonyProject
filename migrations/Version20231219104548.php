<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231219104548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admins CHANGE role role JSON NOT NULL');
        $this->addSql('ALTER TABLE sales ADD name VARCHAR(55) NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE role role JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admins CHANGE role role LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE sales DROP name');
        $this->addSql('ALTER TABLE users CHANGE role role LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}
