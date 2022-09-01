<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831072552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_round ADD game_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE game_round ADD CONSTRAINT FK_F7DD93BB4D77E7D8 FOREIGN KEY (game_id_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_F7DD93BB4D77E7D8 ON game_round (game_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_round DROP FOREIGN KEY FK_F7DD93BB4D77E7D8');
        $this->addSql('DROP INDEX IDX_F7DD93BB4D77E7D8 ON game_round');
        $this->addSql('ALTER TABLE game_round DROP game_id_id');
    }
}
