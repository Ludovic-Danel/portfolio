<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240623085359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artwork_tag (artwork_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_B9EB001EDB8FFA4 (artwork_id), INDEX IDX_B9EB001EBAD26311 (tag_id), PRIMARY KEY(artwork_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artwork_tag ADD CONSTRAINT FK_B9EB001EDB8FFA4 FOREIGN KEY (artwork_id) REFERENCES artwork (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artwork_tag ADD CONSTRAINT FK_B9EB001EBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artwork_tag DROP FOREIGN KEY FK_B9EB001EDB8FFA4');
        $this->addSql('ALTER TABLE artwork_tag DROP FOREIGN KEY FK_B9EB001EBAD26311');
        $this->addSql('DROP TABLE artwork_tag');
    }
}
