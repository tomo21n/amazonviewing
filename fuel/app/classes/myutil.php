<?php
/**
 * Utility Class
 */

class MyUtil {
    /*
     *  通貨レート取得ユーティリティ
     *
     * @params $countory  string 国（JP or US）
     * @return string レート
     */
    public static function get_rate($country)
    {
        $currency = null;

        switch($country){
            case 'JP':
                return 1;
                break;
            case 'US':
                $currency = 'USD';
                break;
            default:
                return 1;
                break;
        }
        $data = file_get_contents('http://api.aoikujira.com/kawase/json/'.$currency);
        $json = json_decode($data, true);
        $yen = $json['JPY'];

        return round($yen * 0.01,3);
    }

    public static function get_nickname($user_id){
        $result = DB::select('value')->from('users_metadata')->where('parent_id',$user_id)->and_where('key','nickname')->execute()->current();

        if($result['value']){
            return $result['value'];
        }
    }

    public static function lb_channel(){
        return array('JPAmazon'=>'JP Amazon'
                    ,'USAmazon'=> 'US Amazon'
                    ,'ebay'=>'ebay'
                    ,'YahooAuction'=>'Yahoo Auction');
    }

    public static function lb_condition(){
        return array('New'=>'新品'
        ,'Used'=> '中古');
    }

    public static function get_myuserid(){
        $user_info = Auth::get_user_id();

        return $user_info[1];
    }

}