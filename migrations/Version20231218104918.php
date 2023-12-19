<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218104918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart ADD orders_id INT DEFAULT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B7CFFE9AD6 ON cart (orders_id)');
        $this->addSql('CREATE INDEX IDX_BA388B7A76ED395 ON cart (user_id)');
        $this->addSql('ALTER TABLE cart_line ADD product_id INT NOT NULL, ADD cart_id INT NOT NULL');
        $this->addSql('ALTER TABLE cart_line ADD CONSTRAINT FK_3EF1B4CF4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE cart_line ADD CONSTRAINT FK_3EF1B4CF1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('CREATE INDEX IDX_3EF1B4CF4584665A ON cart_line (product_id)');
        $this->addSql('CREATE INDEX IDX_3EF1B4CF1AD5CDBF ON cart_line (cart_id)');
        $this->addSql('ALTER TABLE order_line ADD orders_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE1CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('CREATE INDEX IDX_9CE58EE1CFFE9AD6 ON order_line (orders_id)');
        $this->addSql('ALTER TABLE orders ADD users_id INT NOT NULL, ADD address_shipped_id INT NOT NULL, ADD billing_address_id INT NOT NULL, ADD order_state_id INT NOT NULL, ADD payment_id INT NOT NULL, ADD shipping_id INT NOT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE35AA1FFE FOREIGN KEY (address_shipped_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE79D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEE420DE70 FOREIGN KEY (order_state_id) REFERENCES order_state (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE4887F3F8 FOREIGN KEY (shipping_id) REFERENCES shipping (id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE67B3B43D ON orders (users_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE35AA1FFE ON orders (address_shipped_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE79D0C0E4 ON orders (billing_address_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEEE420DE70 ON orders (order_state_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE4C3A3BB ON orders (payment_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE4887F3F8 ON orders (shipping_id)');
        $this->addSql('ALTER TABLE product ADD product_taxes_id INT NOT NULL, ADD product_sales_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFD600904 FOREIGN KEY (product_taxes_id) REFERENCES taxes (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD6FE24090 FOREIGN KEY (product_sales_id) REFERENCES sales (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADFD600904 ON product (product_taxes_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD6FE24090 ON product (product_sales_id)');
        $this->addSql('ALTER TABLE users ADD address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9F5B7AF75 ON users (address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7CFFE9AD6');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('DROP INDEX UNIQ_BA388B7CFFE9AD6 ON cart');
        $this->addSql('DROP INDEX IDX_BA388B7A76ED395 ON cart');
        $this->addSql('ALTER TABLE cart DROP orders_id, DROP user_id');
        $this->addSql('ALTER TABLE cart_line DROP FOREIGN KEY FK_3EF1B4CF4584665A');
        $this->addSql('ALTER TABLE cart_line DROP FOREIGN KEY FK_3EF1B4CF1AD5CDBF');
        $this->addSql('DROP INDEX IDX_3EF1B4CF4584665A ON cart_line');
        $this->addSql('DROP INDEX IDX_3EF1B4CF1AD5CDBF ON cart_line');
        $this->addSql('ALTER TABLE cart_line DROP product_id, DROP cart_id');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE1CFFE9AD6');
        $this->addSql('DROP INDEX IDX_9CE58EE1CFFE9AD6 ON order_line');
        $this->addSql('ALTER TABLE order_line DROP orders_id');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE67B3B43D');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE35AA1FFE');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE79D0C0E4');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEE420DE70');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE4C3A3BB');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE4887F3F8');
        $this->addSql('DROP INDEX IDX_E52FFDEE67B3B43D ON orders');
        $this->addSql('DROP INDEX IDX_E52FFDEE35AA1FFE ON orders');
        $this->addSql('DROP INDEX IDX_E52FFDEE79D0C0E4 ON orders');
        $this->addSql('DROP INDEX IDX_E52FFDEEE420DE70 ON orders');
        $this->addSql('DROP INDEX IDX_E52FFDEE4C3A3BB ON orders');
        $this->addSql('DROP INDEX IDX_E52FFDEE4887F3F8 ON orders');
        $this->addSql('ALTER TABLE orders DROP users_id, DROP address_shipped_id, DROP billing_address_id, DROP order_state_id, DROP payment_id, DROP shipping_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFD600904');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD6FE24090');
        $this->addSql('DROP INDEX IDX_D34A04ADFD600904 ON product');
        $this->addSql('DROP INDEX IDX_D34A04AD6FE24090 ON product');
        $this->addSql('ALTER TABLE product DROP product_taxes_id, DROP product_sales_id');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9F5B7AF75');
        $this->addSql('DROP INDEX IDX_1483A5E9F5B7AF75 ON users');
        $this->addSql('ALTER TABLE users DROP address_id');
    }
}
