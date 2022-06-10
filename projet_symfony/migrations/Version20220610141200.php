<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610141200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, line VARCHAR(255) NOT NULL, cp VARCHAR(5) NOT NULL, city VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_880E0D76F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caracteristiques (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_sous_cat INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, customers_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6EEAA67DC3568B40 (customers_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_content (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_87E420982EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, text LONGTEXT NOT NULL, note INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526C4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, panier_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_62534E21F77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customers_address (customers_id INT NOT NULL, address_id INT NOT NULL, INDEX IDX_3106BC64C3568B40 (customers_id), INDEX IDX_3106BC64F5B7AF75 (address_id), PRIMARY KEY(customers_id, address_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, adresse_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_369ECA324DE7DC5C (adresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_content (id INT AUTO_INCREMENT NOT NULL, panier_id INT NOT NULL, quantite INT NOT NULL, INDEX IDX_47FF428F77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, fournisseur_id INT NOT NULL, name VARCHAR(255) NOT NULL, decription LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, group_product VARCHAR(255) NOT NULL, stock INT NOT NULL, img VARCHAR(255) DEFAULT NULL, INDEX IDX_D34A04AD670C757F (fournisseur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_caracteristiques (product_id INT NOT NULL, caracteristiques_id INT NOT NULL, INDEX IDX_EF39BEE14584665A (product_id), INDEX IDX_EF39BEE1B2639FE4 (caracteristiques_id), PRIMARY KEY(product_id, caracteristiques_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_panier_content (product_id INT NOT NULL, panier_content_id INT NOT NULL, INDEX IDX_57168A044584665A (product_id), INDEX IDX_57168A04E0759A78 (panier_content_id), PRIMARY KEY(product_id, panier_content_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_commande_content (product_id INT NOT NULL, commande_content_id INT NOT NULL, INDEX IDX_86F226F54584665A (product_id), INDEX IDX_86F226F51C628F0B (commande_content_id), PRIMARY KEY(product_id, commande_content_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (product_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_CDFC73564584665A (product_id), INDEX IDX_CDFC735612469DE2 (category_id), PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DC3568B40 FOREIGN KEY (customers_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE commande_content ADD CONSTRAINT FK_87E420982EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E21F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE customers_address ADD CONSTRAINT FK_3106BC64C3568B40 FOREIGN KEY (customers_id) REFERENCES customers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customers_address ADD CONSTRAINT FK_3106BC64F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fournisseur ADD CONSTRAINT FK_369ECA324DE7DC5C FOREIGN KEY (adresse_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE panier_content ADD CONSTRAINT FK_47FF428F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE product_caracteristiques ADD CONSTRAINT FK_EF39BEE14584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_caracteristiques ADD CONSTRAINT FK_EF39BEE1B2639FE4 FOREIGN KEY (caracteristiques_id) REFERENCES caracteristiques (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_panier_content ADD CONSTRAINT FK_57168A044584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_panier_content ADD CONSTRAINT FK_57168A04E0759A78 FOREIGN KEY (panier_content_id) REFERENCES panier_content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_commande_content ADD CONSTRAINT FK_86F226F54584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_commande_content ADD CONSTRAINT FK_86F226F51C628F0B FOREIGN KEY (commande_content_id) REFERENCES commande_content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customers_address DROP FOREIGN KEY FK_3106BC64F5B7AF75');
        $this->addSql('ALTER TABLE fournisseur DROP FOREIGN KEY FK_369ECA324DE7DC5C');
        $this->addSql('ALTER TABLE product_caracteristiques DROP FOREIGN KEY FK_EF39BEE1B2639FE4');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735612469DE2');
        $this->addSql('ALTER TABLE commande_content DROP FOREIGN KEY FK_87E420982EA2E54');
        $this->addSql('ALTER TABLE product_commande_content DROP FOREIGN KEY FK_86F226F51C628F0B');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DC3568B40');
        $this->addSql('ALTER TABLE customers_address DROP FOREIGN KEY FK_3106BC64C3568B40');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD670C757F');
        $this->addSql('ALTER TABLE customers DROP FOREIGN KEY FK_62534E21F77D927C');
        $this->addSql('ALTER TABLE panier_content DROP FOREIGN KEY FK_47FF428F77D927C');
        $this->addSql('ALTER TABLE product_panier_content DROP FOREIGN KEY FK_57168A04E0759A78');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4584665A');
        $this->addSql('ALTER TABLE product_caracteristiques DROP FOREIGN KEY FK_EF39BEE14584665A');
        $this->addSql('ALTER TABLE product_panier_content DROP FOREIGN KEY FK_57168A044584665A');
        $this->addSql('ALTER TABLE product_commande_content DROP FOREIGN KEY FK_86F226F54584665A');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73564584665A');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE caracteristiques');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_content');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE customers_address');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_content');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_caracteristiques');
        $this->addSql('DROP TABLE product_panier_content');
        $this->addSql('DROP TABLE product_commande_content');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
