<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181030124251 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES order_table (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_status DROP FOREIGN KEY FK_B88F75C98D9F6D38');
        $this->addSql('ALTER TABLE order_status ADD CONSTRAINT FK_B88F75C98D9F6D38 FOREIGN KEY (order_id) REFERENCES order_table (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES order_table (id)');
        $this->addSql('ALTER TABLE order_status DROP FOREIGN KEY FK_B88F75C98D9F6D38');
        $this->addSql('ALTER TABLE order_status ADD CONSTRAINT FK_B88F75C98D9F6D38 FOREIGN KEY (order_id) REFERENCES order_table (id)');
    }
}
