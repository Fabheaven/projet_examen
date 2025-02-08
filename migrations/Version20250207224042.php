<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207224042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, mediafile_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price NUMERIC(10, 2) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', state VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_AC74095A989D9B62 (slug), UNIQUE INDEX UNIQ_AC74095AD6810431 (mediafile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_user (activity_id INT NOT NULL, user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_8E570DDB81C06096 (activity_id), INDEX IDX_8E570DDBA76ED395 (user_id), PRIMARY KEY(activity_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_category (activity_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_A646A9CF81C06096 (activity_id), INDEX IDX_A646A9CF12469DE2 (category_id), PRIMARY KEY(activity_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, cart_id INT NOT NULL, circuit_id INT DEFAULT NULL, activity_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_BA388B71AD5CDBF (cart_id), INDEX IDX_BA388B7CF2182C8 (circuit_id), INDEX IDX_BA388B781C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_item (id INT AUTO_INCREMENT NOT NULL, cart_id INT NOT NULL, item_type VARCHAR(255) NOT NULL, item_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_F0FE25271AD5CDBF (cart_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE circuit (id INT AUTO_INCREMENT NOT NULL, mediafile_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price NUMERIC(10, 2) NOT NULL, duration INT NOT NULL, availability TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', state VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1325F3A6989D9B62 (slug), UNIQUE INDEX UNIQ_1325F3A6D6810431 (mediafile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE circuit_user (circuit_id INT NOT NULL, user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_E46BB0B4CF2182C8 (circuit_id), INDEX IDX_E46BB0B4A76ED395 (user_id), PRIMARY KEY(circuit_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE circuit_category (circuit_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_D611D260CF2182C8 (circuit_id), INDEX IDX_D611D26012469DE2 (category_id), PRIMARY KEY(circuit_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', circuit_id INT DEFAULT NULL, activity_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526CCF2182C8 (circuit_id), INDEX IDX_9474526C81C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, email VARCHAR(150) NOT NULL, subject VARCHAR(100) DEFAULT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_file (id INT AUTO_INCREMENT NOT NULL, image_name VARCHAR(255) NOT NULL, image_size INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletters (id INT AUTO_INCREMENT NOT NULL, categories_id INT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_sent TINYINT(1) NOT NULL, INDEX IDX_8ECF000CA21214B7 (categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack (id INT AUTO_INCREMENT NOT NULL, user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_97DE5E23A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack_circuit (pack_id INT NOT NULL, circuit_id INT NOT NULL, INDEX IDX_286C63DB1919B217 (pack_id), INDEX IDX_286C63DBCF2182C8 (circuit_id), PRIMARY KEY(pack_id, circuit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack_activity (pack_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_82FB4D4B1919B217 (pack_id), INDEX IDX_82FB4D4B81C06096 (activity_id), PRIMARY KEY(pack_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_initial VARCHAR(10) DEFAULT NULL, email VARCHAR(150) NOT NULL, last_name VARCHAR(100) DEFAULT NULL, first_name VARCHAR(100) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(150) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_rgpd TINYINT(1) NOT NULL, validation_token VARCHAR(255) DEFAULT NULL, is_valid TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_categories (users_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_ED98E9FC67B3B43D (users_id), INDEX IDX_ED98E9FCA21214B7 (categories_id), PRIMARY KEY(users_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishlist (id INT AUTO_INCREMENT NOT NULL, user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_9CE12A31A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishlist_activity (wishlist_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_1C3B9DAEFB8E54CD (wishlist_id), INDEX IDX_1C3B9DAE81C06096 (activity_id), PRIMARY KEY(wishlist_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishlist_circuit (wishlist_id INT NOT NULL, circuit_id INT NOT NULL, INDEX IDX_8C2925DCFB8E54CD (wishlist_id), INDEX IDX_8C2925DCCF2182C8 (circuit_id), PRIMARY KEY(wishlist_id, circuit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AD6810431 FOREIGN KEY (mediafile_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE activity_user ADD CONSTRAINT FK_8E570DDB81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_user ADD CONSTRAINT FK_8E570DDBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_category ADD CONSTRAINT FK_A646A9CF81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_category ADD CONSTRAINT FK_A646A9CF12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B71AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7CF2182C8 FOREIGN KEY (circuit_id) REFERENCES circuit (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B781C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE circuit ADD CONSTRAINT FK_1325F3A6D6810431 FOREIGN KEY (mediafile_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE circuit_user ADD CONSTRAINT FK_E46BB0B4CF2182C8 FOREIGN KEY (circuit_id) REFERENCES circuit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE circuit_user ADD CONSTRAINT FK_E46BB0B4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE circuit_category ADD CONSTRAINT FK_D611D260CF2182C8 FOREIGN KEY (circuit_id) REFERENCES circuit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE circuit_category ADD CONSTRAINT FK_D611D26012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CCF2182C8 FOREIGN KEY (circuit_id) REFERENCES circuit (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE newsletters ADD CONSTRAINT FK_8ECF000CA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE pack ADD CONSTRAINT FK_97DE5E23A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pack_circuit ADD CONSTRAINT FK_286C63DB1919B217 FOREIGN KEY (pack_id) REFERENCES pack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_circuit ADD CONSTRAINT FK_286C63DBCF2182C8 FOREIGN KEY (circuit_id) REFERENCES circuit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_activity ADD CONSTRAINT FK_82FB4D4B1919B217 FOREIGN KEY (pack_id) REFERENCES pack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_activity ADD CONSTRAINT FK_82FB4D4B81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_categories ADD CONSTRAINT FK_ED98E9FC67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_categories ADD CONSTRAINT FK_ED98E9FCA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT FK_9CE12A31A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE wishlist_activity ADD CONSTRAINT FK_1C3B9DAEFB8E54CD FOREIGN KEY (wishlist_id) REFERENCES wishlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishlist_activity ADD CONSTRAINT FK_1C3B9DAE81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishlist_circuit ADD CONSTRAINT FK_8C2925DCFB8E54CD FOREIGN KEY (wishlist_id) REFERENCES wishlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishlist_circuit ADD CONSTRAINT FK_8C2925DCCF2182C8 FOREIGN KEY (circuit_id) REFERENCES circuit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AD6810431');
        $this->addSql('ALTER TABLE activity_user DROP FOREIGN KEY FK_8E570DDB81C06096');
        $this->addSql('ALTER TABLE activity_user DROP FOREIGN KEY FK_8E570DDBA76ED395');
        $this->addSql('ALTER TABLE activity_category DROP FOREIGN KEY FK_A646A9CF81C06096');
        $this->addSql('ALTER TABLE activity_category DROP FOREIGN KEY FK_A646A9CF12469DE2');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B71AD5CDBF');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7CF2182C8');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B781C06096');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD5CDBF');
        $this->addSql('ALTER TABLE circuit DROP FOREIGN KEY FK_1325F3A6D6810431');
        $this->addSql('ALTER TABLE circuit_user DROP FOREIGN KEY FK_E46BB0B4CF2182C8');
        $this->addSql('ALTER TABLE circuit_user DROP FOREIGN KEY FK_E46BB0B4A76ED395');
        $this->addSql('ALTER TABLE circuit_category DROP FOREIGN KEY FK_D611D260CF2182C8');
        $this->addSql('ALTER TABLE circuit_category DROP FOREIGN KEY FK_D611D26012469DE2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CCF2182C8');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C81C06096');
        $this->addSql('ALTER TABLE newsletters DROP FOREIGN KEY FK_8ECF000CA21214B7');
        $this->addSql('ALTER TABLE pack DROP FOREIGN KEY FK_97DE5E23A76ED395');
        $this->addSql('ALTER TABLE pack_circuit DROP FOREIGN KEY FK_286C63DB1919B217');
        $this->addSql('ALTER TABLE pack_circuit DROP FOREIGN KEY FK_286C63DBCF2182C8');
        $this->addSql('ALTER TABLE pack_activity DROP FOREIGN KEY FK_82FB4D4B1919B217');
        $this->addSql('ALTER TABLE pack_activity DROP FOREIGN KEY FK_82FB4D4B81C06096');
        $this->addSql('ALTER TABLE users_categories DROP FOREIGN KEY FK_ED98E9FC67B3B43D');
        $this->addSql('ALTER TABLE users_categories DROP FOREIGN KEY FK_ED98E9FCA21214B7');
        $this->addSql('ALTER TABLE wishlist DROP FOREIGN KEY FK_9CE12A31A76ED395');
        $this->addSql('ALTER TABLE wishlist_activity DROP FOREIGN KEY FK_1C3B9DAEFB8E54CD');
        $this->addSql('ALTER TABLE wishlist_activity DROP FOREIGN KEY FK_1C3B9DAE81C06096');
        $this->addSql('ALTER TABLE wishlist_circuit DROP FOREIGN KEY FK_8C2925DCFB8E54CD');
        $this->addSql('ALTER TABLE wishlist_circuit DROP FOREIGN KEY FK_8C2925DCCF2182C8');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE activity_user');
        $this->addSql('DROP TABLE activity_category');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE circuit');
        $this->addSql('DROP TABLE circuit_user');
        $this->addSql('DROP TABLE circuit_category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE media_file');
        $this->addSql('DROP TABLE newsletters');
        $this->addSql('DROP TABLE pack');
        $this->addSql('DROP TABLE pack_circuit');
        $this->addSql('DROP TABLE pack_activity');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_categories');
        $this->addSql('DROP TABLE wishlist');
        $this->addSql('DROP TABLE wishlist_activity');
        $this->addSql('DROP TABLE wishlist_circuit');
    }
}
