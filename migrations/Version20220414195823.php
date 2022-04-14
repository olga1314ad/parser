<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220414195823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offer_to_notes (offer_id INT NOT NULL, sales_notes_id INT NOT NULL, INDEX IDX_735A27DE53C674EE (offer_id), INDEX IDX_735A27DEB1EA6D72 (sales_notes_id), PRIMARY KEY(offer_id, sales_notes_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer_to_notes ADD CONSTRAINT FK_735A27DE53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_to_notes ADD CONSTRAINT FK_735A27DEB1EA6D72 FOREIGN KEY (sales_notes_id) REFERENCES sales_notes (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE offer_sales_notes');
        $this->addSql('DROP TABLE yandex_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offer_sales_notes (offer_id INT NOT NULL, sales_notes_id INT NOT NULL, INDEX IDX_767DAB3453C674EE (offer_id), INDEX IDX_767DAB34B1EA6D72 (sales_notes_id), PRIMARY KEY(offer_id, sales_notes_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE yandex_id (id INT AUTO_INCREMENT NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer_sales_notes ADD CONSTRAINT FK_767DAB34B1EA6D72 FOREIGN KEY (sales_notes_id) REFERENCES sales_notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_sales_notes ADD CONSTRAINT FK_767DAB3453C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE offer_to_notes');
    }
}
