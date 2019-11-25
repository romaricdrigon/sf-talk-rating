<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20191123235743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Renamed Comment to Review';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE event_comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE talk_comment_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE event_review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE talk_review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event_review (id INT NOT NULL, event_id INT DEFAULT NULL, posted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, rating INT NOT NULL, content_rating INT DEFAULT NULL, food_rating INT DEFAULT NULL, location_rating INT DEFAULT NULL, social_event_rating INT DEFAULT NULL, selected_tags JSON DEFAULT NULL, comment TEXT NOT NULL, author_uuid VARCHAR(255) NOT NULL, author_username VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) NOT NULL, author_email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4BDAF69471F7E88B ON event_review (event_id)');
        $this->addSql('COMMENT ON COLUMN event_review.posted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE talk_review (id INT NOT NULL, talk_id INT NOT NULL, posted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, rating INT NOT NULL, selected_tags JSON DEFAULT NULL, comment TEXT NOT NULL, content_rating INT DEFAULT NULL, delivery_rating INT DEFAULT NULL, relevance_rating INT DEFAULT NULL, author_uuid VARCHAR(255) NOT NULL, author_username VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) NOT NULL, author_email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_319388B56F0601D5 ON talk_review (talk_id)');
        $this->addSql('COMMENT ON COLUMN talk_review.posted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE event_review ADD CONSTRAINT FK_4BDAF69471F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE talk_review ADD CONSTRAINT FK_319388B56F0601D5 FOREIGN KEY (talk_id) REFERENCES talk (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE event_comment');
        $this->addSql('DROP TABLE talk_comment');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE event_review_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE talk_review_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE event_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE talk_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event_comment (id INT NOT NULL, event_id INT DEFAULT NULL, posted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, rating INT NOT NULL, content_rating INT DEFAULT NULL, food_rating INT DEFAULT NULL, location_rating INT DEFAULT NULL, social_event_rating INT DEFAULT NULL, selected_tags JSON DEFAULT NULL, comment TEXT NOT NULL, author_uuid VARCHAR(255) NOT NULL, author_username VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) NOT NULL, author_email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_1123fbc371f7e88b ON event_comment (event_id)');
        $this->addSql('COMMENT ON COLUMN event_comment.posted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE talk_comment (id INT NOT NULL, talk_id INT NOT NULL, posted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, rating INT NOT NULL, selected_tags JSON DEFAULT NULL, comment TEXT NOT NULL, content_rating INT DEFAULT NULL, delivery_rating INT DEFAULT NULL, relevance_rating INT DEFAULT NULL, author_uuid VARCHAR(255) NOT NULL, author_username VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) NOT NULL, author_email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_5d30a2e36f0601d5 ON talk_comment (talk_id)');
        $this->addSql('COMMENT ON COLUMN talk_comment.posted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE event_comment ADD CONSTRAINT fk_1123fbc371f7e88b FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE talk_comment ADD CONSTRAINT fk_5d30a2e36f0601d5 FOREIGN KEY (talk_id) REFERENCES talk (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE event_review');
        $this->addSql('DROP TABLE talk_review');
    }
}
