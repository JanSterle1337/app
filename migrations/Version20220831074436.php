<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831074436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result ADD ticket_id_id INT NOT NULL, ADD game_round_id_id INT NOT NULL, DROP ticket_id, DROP game_round_id');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1135774FDDC FOREIGN KEY (ticket_id_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1137762C8AC FOREIGN KEY (game_round_id_id) REFERENCES game_round (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_136AC1135774FDDC ON result (ticket_id_id)');
        $this->addSql('CREATE INDEX IDX_136AC1137762C8AC ON result (game_round_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1135774FDDC');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1137762C8AC');
        $this->addSql('DROP INDEX UNIQ_136AC1135774FDDC ON result');
        $this->addSql('DROP INDEX IDX_136AC1137762C8AC ON result');
        $this->addSql('ALTER TABLE result ADD ticket_id INT NOT NULL, ADD game_round_id INT NOT NULL, DROP ticket_id_id, DROP game_round_id_id');
    }
}
