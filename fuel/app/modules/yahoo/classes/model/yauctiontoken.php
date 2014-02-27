<?php
namespace Yahoo;
class Model_Yauctiontoken extends \Orm\Model
{
    protected static $_table_name = 'yauction_token';

    protected static $_properties = array(
		'id',
		'user_id',
		'yahoo_user_id',
		'open_id',
		'access_token',
		'refresh_token',
		'expiration',
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

    public static function getAccessToken($open_id){

        $result = self::find('first', array(
            'where' => array(
                array('open_id', $open_id),
            ),
        ));

        return $result;
    }


    protected static function getIdFromOpenId($open_id){

        $result = self::find('first', array(
            'where' => array(
                array('open_id', $open_id),
            ),
        ));

        if($result){
            return $result->id;
        }
    }

    public static function setAccessToken($user_id,$open_id,$access_token,$refresh_token,$expiration){

        $id = self::getIdFromOpenId($open_id);

        if($id){
            $updatetoken = self::find($id);
            $updatetoken->set(array(
                'open_id'    => $open_id,
                'access_token' =>$access_token,
                'refresh_token'  => $refresh_token,
                'expiration' => $expiration
            ));

            if($updatetoken->save()){
                return $updatetoken->id;
            }else{
                return false;
            }
        }else{

            $newtoken = new Model_Yauctiontoken();
            $newtoken->user_id = $user_id;
            $newtoken->open_id = $open_id;
            $newtoken->access_token = $access_token;
            $newtoken->refresh_token = $refresh_token;
            $newtoken->expiration = $expiration;

            if($newtoken->save()){
                return $newtoken->id;
            }else{
                return false;
            }
        }

    }

    public static function setRefreshToken($open_id,$access_token,$expiration){

        $id = self::getIdFromOpenId($open_id);

        if($id){
            $updatetoken = self::find($id);
            $updatetoken->set(array(
                'open_id'       =>$open_id,
                'access_token'  => $access_token,
                'expiration' => $expiration
            ));

            if($updatetoken->save()){
                return $updatetoken->id;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    public static function validate($factory)
    {
        $val = \Validation::forge($factory);
        $val->add_field('yahoo_user_id', 'Yahoo User Id', 'max_length[255]');

        return $val;
    }


}
