<?php

namespace Fuel\Migrations;

class Create_salesparts
{
	public function up()
	{
		\DBUtil::create_table('salesparts', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'part_id' => array('constraint' => 20, 'type' => 'varchar'),
			'asin' => array('constraint' => 10, 'type' => 'varchar'),
			'ean' => array('constraint' => 10, 'type' => 'varchar'),
			'title' => array('constraint' => 255, 'type' => 'varchar'),
			'category' => array('constraint' => 255, 'type' => 'varchar'),
			'url' => array('type' => 'text'),
			'image_s' => array('type' => 'text'),
			'image_l' => array('type' => 'text'),
			'volume' => array('constraint' => 255, 'type' => 'varchar'),
			'wight' => array('constraint' => 255, 'type' => 'varchar'),
			'release_date' => array('type' => 'date'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('salesparts');
	}
}