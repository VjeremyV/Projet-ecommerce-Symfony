<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220614114513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contenu DROP FOREIGN KEY FK_89C2003FF347EFB');
        $this->addSql('DROP INDEX UNIQ_89C2003FF347EFB ON contenu');
        $this->addSql('ALTER TABLE contenu CHANGE produit_id produits_id INT NOT NULL');
        $this->addSql('ALTER TABLE contenu ADD CONSTRAINT FK_89C2003FCD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_89C2003FCD11A2CF ON contenu (produits_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contenu DROP FOREIGN KEY FK_89C2003FCD11A2CF');
        $this->addSql('DROP INDEX IDX_89C2003FCD11A2CF ON contenu');
        $this->addSql('ALTER TABLE contenu CHANGE produits_id produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE contenu ADD CONSTRAINT FK_89C2003FF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_89C2003FF347EFB ON contenu (produit_id)');
    }
}
