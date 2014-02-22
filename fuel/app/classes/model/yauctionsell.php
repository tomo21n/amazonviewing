<?php
class Model_Yauctionsell extends \Orm\Model
{
    protected static $_table_name = 'yauction_sell';

    protected static $_properties = array(
		'id',
		'user_id',
		'open_id',
		'auction_id',
		'title',
		'highest_price',
		'winner_id',
		'winner_contact_url',
		'message_title',
		'end_time',
		'auction_item_url',
		'image_url',
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
        $val->add_field('user_id', 'User Id', 'required|max_length[20]');
        $val->add_field('auction_id', 'Auction Id', 'required|max_length[20]');

        return $val;
    }

    /**********************************
     * リレーション：一対多
     */
    protected static $_has_one = array(
        'yauctiontoken' => array(
            'model_to' => 'Model_Yauctiontoken',
            'key_from' => 'open_id',
            'key_to' => 'open_id',
            'cascade_save' => false,
            'cascade_delete' => false
        ),
    );
}
