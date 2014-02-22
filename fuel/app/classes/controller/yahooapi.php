<?php
class Controller_Yahooapi extends Controller_Rest
{

    public function get_myCloseList()
    {

        $errormsg = null;

        if (Input::method() == 'POST') {

            if ($user = Auth::validate_user(Input::post('email'), Input::post('password')))
            {

                if ( Auth::member(1) )
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
            $open_id = Input::post('open_id');

            if($username){
                $yauction = new Yauction();
                $yauction->setRequestUri(Uri::base().'user/myauction/index');


                if($open_id){
                    $env = Model_Yauctiontoken::getAccessToken($open_id);
                    $yauction->setTokenFromDb($env);

                    $result = $yauction->myCloseList();
                    if($result === 'Invalid Token'){
                        $yauction->refreshToken();

                        $env = Model_Yauctiontoken::getAccessToken($open_id);
                        $yauction->setTokenFromDb($env);

                        $result = $yauction->myCloseList();

                    }else if($result === 'Invalid Request'||$result === 'Other Error'){
                        $errormsg = $result;

                    }

                }

            }
            if(is_null($errormsg)){

                $yauctionsell = Model_Yauctionsell::find('all',array(
                    'where'=> array(
                        array( 'open_id', '=', $open_id )
                    ),'order_by' => array(
                        array('end_time' ,'desc')
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
                    $itemarray += array('end_time' => $item->end_time);

                    $resultarray[]= $itemarray;

                    $itemarray = array();
                }


                $resultsetarray = array('ResultSet'=> array('ResultCount' => count($yauctionsell),'Result' => $resultarray));

                return $resultsetarray;

            }else{

                return array('ERROR'=>$errormsg);

            }
        }
    }

}