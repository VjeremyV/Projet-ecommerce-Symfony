<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620131807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_produit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_group_produit (produit_id INT NOT NULL, group_produit_id INT NOT NULL, INDEX IDX_81FB1EF5F347EFB (produit_id), INDEX IDX_81FB1EF5D8F91F49 (group_produit_id), PRIMARY KEY(produit_id, group_produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_group_produit ADD CONSTRAINT FK_81FB1EF5F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_group_produit ADD CONSTRAINT FK_81FB1EF5D8F91F49 FOREIGN KEY (group_produit_id) REFERENCES group_produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit DROP group_produit');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_group_produit DROP FOREIGN KEY FK_81FB1EF5D8F91F49');
        $this->addSql('DROP TABLE group_produit');
        $this->addSql('DROP TABLE produit_group_produit');
        $this->addSql('ALTER TABLE produit ADD group_produit VARCHAR(255) DEFAULT NULL');
    }
}
