<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610180403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE caracteristiques (id INT AUTO_INCREMENT NOT NULL, type_caracteristiques_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_61B5DA1D34D72C4B (type_caracteristiques_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories_type_caracteristiques (categories_id INT NOT NULL, type_caracteristiques_id INT NOT NULL, INDEX IDX_DA238186A21214B7 (categories_id), INDEX IDX_DA23818634D72C4B (type_caracteristiques_id), PRIMARY KEY(categories_id, type_caracteristiques_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, adresse_mail VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, code_postale VARCHAR(5) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, utilisateurs_id INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_panier TINYINT(1) NOT NULL, INDEX IDX_35D4282C1E969C5 (utilisateurs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, contenu LONGTEXT NOT NULL, note INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D9BEC0C4F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contenu (id INT AUTO_INCREMENT NOT NULL, commandes_id INT NOT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_89C2003F8BF5C2E6 (commandes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, contenu_id INT DEFAULT NULL, fournisseur_id INT NOT NULL, categorie_id INT NOT NULL, type_caracteristique_id INT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, group_produit VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, stock INT NOT NULL, image VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_29A5EC273C1CC488 (contenu_id), INDEX IDX_29A5EC27670C757F (fournisseur_id), INDEX IDX_29A5EC27BCF5E72D (categorie_id), INDEX IDX_29A5EC2770659567 (type_caracteristique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_caracteristiques (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, pseudo VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_497B315E19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE caracteristiques ADD CONSTRAINT FK_61B5DA1D34D72C4B FOREIGN KEY (type_caracteristiques_id) REFERENCES type_caracteristiques (id)');
        $this->addSql('ALTER TABLE categories_type_caracteristiques ADD CONSTRAINT FK_DA238186A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_type_caracteristiques ADD CONSTRAINT FK_DA23818634D72C4B FOREIGN KEY (type_caracteristiques_id) REFERENCES type_caracteristiques (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE contenu ADD CONSTRAINT FK_89C2003F8BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC273C1CC488 FOREIGN KEY (contenu_id) REFERENCES contenu (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2770659567 FOREIGN KEY (type_caracteristique_id) REFERENCES type_caracteristiques (id)');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315E19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_type_caracteristiques DROP FOREIGN KEY FK_DA238186A21214B7');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_497B315E19EB6921');
        $this->addSql('ALTER TABLE contenu DROP FOREIGN KEY FK_89C2003F8BF5C2E6');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC273C1CC488');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27670C757F');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4F347EFB');
        $this->addSql('ALTER TABLE caracteristiques DROP FOREIGN KEY FK_61B5DA1D34D72C4B');
        $this->addSql('ALTER TABLE categories_type_caracteristiques DROP FOREIGN KEY FK_DA23818634D72C4B');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2770659567');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C1E969C5');
        $this->addSql('DROP TABLE caracteristiques');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE categories_type_caracteristiques');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE contenu');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE type_caracteristiques');
        $this->addSql('DROP TABLE utilisateurs');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
