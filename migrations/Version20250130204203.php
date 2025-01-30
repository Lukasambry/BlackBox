<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250130204203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add game state tracking columns to room table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE room ADD current_state VARCHAR(20) DEFAULT NULL');
        $this->addSql("UPDATE room SET current_state = 'waiting' WHERE current_state IS NULL");
        $this->addSql('ALTER TABLE room ALTER COLUMN current_state SET NOT NULL');

        $this->addSql('ALTER TABLE room ADD starting_phase_started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE room ADD playing_phase_started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE room ADD voting_phase_started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN room.starting_phase_started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN room.playing_phase_started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN room.voting_phase_started_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE room DROP current_state');
        $this->addSql('ALTER TABLE room DROP starting_phase_started_at');
        $this->addSql('ALTER TABLE room DROP playing_phase_started_at');
        $this->addSql('ALTER TABLE room DROP voting_phase_started_at');
    }
}
