<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260805163906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient DROP CONSTRAINT fk_ingredient_ingredient_type');
        $this->addSql('DROP INDEX idx_ingredient_type_id');
        $this->addSql('ALTER TABLE recipe_ingredient DROP CONSTRAINT fk_22d1fe13933fe08c');
        $this->addSql('ALTER TABLE recipe_ingredient DROP CONSTRAINT fk_22d1fe13da4e2c90');
        $this->addSql('DROP INDEX idx_22d1fe13933fe08c');
        $this->addSql('DROP INDEX idx_22d1fe13da4e2c90');
        $this->addSql('ALTER TABLE recipe_ingredient ALTER ingredient_id SET NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredient ALTER unit_of_measure_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT fk_ingredient_ingredient_type FOREIGN KEY (ingredient_type_id) REFERENCES ingredient_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_ingredient_type_id ON ingredient (ingredient_type_id)');
        $this->addSql('ALTER TABLE recipe_ingredient ALTER ingredient_id DROP NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredient ALTER unit_of_measure_id DROP NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT fk_22d1fe13933fe08c FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT fk_22d1fe13da4e2c90 FOREIGN KEY (unit_of_measure_id) REFERENCES unit_of_measure (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_22d1fe13933fe08c ON recipe_ingredient (ingredient_id)');
        $this->addSql('CREATE INDEX idx_22d1fe13da4e2c90 ON recipe_ingredient (unit_of_measure_id)');
    }
}
