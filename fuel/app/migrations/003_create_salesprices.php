<?php

namespace Fuel\Migrations;

class Create_salesprices
{
	public function up()
	{
		\DBUtil::create_table('salesprices', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'part_id' => array('constraint' => 20, 'type' => 'varchar'),
			'survey_dtm' => array('type' => 'timestamp'),
			'country' => array('constraint' => 255, 'type' => 'varchar'),
			'new_1st_price' => array('type' => 'float'),
			'new_2nd_price' => array('type' => 'float'),
			'new_3rd_price' => array('type' => 'float'),
			'used_1st_price' => array('type' => 'float'),
			'used_2nd_price' => array('type' => 'float'),
			'used_3rd_price' => array('type' => 'float'),
			'new_1st_qty' => array('constraint' => 11, 'type' => 'int'),
			'new_2nd_qty' => array('constraint' => 11, 'type' => 'int'),
			'new_3rd_qty' => array('constraint' => 11, 'type' => 'int'),
			'used_1st_qty' => array('constraint' => 11, 'type' => 'int'),
			'used_2nd_qty' => array('constraint' => 11, 'type' => 'int'),
			'used_3rd_qty' => array('constraint' => 11, 'type' => 'int'),
			'new_1st_shipfee' => array('type' => 'float'),
			'new_2nd_shipfee' => array('type' => 'float'),
			'new_3rd_shipfee' => array('type' => 'float'),
			'used_1st_shipfee' => array('type' => 'float'),
			'used_2nd_shipfee' => array('type' => 'float'),
			'used_3rd_shipfee' => array('type' => 'float'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('salesprices');
	}
}