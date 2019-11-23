<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20191123195139 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'SfConnectUser username can be null';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE talk ALTER speaker_username DROP NOT NULL');
        $this->addSql('ALTER TABLE event_comment ALTER author_username DROP NOT NULL');
        $this->addSql('ALTER TABLE talk_comment ALTER author_username DROP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE talk ALTER speaker_username SET NOT NULL');
        $this->addSql('ALTER TABLE event_comment ALTER author_username SET NOT NULL');
        $this->addSql('ALTER TABLE talk_comment ALTER author_username SET NOT NULL');
    }
}
