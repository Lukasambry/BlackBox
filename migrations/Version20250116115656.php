<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250116115656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix foreign key constraints and add user_id column to secret table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE secret ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT IF EXISTS FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE secret DROP CONSTRAINT IF EXISTS FK_5CA2E8E59D86650F');
        $this->addSql('DROP INDEX IF EXISTS IDX_5CA2E8E59D86650F');
        $this->addSql('ALTER TABLE secret ADD CONSTRAINT FK_5CA2E8E59D86650F FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5CA2E8E59D86650F ON secret (user_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE secret DROP CONSTRAINT IF EXISTS FK_5CA2E8E59D86650F');
        $this->addSql('DROP INDEX IF EXISTS IDX_5CA2E8E59D86650F');
        $this->addSql('ALTER TABLE secret DROP user_id');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT IF EXISTS FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}