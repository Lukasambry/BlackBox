<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250130194746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add owner to room table with safe migration';
    }

    public function up(Schema $schema): void
    {
        // 1. Ajouter la colonne comme nullable
        $this->addSql('ALTER TABLE room ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_729F519B7E3C61F9 ON room (owner_id)');

        // 2. Mettre à jour les données existantes
        $this->addSql('
            UPDATE room r
            SET owner_id = (
                SELECT ru.user_id
                FROM room_user ru
                WHERE ru.room_id = r.id
                LIMIT 1
            )
            WHERE owner_id IS NULL
        ');

        // 3. Vérifier qu'il n'y a pas de valeurs NULL avant de rendre la colonne NOT NULL
        $this->addSql('
            DO $$
            BEGIN
                IF EXISTS (SELECT 1 FROM room WHERE owner_id IS NULL) THEN
                    RAISE EXCEPTION \'There are still rooms without owners\';
                END IF;
            END
            $$;
        ');

        // 4. Rendre la colonne NOT NULL seulement si tout est OK
        $this->addSql('ALTER TABLE room ALTER COLUMN owner_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE room DROP CONSTRAINT FK_729F519B7E3C61F9');
        $this->addSql('DROP INDEX IDX_729F519B7E3C61F9');
        $this->addSql('ALTER TABLE room DROP owner_id');
    }
}