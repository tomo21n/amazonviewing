<?php
class Model_Brand extends \Orm\Model
{

    protected static $_table_name = 'brand';

    protected static $_primary_key = array('merchant_id');

	protected static $_properties = array(
        'merchant_id',
        'alphabet',
        'brandurl',
        'check_date',
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
		//$val->add_field('name', 'Name', 'required|max_length[255]');

		return $val;
	}


}
