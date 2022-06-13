<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613101612 extends AbstractMigration
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
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2770659567');
        $this->addSql('DROP INDEX IDX_29A5EC2770659567 ON produit');
        $this->addSql('ALTER TABLE produit DROP type_caracteristique_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE produit_caracteristiques');
        $this->addSql('ALTER TABLE produit ADD type_caracteristique_id INT NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2770659567 FOREIGN KEY (type_caracteristique_id) REFERENCES type_caracteristiques (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC2770659567 ON produit (type_caracteristique_id)');
    }
}
