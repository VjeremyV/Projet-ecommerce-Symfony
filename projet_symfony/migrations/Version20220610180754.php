<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610180754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contenu ADD produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE contenu ADD CONSTRAINT FK_89C2003FF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_89C2003FF347EFB ON contenu (produit_id)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC273C1CC488');
        $this->addSql('DROP INDEX UNIQ_29A5EC273C1CC488 ON produit');
        $this->addSql('ALTER TABLE produit DROP contenu_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contenu DROP FOREIGN KEY FK_89C2003FF347EFB');
        $this->addSql('DROP INDEX UNIQ_89C2003FF347EFB ON contenu');
        $this->addSql('ALTER TABLE contenu DROP produit_id');
        $this->addSql('ALTER TABLE produit ADD contenu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC273C1CC488 FOREIGN KEY (contenu_id) REFERENCES contenu (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29A5EC273C1CC488 ON produit (contenu_id)');
    }
}
