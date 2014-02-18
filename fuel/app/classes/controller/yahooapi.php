<?php
class Controller_Yahooapi extends Controller_Rest
{

    public function get_list()
    {
        if (Input::method() == 'POST') {

            if ($user = Auth::validate_user(Input::post('email'), Input::post('password')))
            {

                if ( Auth::member(1) )
                {
                    //禁止ユーザ
                    $result = false;
                    $errormsg = 'アカウントがロックされています';
                }else{
                    $result = $user->username;
                }
            }
            else
            {
                $result = false;
                $errormsg = 'ID,パスワードが一致しません';

            }

            if($result){
                return array('OK'=>'OK');
            }else{
                return array('ERROR'=>$errormsg);

            }
        }
    }

}