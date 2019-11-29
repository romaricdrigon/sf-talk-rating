<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20191129130741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Removed EventReview';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE event_review_id_seq CASCADE');
        $this->addSql('DROP TABLE event_review');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE event_review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event_review (id INT NOT NULL, event_id INT DEFAULT NULL, posted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, rating INT NOT NULL, content_rating INT DEFAULT NULL, food_rating INT DEFAULT NULL, location_rating INT DEFAULT NULL, social_event_rating INT DEFAULT NULL, selected_tags JSON DEFAULT NULL, comment TEXT NOT NULL, author_uuid VARCHAR(255) NOT NULL, author_username VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) NOT NULL, author_email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_4bdaf69471f7e88b ON event_review (event_id)');
        $this->addSql('COMMENT ON COLUMN event_review.posted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE event_review ADD CONSTRAINT fk_4bdaf69471f7e88b FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
