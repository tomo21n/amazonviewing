<?php
class Model_Inventory extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'user_id',
		'part_id',
		'sku',
		'sale_qty',
		'sale_price',
		'condition',
		'channel',
		'comment',
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
		$val->add_field('user_id', 'User Id', 'max_length[20]');
		$val->add_field('part_id', 'Part Id', 'max_length[20]');
		$val->add_field('sku', 'Sku', 'max_length[20]');
		$val->add_field('sale_qty', 'Sale Qty', 'valid_string[numeric]');
		$val->add_field('condition', 'Condition', 'max_length[255]');
		$val->add_field('channel', 'Channel', 'max_length[255]');

		return $val;
	}

    /**********************************
     * リレーション：一対多
     */
    protected static $_has_one = array(
        'salespart' => array(
            'model_to' => 'Model_Salespart',
            'key_from' => 'part_id',
            'key_to' => 'part_id',
            'cascade_save' => false,
            'cascade_delete' => false
        ),
        'offerprice' => array(
            'model_to' => 'Model_Offerprice',
            'key_from' => 'part_id',
            'key_to' => 'part_id',
            'cascade_save' => false,
            'cascade_delete' => false
        )
    );

}
