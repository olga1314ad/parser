<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220421143417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT NOT NULL, parent_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_64C19C1727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, rate INT NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_options (id INT AUTO_INCREMENT NOT NULL, cost INT NOT NULL, days VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, currency_id INT NOT NULL, delivery_option_id INT NOT NULL, category_id INT NOT NULL, vendor_id INT NOT NULL, shop_id INT NOT NULL, url VARCHAR(150) NOT NULL, price INT NOT NULL, store TINYINT(1) NOT NULL, pickup TINYINT(1) NOT NULL, delivery TINYINT(1) NOT NULL, model VARCHAR(255) NOT NULL, type_prefix VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, vendor_code VARCHAR(100) NOT NULL, barcode VARCHAR(20) NOT NULL, INDEX IDX_29D6873E38248176 (currency_id), INDEX IDX_29D6873EE3A151FD (delivery_option_id), INDEX IDX_29D6873E12469DE2 (category_id), INDEX IDX_29D6873EF603EE73 (vendor_id), INDEX IDX_29D6873E4D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_to_notes (offer_id INT NOT NULL, sales_notes_id INT NOT NULL, INDEX IDX_735A27DE53C674EE (offer_id), INDEX IDX_735A27DEB1EA6D72 (sales_notes_id), PRIMARY KEY(offer_id, sales_notes_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE params (id INT AUTO_INCREMENT NOT NULL, offer_id INT NOT NULL, name VARCHAR(150) NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_8FCE0EF353C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, offer_id INT NOT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_16DB4F8953C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sales_notes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, company VARCHAR(100) NOT NULL, url VARCHAR(120) NOT NULL, updated_at VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vendor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EE3A151FD FOREIGN KEY (delivery_option_id) REFERENCES delivery_options (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EF603EE73 FOREIGN KEY (vendor_id) REFERENCES vendor (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE offer_to_notes ADD CONSTRAINT FK_735A27DE53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_to_notes ADD CONSTRAINT FK_735A27DEB1EA6D72 FOREIGN KEY (sales_notes_id) REFERENCES sales_notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE params ADD CONSTRAINT FK_8FCE0EF353C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8953C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E12469DE2');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E38248176');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EE3A151FD');
        $this->addSql('ALTER TABLE offer_to_notes DROP FOREIGN KEY FK_735A27DE53C674EE');
        $this->addSql('ALTER TABLE params DROP FOREIGN KEY FK_8FCE0EF353C674EE');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F8953C674EE');
        $this->addSql('ALTER TABLE offer_to_notes DROP FOREIGN KEY FK_735A27DEB1EA6D72');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E4D16C4DD');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EF603EE73');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE delivery_options');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE offer_to_notes');
        $this->addSql('DROP TABLE params');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE sales_notes');
        $this->addSql('DROP TABLE shop');
        $this->addSql('DROP TABLE vendor');
    }
}
