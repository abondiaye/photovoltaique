<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250910115050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE availability_slot (id INT AUTO_INCREMENT NOT NULL, start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', capacity SMALLINT UNSIGNED NOT NULL, booked SMALLINT UNSIGNED NOT NULL, is_closed TINYINT(1) NOT NULL, label VARCHAR(120) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX idx_slot_start (start_at), INDEX idx_slot_end (end_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, note SMALLINT NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, location VARCHAR(255) NOT NULL, done_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE installation (id INT AUTO_INCREMENT NOT NULL, quote_id INT DEFAULT NULL, scheduled_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(20) NOT NULL, photos JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_1CBA6AB1DB805178 (quote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lead (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(120) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(30) DEFAULT NULL, roof_area_m2 DOUBLE PRECISION DEFAULT NULL, has_shadow TINYINT(1) NOT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maintenance_request (id INT AUTO_INCREMENT NOT NULL, surface_type VARCHAR(40) NOT NULL, full_name VARCHAR(120) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(30) DEFAULT NULL, address_line1 VARCHAR(180) NOT NULL, address_line2 VARCHAR(180) DEFAULT NULL, city VARCHAR(100) NOT NULL, postal_code VARCHAR(12) NOT NULL, type VARCHAR(20) NOT NULL, comment LONGTEXT DEFAULT NULL, requested_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', photos JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX idx_mr_status (status), INDEX idx_mr_requested_date (requested_date), INDEX idx_mr_created_at (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panneau (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, panneau VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, category VARCHAR(50) NOT NULL, power_w DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION DEFAULT NULL, specs JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quote (id INT AUTO_INCREMENT NOT NULL, lead_id INT NOT NULL, total_kw_p DOUBLE PRECISION NOT NULL, estimated_production_kwh_year DOUBLE PRECISION NOT NULL, net_price DOUBLE PRECISION NOT NULL, items JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6B71CBF455458D (lead_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realisation (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, done_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE testimonial (id INT AUTO_INCREMENT NOT NULL, author VARCHAR(120) NOT NULL, rating SMALLINT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_report (id INT AUTO_INCREMENT NOT NULL, request_id INT NOT NULL, panels_cleaned TINYINT(1) DEFAULT 0 NOT NULL, structure_checked TINYINT(1) DEFAULT 0 NOT NULL, wiring_secured TINYINT(1) DEFAULT 0 NOT NULL, inverter_checked TINYINT(1) DEFAULT 0 NOT NULL, debris_removed TINYINT(1) DEFAULT 0 NOT NULL, before_photos JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', after_photos JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', note LONGTEXT DEFAULT NULL, publish_on_site TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6FD6B1A6427EB8A5 (request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE installation ADD CONSTRAINT FK_1CBA6AB1DB805178 FOREIGN KEY (quote_id) REFERENCES quote (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF455458D FOREIGN KEY (lead_id) REFERENCES lead (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_report ADD CONSTRAINT FK_6FD6B1A6427EB8A5 FOREIGN KEY (request_id) REFERENCES maintenance_request (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE installation DROP FOREIGN KEY FK_1CBA6AB1DB805178');
        $this->addSql('ALTER TABLE quote DROP FOREIGN KEY FK_6B71CBF455458D');
        $this->addSql('ALTER TABLE work_report DROP FOREIGN KEY FK_6FD6B1A6427EB8A5');
        $this->addSql('DROP TABLE availability_slot');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE installation');
        $this->addSql('DROP TABLE lead');
        $this->addSql('DROP TABLE maintenance_request');
        $this->addSql('DROP TABLE panneau');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE quote');
        $this->addSql('DROP TABLE realisation');
        $this->addSql('DROP TABLE testimonial');
        $this->addSql('DROP TABLE work_report');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
