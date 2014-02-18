<?php

namespace Fuel\Migrations;

class Create_yauctionsell
{
	public function up()
	{
		\DBUtil::create_table('yauction_sell', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id' => array('constraint' => 20, 'type' => 'varchar'),
			'auction_id' => array('constraint' => 20, 'type' => 'varchar'),
			'title' => array('constraint' => 2000, 'type' => 'varchar'),
			'highest_price' => array('constraint' => 11, 'type' => 'int'),
			'winner_id' => array('constraint' => 100, 'type' => 'varchar'),
			'item_list_url' => array('constraint' => 2000, 'type' => 'varchar'),
			'message_title' => array('constraint' => 2000, 'type' => 'varchar'),
			'end_time' => array('type' => 'timestamp'),
			'auction_item_url' => array('constraint' => 2000, 'type' => 'varchar'),
			'image_url' => array('constraint' => 2000, 'type' => 'varchar'),
			'created_at' => array('type' => 'timestamp'),
			'updated_at' => array('type' => 'timestamp'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('yauction_sell');
	}
}