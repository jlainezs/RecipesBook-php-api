<?php
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20260718170802 extends AbstractMigration
{
    /**
     * Manual integrity reference.
     * Needed because we removed the dependency of IngredientType bounded context from Ingredient bounded context.
     */
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT fk_ingredient_ingredient_type FOREIGN KEY (ingredient_type_id) REFERENCES ingredient_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_ingredient_type_id ON ingredient (ingredient_type_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ingredient DROP CONSTRAINT fk_ingredient_ingredient_type');
        $this->addSql('DROP INDEX idx_ingredient_type_id');

    }
}
