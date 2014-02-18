<?php

namespace Fuel\Migrations;

class Create_inventories
{
	public function up()
	{
		\DBUtil::create_table('inventories', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id' => array('constraint' => 20, 'type' => 'varchar'),
			'part_id' => array('constraint' => 20, 'type' => 'varchar'),
			'sku' => array('constraint' => 20, 'type' => 'varchar'),
			'sale_count' => array('constraint' => 11, 'type' => 'int'),
			'sale_price' => array('type' => 'float'),
			'condition' => array('constraint' => 255, 'type' => 'varchar'),
			'channel' => array('constraint' => 255, 'type' => 'varchar'),
			'comment' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('inventories');
	}
}