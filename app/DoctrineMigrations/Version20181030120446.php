<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181030120446 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_table (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, delivery_method_id INT DEFAULT NULL, user_name VARCHAR(255) DEFAULT NULL, user_phone VARCHAR(255) DEFAULT NULL, delivery_method_name VARCHAR(255) NOT NULL, delivery_method_cost INT NOT NULL, payment_method VARCHAR(255) DEFAULT NULL, cost INT NOT NULL, note VARCHAR(255) DEFAULT NULL, current_status VARCHAR(255) NOT NULL, cancel_reason VARCHAR(255) DEFAULT NULL, delivery_index VARCHAR(255) DEFAULT NULL, delivery_address LONGTEXT DEFAULT NULL, INDEX IDX_75B7FBBBA76ED395 (user_id), INDEX UNIQ_75B7FBBB5DED75F5 (delivery_method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, order_id INT NOT NULL, product_name VARCHAR(255) NOT NULL, product_code VARCHAR(255) NOT NULL, price INT NOT NULL, quantity INT NOT NULL, INDEX IDX_52EA1F094584665A (product_id), INDEX IDX_52EA1F098D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_status (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B88F75C98D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_table ADD CONSTRAINT FK_75B7FBBBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_table ADD CONSTRAINT FK_75B7FBBB5DED75F5 FOREIGN KEY (delivery_method_id) REFERENCES delivery_method (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES order_table (id)');
        $this->addSql('ALTER TABLE order_status ADD CONSTRAINT FK_B88F75C98D9F6D38 FOREIGN KEY (order_id) REFERENCES order_table (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE order_status DROP FOREIGN KEY FK_B88F75C98D9F6D38');
        $this->addSql('DROP TABLE order_table');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE order_status');
    }
}
