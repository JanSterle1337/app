<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220901125735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_combination (id INT AUTO_INCREMENT NOT NULL, numbers LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticket ADD combination_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA37D949DCC FOREIGN KEY (combination_id) REFERENCES game_combination (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97A0ADA37D949DCC ON ticket (combination_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA37D949DCC');
        $this->addSql('DROP TABLE game_combination');
        $this->addSql('DROP INDEX UNIQ_97A0ADA37D949DCC ON ticket');
        $this->addSql('ALTER TABLE ticket DROP combination_id');
    }
}
