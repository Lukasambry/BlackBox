<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129212557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE room_user (room_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(room_id, user_id))');
        $this->addSql('CREATE INDEX IDX_EE973C2D54177093 ON room_user (room_id)');
        $this->addSql('CREATE INDEX IDX_EE973C2DA76ED395 ON room_user (user_id)');
        $this->addSql('ALTER TABLE room_user ADD CONSTRAINT FK_EE973C2D54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_user ADD CONSTRAINT FK_EE973C2DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_room DROP CONSTRAINT fk_81e1d52a76ed395');
        $this->addSql('ALTER TABLE user_room DROP CONSTRAINT fk_81e1d5254177093');
        $this->addSql('DROP TABLE user_room');
        $this->addSql('ALTER TABLE reset_password_request ADD hashed_token VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE room ADD invite_code VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE room ADD is_started BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE room ALTER start_time TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE room ALTER start_time SET NOT NULL');
        $this->addSql('ALTER TABLE room ALTER end_time TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE room ALTER end_time SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN room.start_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN room.end_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_729F519B6F21F112 ON room (invite_code)');
        $this->addSql('ALTER INDEX idx_5ca2e8e59d86650f RENAME TO IDX_5CA2E8E5A76ED395');
        $this->addSql('ALTER TABLE "user" ALTER is_verified DROP DEFAULT');
        $this->addSql('ALTER INDEX idx_5a10856476ed395 RENAME TO IDX_5A108564A76ED395');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE user_room (user_id INT NOT NULL, room_id INT NOT NULL, PRIMARY KEY(user_id, room_id))');
        $this->addSql('CREATE INDEX idx_81e1d5254177093 ON user_room (room_id)');
        $this->addSql('CREATE INDEX idx_81e1d52a76ed395 ON user_room (user_id)');
        $this->addSql('ALTER TABLE user_room ADD CONSTRAINT fk_81e1d52a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_room ADD CONSTRAINT fk_81e1d5254177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_user DROP CONSTRAINT FK_EE973C2D54177093');
        $this->addSql('ALTER TABLE room_user DROP CONSTRAINT FK_EE973C2DA76ED395');
        $this->addSql('DROP TABLE room_user');
        $this->addSql('ALTER TABLE "user" ALTER is_verified SET DEFAULT false');
        $this->addSql('ALTER TABLE reset_password_request DROP hashed_token');
        $this->addSql('ALTER INDEX idx_5ca2e8e5a76ed395 RENAME TO idx_5ca2e8e59d86650f');
        $this->addSql('DROP INDEX UNIQ_729F519B6F21F112');
        $this->addSql('ALTER TABLE room DROP invite_code');
        $this->addSql('ALTER TABLE room DROP is_started');
        $this->addSql('ALTER TABLE room ALTER start_time TYPE DATE');
        $this->addSql('ALTER TABLE room ALTER start_time DROP NOT NULL');
        $this->addSql('ALTER TABLE room ALTER end_time TYPE DATE');
        $this->addSql('ALTER TABLE room ALTER end_time DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN room.start_time IS NULL');
        $this->addSql('COMMENT ON COLUMN room.end_time IS NULL');
        $this->addSql('ALTER INDEX idx_5a108564a76ed395 RENAME TO idx_5a10856476ed395');
    }
}
