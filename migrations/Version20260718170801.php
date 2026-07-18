<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260718170801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient DROP CONSTRAINT fk_6baf7870c47b8755');
        $this->addSql('DROP INDEX idx_6baf7870c47b8755');
        $this->addSql('ALTER TABLE ingredient ALTER name TYPE VARCHAR');
        $this->addSql('ALTER TABLE ingredient_type ALTER name TYPE VARCHAR');
        $this->addSql('ALTER TABLE meal_course ALTER name TYPE VARCHAR');
        $this->addSql('ALTER TABLE recipe ALTER name TYPE VARCHAR');
        $this->addSql('ALTER TABLE season ALTER name TYPE VARCHAR');
        $this->addSql('ALTER TABLE unit_of_measure ALTER name TYPE VARCHAR');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT fk_6baf7870c47b8755 FOREIGN KEY (ingredient_type_id) REFERENCES ingredient_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6baf7870c47b8755 ON ingredient (ingredient_type_id)');
        $this->addSql('ALTER TABLE ingredient_type ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE meal_course ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE recipe ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE season ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE unit_of_measure ALTER name TYPE VARCHAR(255)');
    }
}
