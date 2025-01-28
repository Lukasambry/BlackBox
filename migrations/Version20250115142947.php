<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250115142947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE room (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, is_private BOOLEAN NOT NULL, max_capacity INTEGER NOT NULL, start_time DATE, end_time DATE, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN room.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN room.updated_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('CREATE TABLE secret (id SERIAL NOT NULL, user_id INT DEFAULT NULL, room_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, votes_up INTEGER DEFAULT NULL, votes_down INTEGER DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5CA2E8E554177093 ON secret (room_id)');
        $this->addSql('CREATE INDEX IDX_5CA2E8E59D86650F ON secret (user_id)');
        $this->addSql('COMMENT ON COLUMN secret.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN secret.updated_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('CREATE TABLE user_room (user_id INT NOT NULL, room_id INT NOT NULL, PRIMARY KEY(user_id, room_id))');
        $this->addSql('CREATE INDEX IDX_81E1D52A76ED395 ON user_room (user_id)');
        $this->addSql('CREATE INDEX IDX_81E1D5254177093 ON user_room (room_id)');

        $this->addSql('CREATE TABLE vote (id SERIAL NOT NULL, user_id INT DEFAULT NULL, secret_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A10856467176C07 ON vote (secret_id)');
        $this->addSql('CREATE INDEX IDX_5A10856476ED395 ON vote (user_id)');
        $this->addSql('COMMENT ON COLUMN vote.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN vote.updated_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('CREATE TABLE theme (id SERIAL NOT NULL, question VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN theme.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN theme.updated_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('CREATE TABLE reset_password_request (id SERIAL NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('COMMENT ON COLUMN reset_password_request.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN reset_password_request.expires_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('ALTER TABLE secret ADD CONSTRAINT FK_5CA2E8E554177093 FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE secret ADD CONSTRAINT FK_5CA2E8E59D86650F FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE user_room ADD CONSTRAINT FK_81E1D52A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_room ADD CONSTRAINT FK_81E1D5254177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A10856467176C07 FOREIGN KEY (secret_id) REFERENCES secret (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A10856476ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE room ADD theme_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_729F519B59027487 ON room (theme_id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vote DROP CONSTRAINT FK_5A10856467176C07');
        $this->addSql('ALTER TABLE vote DROP CONSTRAINT FK_5A10856476ED395');
        $this->addSql('ALTER TABLE user_room DROP CONSTRAINT FK_81E1D5254177093');
        $this->addSql('ALTER TABLE user_room DROP CONSTRAINT FK_81E1D52A76ED395');
        $this->addSql('ALTER TABLE secret DROP CONSTRAINT FK_5CA2E8E59D86650F');
        $this->addSql('ALTER TABLE secret DROP CONSTRAINT FK_5CA2E8E554177093');
        $this->addSql('ALTER TABLE "user" DROP is_verified');
        $this->addSql('ALTER TABLE room DROP CONSTRAINT FK_729F519B59027487');
        $this->addSql('ALTER TABLE room DROP theme_id');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AA76ED395');

        $this->addSql('DROP INDEX IF EXISTS IDX_8D93D64972DCDAFC');
        $this->addSql('DROP INDEX IF EXISTS IDX_729F519B59027487');
        $this->addSql('DROP INDEX IF EXISTS IDX_5CA2E8E59D86650F');

        $this->addSql('DROP TABLE vote');
        $this->addSql('DROP TABLE user_room');
        $this->addSql('DROP TABLE secret');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE reset_password_request');
    }
}
