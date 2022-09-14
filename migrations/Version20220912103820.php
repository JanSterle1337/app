<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220912103820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket ADD matched_combination_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA341AB491D FOREIGN KEY (matched_combination_id) REFERENCES combination (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97A0ADA341AB491D ON ticket (matched_combination_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA341AB491D');
        $this->addSql('DROP INDEX UNIQ_97A0ADA341AB491D ON ticket');
        $this->addSql('ALTER TABLE ticket DROP matched_combination_id');
    }
}
