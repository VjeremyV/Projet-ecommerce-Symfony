<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613065412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_caracteristiques (produit_id INT NOT NULL, caracteristiques_id INT NOT NULL, INDEX IDX_922CA595F347EFB (produit_id), INDEX IDX_922CA595B2639FE4 (caracteristiques_id), PRIMARY KEY(produit_id, caracteristiques_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_caracteristiques ADD CONSTRAINT FK_922CA595F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_caracteristiques ADD CONSTRAINT FK_922CA595B2639FE4 FOREIGN KEY (caracteristiques_id) REFERENCES caracteristiques (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE caracteristiques CHANGE nom nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE categories CHANGE nom nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE clients CHANGE adresse_mail adresse_mail VARCHAR(255) NOT NULL, CHANGE ville ville VARCHAR(255) NOT NULL, CHANGE telephone telephone VARCHAR(255) NOT NULL, CHANGE code_postale code_postale VARCHAR(5) NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE adresse adresse LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE commentaires CHANGE contenu contenu LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE fournisseur CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE group_produit group_produit VARCHAR(255) DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE type_caracteristiques CHANGE nom nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs CHANGE pseudo pseudo VARCHAR(255) NOT NULL, CHANGE mot_de_passe mot_de_passe VARCHAR(255) NOT NULL, CHANGE role role VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL, CHANGE headers headers LONGTEXT NOT NULL, CHANGE queue_name queue_name VARCHAR(190) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE produit_caracteristiques');
        $this->addSql('ALTER TABLE caracteristiques CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE categories CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE clients CHANGE adresse_mail adresse_mail VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE ville ville VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE code_postale code_postale VARCHAR(5) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE adresse adresse LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commentaires CHANGE contenu contenu LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE fournisseur CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(190) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE produit CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE group_produit group_produit VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE type_caracteristiques CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE utilisateurs CHANGE pseudo pseudo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE mot_de_passe mot_de_passe VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE role role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
