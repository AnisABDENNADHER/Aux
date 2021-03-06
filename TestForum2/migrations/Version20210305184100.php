<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210305184100 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread (id INT AUTO_INCREMENT NOT NULL, picture VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, published_at DATETIME DEFAULT NULL, is_published TINYINT(1) DEFAULT \'0\' NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_31204C83989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread_category (thread_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_9FD5A1DE2904019 (thread_id), INDEX IDX_9FD5A1D12469DE2 (category_id), PRIMARY KEY(thread_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE thread_category ADD CONSTRAINT FK_9FD5A1DE2904019 FOREIGN KEY (thread_id) REFERENCES thread (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thread_category ADD CONSTRAINT FK_9FD5A1D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE thread_category DROP FOREIGN KEY FK_9FD5A1D12469DE2');
        $this->addSql('ALTER TABLE thread_category DROP FOREIGN KEY FK_9FD5A1DE2904019');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE thread');
        $this->addSql('DROP TABLE thread_category');
    }
}
