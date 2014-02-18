<?php
class Model_Offerprice extends \Orm\Model
{
    protected static $_table_name = 'offerprices';

	protected static $_properties = array(
		'id',
        'part_id',
        'asin',
		'survey_dtm',
		'country',
		'new_1st_price',
		'new_2nd_price',
		'new_3rd_price',
		'used_1st_price',
		'used_2nd_price',
		'used_3rd_price',
		'new_1st_qty',
		'new_2nd_qty',
		'new_3rd_qty',
		'used_1st_qty',
		'used_2nd_qty',
		'used_3rd_qty',
		'new_1st_shipfee',
		'new_2nd_shipfee',
		'new_3rd_shipfee',
		'used_1st_shipfee',
		'used_2nd_shipfee',
		'used_3rd_shipfee',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => true,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => true,
		),
	);

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('part_id', 'Part Id', 'required|max_length[20]');
		$val->add_field('survey_dtm', 'Survey Dtm', 'required');
		$val->add_field('country', 'Country', 'required|max_length[255]');
		$val->add_field('new_1st_price', 'New 1st Price', 'required');
		$val->add_field('new_2nd_price', 'New 2nd Price', 'required');
		$val->add_field('new_3rd_price', 'New 3rd Price', 'required');
		$val->add_field('used_1st_price', 'Used 1st Price', 'required');
		$val->add_field('used_2nd_price', 'Used 2nd Price', 'required');
		$val->add_field('used_3rd_price', 'Used 3rd Price', 'required');
		$val->add_field('new_1st_qty', 'New 1st Qty', 'required|valid_string[numeric]');
		$val->add_field('new_2nd_qty', 'New 2nd Qty', 'required|valid_string[numeric]');
		$val->add_field('new_3rd_qty', 'New 3rd Qty', 'required|valid_string[numeric]');
		$val->add_field('used_1st_qty', 'Used 1st Qty', 'required|valid_string[numeric]');
		$val->add_field('used_2nd_qty', 'Used 2nd Qty', 'required|valid_string[numeric]');
		$val->add_field('used_3rd_qty', 'Used 3rd Qty', 'required|valid_string[numeric]');
		$val->add_field('new_1st_shipfee', 'New 1st Shipfee', 'required');
		$val->add_field('new_2nd_shipfee', 'New 2nd Shipfee', 'required');
		$val->add_field('new_3rd_shipfee', 'New 3rd Shipfee', 'required');
		$val->add_field('used_1st_shipfee', 'Used 1st Shipfee', 'required');
		$val->add_field('used_2nd_shipfee', 'Used 2nd Shipfee', 'required');
		$val->add_field('used_3rd_shipfee', 'Used 3rd Shipfee', 'required');

		return $val;
	}

}
