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
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE room (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN room.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN room.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE secret (id SERIAL NOT NULL, room_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5CA2E8E554177093 ON secret (room_id)');
        $this->addSql('COMMENT ON COLUMN secret.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN secret.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_room (user_id INT NOT NULL, room_id INT NOT NULL, PRIMARY KEY(user_id, room_id))');
        $this->addSql('CREATE INDEX IDX_81E1D52A76ED395 ON user_room (user_id)');
        $this->addSql('CREATE INDEX IDX_81E1D5254177093 ON user_room (room_id)');
        $this->addSql('CREATE TABLE vote (id SERIAL NOT NULL, secret_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A10856467176C07 ON vote (secret_id)');
        $this->addSql('COMMENT ON COLUMN vote.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN vote.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE secret ADD CONSTRAINT FK_5CA2E8E554177093 FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_room ADD CONSTRAINT FK_81E1D52A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_room ADD CONSTRAINT FK_81E1D5254177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A10856467176C07 FOREIGN KEY (secret_id) REFERENCES secret (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD vote_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64972DCDAFC FOREIGN KEY (vote_id) REFERENCES vote (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D64972DCDAFC ON "user" (vote_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64972DCDAFC');
        $this->addSql('ALTER TABLE secret DROP CONSTRAINT FK_5CA2E8E554177093');
        $this->addSql('ALTER TABLE user_room DROP CONSTRAINT FK_81E1D52A76ED395');
        $this->addSql('ALTER TABLE user_room DROP CONSTRAINT FK_81E1D5254177093');
        $this->addSql('ALTER TABLE vote DROP CONSTRAINT FK_5A10856467176C07');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE secret');
        $this->addSql('DROP TABLE user_room');
        $this->addSql('DROP TABLE vote');
        $this->addSql('DROP INDEX IDX_8D93D64972DCDAFC');
        $this->addSql('ALTER TABLE "user" DROP vote_id');
    }
}
