<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220912104658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_round ADD drawn_combination_id INT DEFAULT NULL, DROP drawn_combination');
        $this->addSql('ALTER TABLE game_round ADD CONSTRAINT FK_F7DD93BB4104530F FOREIGN KEY (drawn_combination_id) REFERENCES combination (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7DD93BB4104530F ON game_round (drawn_combination_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_round DROP FOREIGN KEY FK_F7DD93BB4104530F');
        $this->addSql('DROP INDEX UNIQ_F7DD93BB4104530F ON game_round');
        $this->addSql('ALTER TABLE game_round ADD drawn_combination LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', DROP drawn_combination_id');
    }
}
