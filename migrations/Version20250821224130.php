<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250821224130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE maintenance_request (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(120) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(30) DEFAULT NULL, address_line1 VARCHAR(180) NOT NULL, address_line2 VARCHAR(180) DEFAULT NULL, city VARCHAR(100) NOT NULL, postal_code VARCHAR(12) NOT NULL, type VARCHAR(20) NOT NULL, comment LONGTEXT DEFAULT NULL, requested_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', photos JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX idx_mr_status (status), INDEX idx_mr_requested_date (requested_date), INDEX idx_mr_created_at (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE maintenance_request');
    }
}
