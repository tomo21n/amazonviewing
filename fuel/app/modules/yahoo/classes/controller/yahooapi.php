<?php
namespace Yahoo;
class Controller_Yahooapi extends \Controller_Rest
{

    public function before(){

        parent::before();

        if (\Input::method() == 'POST') {

            if ($user = \Auth::validate_user(\Input::post('email'), \Input::post('password')))
            {

                if ( \Auth::member(1) )
                {
                    //禁止ユーザ
                    $username = false;
                    $errormsg = 'アカウントがロックされています';
                }else{
                    $username = $user->username;
                }
            }
            else
            {
                $username = false;
                $errormsg = 'ID,パスワードが一致しません';

            }
        }

        if($username){
            return true;

        }else{
            return $errormsg;
        }

    }

    public function post_myCloseList()
    {
        $errormsg = self::before();

        if($errormsg === true){

            $open_id = \Input::post('open_id');

            $yauction = new Yauction();
            $yauction->setRequestUri(\Uri::base().'yahoo/myauction/index');


            if($open_id){
                $env = Model_Yauctiontoken::getAccessToken($open_id);
                if(!$env){

                    return array('ERROR'=>'Open Id を正しく設定して下さい。');

                }else{
                    $yauction->setTokenFromDb($env);
                    $result = $yauction->myCloseList();
                }
                if($result === 'Invalid Token'){
                    $yauction->refreshToken();

                    $env = Model_Yauctiontoken::getAccessToken($open_id);
                    $yauction->setTokenFromDb($env);

                    $result = $yauction->myCloseList();

                }

            }

            if($result !== 'Invalid Request'||$result !== 'Other Error'){

                $yauctionsell = Model_Yauctionsell::find('all',array(
                    'where'=> array(
                        array( 'open_id', '=', $open_id )
                    ),'order_by' => array(
                        array('end_time' ,'asc')
                    ),'limit' => 50
                ));

                $resultarray = array();

                $itemarray = array();
                foreach($yauctionsell as $item){
                    $itemarray += array('auction_id' => $item->auction_id);
                    $itemarray += array('auction_item_url' => $item->auction_item_url);
                    $itemarray += array('title' => $item->title);
                    $itemarray += array('highest_price' => $item->highest_price);
                    $itemarray += array('winner_id' => $item->winner_id);
                    $itemarray += array('winner_contact_url' => $item->winner_contact_url);
                    $itemarray += array('message_title' => $item->message_title);
                    $itemarray += array('end_time' => date('Y/m/d H:i:s', strtotime($item->end_time)));
                    $itemarray += array('winner_url' => 'http://openuser.auctions.yahoo.co.jp/jp/user/'.$item->winner_id);

                    $resultarray[]= $itemarray;

                    $itemarray = array();
                }


                $resultsetarray = array('ResultSet'=> array('ResultCount' => count($yauctionsell),'Result' => $resultarray));

                return $resultsetarray;
            }

        }else{

            return array('ERROR'=>$errormsg);

        }
    }

    public function post_myWonList()
    {

        $errormsg = self::before();


        if($errormsg === true){

            $open_id = \Input::post('open_id');

            $yauction = new Yauction();
            $yauction->setRequestUri(\Uri::base().'yahoo/myauction/index');


            if($open_id){
                $env = Model_Yauctiontoken::getAccessToken($open_id);
                if(!$env){

                    return array('ERROR'=>'Open Id を正しく設定して下さい。');

                }else{
                    $yauction->setTokenFromDb($env);
                    $result = $yauction->myWonList();
                }
                if($result === 'Invalid Token'){
                    $yauction->refreshToken();

                    $env = Model_Yauctiontoken::getAccessToken($open_id);
                    $yauction->setTokenFromDb($env);

                    $result = $yauction->myWonList();

                }
            }

            if($result !== 'Invalid Request'||$result !== 'Other Error'){

                $yauctionwon = Model_Yauctionwon::find('all',array(
                    'where'=> array(
                        array( 'open_id', '=', $open_id )
                    ),'order_by' => array(
                        array('end_time' ,'asc')
                    ),'limit' => 50
                ));

                $resultarray = array();


                $itemarray = array();
                foreach($yauctionwon as $item){
                    $itemarray += array('auction_id' => $item->auction_id);
                    $itemarray += array('auction_item_url' => $item->auction_item_url);
                    $itemarray += array('title' => $item->title);
                    $itemarray += array('won_price' => $item->won_price);
                    $itemarray += array('seller_id' => $item->seller_id);
                    $itemarray += array('seller_contact_url' => $item->seller_contact_url);
                    $itemarray += array('message_title' => $item->message_title);
                    $itemarray += array('end_time' => date('Y/m/d H:i:s', strtotime($item->end_time)));
                    $itemarray += array('seller_url' => 'http://openuser.auctions.yahoo.co.jp/jp/user/'.$item->seller_id);

                    $resultarray[]= $itemarray;

                    $itemarray = array();
                }


                $resultsetarray = array('ResultSet'=> array('ResultCount' => count($yauctionwon),'Result' => $resultarray));

                return $resultsetarray;
            }

        }else{

            return array('ERROR'=>$errormsg);

        }
    }

}