<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20191123184315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'First version of Domain Model';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE talk_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE event_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE talk_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, name VARCHAR(255) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, badge_url VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN event.start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN event.end_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE talk (id INT NOT NULL, event_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, tags JSON DEFAULT NULL, first_time_speaker BOOLEAN NOT NULL, speaker_uuid VARCHAR(255) NOT NULL, speaker_username VARCHAR(255) NOT NULL, speaker_name VARCHAR(255) NOT NULL, speaker_email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9F24D5BB71F7E88B ON talk (event_id)');
        $this->addSql('CREATE TABLE event_comment (id INT NOT NULL, event_id INT DEFAULT NULL, posted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, rating INT NOT NULL, content_rating INT DEFAULT NULL, food_rating INT DEFAULT NULL, location_rating INT DEFAULT NULL, social_event_rating INT DEFAULT NULL, selected_tags JSON DEFAULT NULL, comment TEXT NOT NULL, author_uuid VARCHAR(255) NOT NULL, author_username VARCHAR(255) NOT NULL, author_name VARCHAR(255) NOT NULL, author_email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1123FBC371F7E88B ON event_comment (event_id)');
        $this->addSql('COMMENT ON COLUMN event_comment.posted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE talk_comment (id INT NOT NULL, talk_id INT NOT NULL, posted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, rating INT NOT NULL, selected_tags JSON DEFAULT NULL, comment TEXT NOT NULL, content_rating INT DEFAULT NULL, delivery_rating INT DEFAULT NULL, relevance_rating INT DEFAULT NULL, author_uuid VARCHAR(255) NOT NULL, author_username VARCHAR(255) NOT NULL, author_name VARCHAR(255) NOT NULL, author_email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5D30A2E36F0601D5 ON talk_comment (talk_id)');
        $this->addSql('COMMENT ON COLUMN talk_comment.posted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE talk ADD CONSTRAINT FK_9F24D5BB71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_comment ADD CONSTRAINT FK_1123FBC371F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE talk_comment ADD CONSTRAINT FK_5D30A2E36F0601D5 FOREIGN KEY (talk_id) REFERENCES talk (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE talk DROP CONSTRAINT FK_9F24D5BB71F7E88B');
        $this->addSql('ALTER TABLE event_comment DROP CONSTRAINT FK_1123FBC371F7E88B');
        $this->addSql('ALTER TABLE talk_comment DROP CONSTRAINT FK_5D30A2E36F0601D5');
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE talk_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE event_comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE talk_comment_id_seq CASCADE');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE talk');
        $this->addSql('DROP TABLE event_comment');
        $this->addSql('DROP TABLE talk_comment');
    }
}
