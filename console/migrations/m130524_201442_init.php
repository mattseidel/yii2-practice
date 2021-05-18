<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'user_type' => $this->integer()->defaultValue(1),
            'phone' => $this->string(10)->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('item', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'photo' => $this->string()->unique(),
            'price' => $this->integer()->notNull(),
            'description' => $this->text(),
            'available' => $this->boolean(),
        ], $tableOptions);

        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'client' => $this->integer()->notNull(),
            'seller' => $this->integer(),
            'deliver' => $this->integer(),
            'address' => $this->string(250),
            'total' => $this->integer(),
            'status' => $this->tinyInteger()->defaultValue(1),
            'date' => $this->timestamp()->defaultValue(date('Y-m-d H:i:s')),
        ], $tableOptions);

        $this->createTable('order_item', [
            'item' => $this->integer(),
            'order' => $this->integer(),
            'quanty' => $this->integer(),
            'price' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_client_order',
            'order',
            'client',
            'user',
            'id'
        );
        $this->addForeignKey(
            'fk_order_seller',
            'order',
            'seller',
            'user',
            'id'
        );
        $this->addForeignKey(
            'fk_order_delivery',
            'order',
            'deliver',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk_order_item',
            'order_item',
            'order',
            'order',
            'id'
        );

        $this->addForeignKey(
            'fk_item_order',
            'order_item',
            'item',
            'item',
            'id'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk_client_order', 'order');
        $this->dropForeignKey('fk_order_seller', 'order');
        $this->dropForeignKey('fk_order_delivery', 'order');
        $this->dropForeignKey('fk_order_item', 'order_item');
        $this->dropForeignKey('fk_item_order', 'order_item');
        $this->dropTable('user');
        $this->dropTable('item');
        $this->dropTable('order');
        $this->dropTable('order_item');
    }
}
