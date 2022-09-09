<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220908154518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1135774FDDC');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113D44A386');
        $this->addSql('DROP TABLE result');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, ticket_id_id INT NOT NULL, game_round_id INT NOT NULL, matched_combination LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', INDEX IDX_136AC113D44A386 (game_round_id), UNIQUE INDEX UNIQ_136AC1135774FDDC (ticket_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1135774FDDC FOREIGN KEY (ticket_id_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113D44A386 FOREIGN KEY (game_round_id) REFERENCES game_round (id)');
    }
}
