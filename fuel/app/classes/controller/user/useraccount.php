<?php

class Controller_User_Useraccount extends Controller_User
{
    public $template = 'template';

    public function action_index()
    {
        Response::redirect('user/useraccount/edit');

    }


    public function action_edit($id = null)
    {
        $account = array();
        $account['user_id']  = Auth::get_user_id();
        $account['nickname'] = Auth::get('nickname');
        $account['email']    = Auth::get('email');

        $val = Validation::forge('edit');
        $val->add_field('nickname', '名前', 'required|max_length[50]');
        $val->add_field('email', 'メールアドレス', 'required|valid_email');


        if ($val->run())
        {

            try{
                $account['nickname'] = Input::post('nickname');
                $account['email']    = Input::post('email');

                $user = \Auth::instance()->update_user($account);

                if(Input::post('password')){
                    \Auth::instance()->change_password(Input::post('password'));
                }

                if ($user)
                {
                    Session::set_flash('success', e('アカウントを更新しました。'));
                    Response::redirect('user/useraccount/edit');
                }

            }catch (\SimpleUserUpdateException $e) {

                Session::set_flash('error', e($e->getMessage()));

            }

         }

        else
        {
            if (Input::method() == 'POST')
            {
                $account['nickname'] = $val->validated('nickname');
                $account['email'] = $val->validated('email');
                $account['password'] = $val->validated('password');

                Session::set_flash('error', $val->error());
            }

            $this->template->set_global('account', $account, false);
        }

        $this->template->title = "アカウント編集";
        $this->template->content = View::forge('user/useraccount/edit');

    }

    public function action_changepassword($id = null)
    {
        $account = array();

        $val = Validation::forge('edit');
        $val->add_field('password_old', '現在のパスワード', 'required|max_length[50]');
        $val->add_field('password', '新パスワード', 'required|max_length[50]');
        $val->add('password_conf', '新パスワード再入力')
            ->add_rule('match_field', 'password')
            ->add_rule('required');


        if ($val->run())
        {

            $user = \Auth::instance()->change_password(Input::post('password_old'),Input::post('password'));

            if ($user)
            {
                Session::set_flash('success', e('パスワードを更新しました'));
                Response::redirect('user/useraccount/changepassword');
            }else{
                Session::set_flash('error', e('パスワードが間違っています'));
                Response::redirect('user/useraccount/changepassword');
            }

        }

        else
        {
            if (Input::method() == 'POST')
            {
                $account['password_old'] = $val->validated('password_old');
                $account['password'] = $val->validated('password');
                $account['password_conf'] = $val->validated('password_conf');

                Session::set_flash('error', $val->error());
            }

            $this->template->set_global('account', $account, false);
        }

        $this->template->title = "パスワード変更";
        $this->template->content = View::forge('user/useraccount/changepassword');

    }

    public function action_delete($id = null)
    {
        if ($memo = Model_Memo::find($id))
        {
            $memo->delete();

            Session::set_flash('success', e('Deleted memo #'.$id));
        }

        else
        {
            Session::set_flash('error', e('Could not delete memo #'.$id));
        }

        Response::redirect('admin/memo');

    }


}