<?php
namespace Amazon;
class Model_Salespart extends \Orm\Model
{
	protected static $_properties = array(
		'part_id',
		'asin',
		'ean',
		'title',
		'category',
		'url',
		'image_s',
		'image_l',
		'volume',
		'wight',
		'release_date',
		'created_at',
		'updated_at',
	);

    protected static $_primary_key = array('part_id');

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
		$val->add_field('part_id', 'Part Id', 'max_length[20]');
		$val->add_field('asin', 'Asin', 'required|max_length[10]');
		$val->add_field('ean', 'Ean', 'max_length[10]');
		$val->add_field('title', 'Title', 'max_length[255]');
		$val->add_field('category', 'Category', 'max_length[255]');
		$val->add_field('volume', 'Volume', 'max_length[255]');
		$val->add_field('wight', 'Wight', 'max_length[255]');

		return $val;
	}


}
