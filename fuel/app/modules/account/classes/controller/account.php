<?php

class Controller_Account extends Controller_Base
{
    public $template = 'template';

    public function action_index()
    {
        \Response::redirect('account/create');

    }

    public function action_create()
    {
        if (\Input::method() == 'POST')
        {
            $val = \Validation::forge('create');
            $val->add_field('nickname', '名前', 'required|max_length[50]');
            $val->add_field('password', 'パスワード', 'required|max_length[50]');
            $val->add('password_conf', 'パスワード再入力')
                ->add_rule('required')
                ->add_rule('match_field', 'password');
            $val->add_field('email', 'メールアドレス', 'required|valid_email');

            if ($val->run()) {
                try{
                    $user = \Auth::instance()->create_user(
                        \Input::post('email')      //username
                        , \Input::post('password') //password
                        , \Input::post('email')    //email
                        ,  3                      //group
                        ,array(
                            'nickname' => \Input::post('nickname'),
                        )
                    );

                    if ($user)
                    {
                        \Session::set_flash('success', e('アカウントを作成しました。作成したアカウントでログインして下さい'));
                        \Response::redirect('user/login');
                    }

                }catch (\SimpleUserUpdateException $e) {

                    \Session::set_flash('error', e($e->getMessage()));

                }

            }else {

                \Session::set_flash('error', $val->error());

            }

        }

        $this->template->title = "Accounts";
        $this->template->content = View::forge('account/create');

    }


}