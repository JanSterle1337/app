<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220905121512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1137762C8AC');
        $this->addSql('DROP INDEX IDX_136AC1137762C8AC ON result');
        $this->addSql('ALTER TABLE result CHANGE game_round_id_id game_round_id INT NOT NULL');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113D44A386 FOREIGN KEY (game_round_id) REFERENCES game_round (id)');
        $this->addSql('CREATE INDEX IDX_136AC113D44A386 ON result (game_round_id)');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA37762C8AC');
        $this->addSql('DROP INDEX IDX_97A0ADA37762C8AC ON ticket');
        $this->addSql('ALTER TABLE ticket CHANGE game_round_id_id game_round_id INT NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3D44A386 FOREIGN KEY (game_round_id) REFERENCES game_round (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3D44A386 ON ticket (game_round_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113D44A386');
        $this->addSql('DROP INDEX IDX_136AC113D44A386 ON result');
        $this->addSql('ALTER TABLE result CHANGE game_round_id game_round_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1137762C8AC FOREIGN KEY (game_round_id_id) REFERENCES game_round (id)');
        $this->addSql('CREATE INDEX IDX_136AC1137762C8AC ON result (game_round_id_id)');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3D44A386');
        $this->addSql('DROP INDEX IDX_97A0ADA3D44A386 ON ticket');
        $this->addSql('ALTER TABLE ticket CHANGE game_round_id game_round_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA37762C8AC FOREIGN KEY (game_round_id_id) REFERENCES game_round (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA37762C8AC ON ticket (game_round_id_id)');
    }
}
