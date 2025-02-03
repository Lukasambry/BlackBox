<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250202161617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE room ADD current_voting_secret_index INT DEFAULT NULL');
        $this->addSql('ALTER TABLE room ADD current_voting_started_at TIMESTAMP(0) WITHOUT TIME ZONE NULL');
        $this->addSql('COMMENT ON COLUMN room.current_voting_started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE vote ADD is_positive BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vote DROP is_positive');
        $this->addSql('ALTER TABLE room DROP current_voting_secret_index');
        $this->addSql('ALTER TABLE room DROP current_voting_started_at');
    }
}
