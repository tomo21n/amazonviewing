<?php
class Controller_Admin_ManageAccount extends Controller_Admin
{

    public function action_index()
    {

        $where_array = array();
        if(Input::get('email')){
            $where_array[] = array('users.email','like','%'.Input::get('email').'%');
        }

        $result_arr= DB::select(DB::expr('count(users.id) AS COUNT'))
                       ->from('users')
                       ->where($where_array)
                       ->execute()
                       ->current();
        $data['count'] = $result_arr['COUNT'];

        $query =<<< EOF1
        SELECT users.id,
        users.email,
        users.group_id,
        metadata.nickname
        FROM users,
        (SELECT
            a.parent_id id,
            (select value from users_metadata b where a.parent_id = b.parent_id AND b.KEY = 'nickname') nickname
         FROM (select distinct parent_id from users_metadata) a
         order by id) metadata
         where users.id = metadata.id

EOF1;

        if(Input::get('email')){
            $query .= ' AND users.email like "%'.Input::get('email').'%"';
        }

        $getsegment = '?email='. Input::get('email');
        //Paginationの環境設定
        $config = array(
            'name' => 'default',
            'pagination_url' => ('admin/manageaccount/index') ,
            'uri_segment' => 4,
            'num_links' => 5,
            'per_page' => 50,
            'total_items' => $data['count'],
        );

        //Paginationのセット
        $pagination = Pagination::forge('pagination', $config);

        $query .= " limit ".$pagination->per_page;
        $query .= " offset ".$pagination->offset;
        $data['accountlist']=
              DB::query($query)->execute()->as_array();

        $this->template->title = "アカウント一覧";
        $this->template->content = View::forge('admin/manageaccount/index', $data);
        $this->template->content->set_safe('pagination', $pagination->render());


    }

    public function action_edit($id = null)
    {

        $query =<<< EOF1
        SELECT users.id,
        users.username,
        users.email,
        users.group_id,
        metadata.nickname
        FROM users,
        (SELECT
            a.parent_id id,
            (select value from users_metadata b where a.parent_id = b.parent_id AND b.KEY = 'nickname') nickname
         FROM (select distinct parent_id from users_metadata) a
         order by id) metadata
         where users.id = metadata.id
    AND users.id = {$id}
EOF1;
        $account = DB::query($query)->execute()->current();

        $val = Validation::forge();

        $val->add('group_id', 'Group ID')->add_rule('required');

        if ($val->run())
        {
            if($account['group_id'] <> Input::post('group_id')){
                $account['group_id'] = Input::post('group_id');
                $result = Auth::update_user(
                    array(
                        'group_id'        => Input::post('group_id'),
                    )
                ,$account['username']
                );

                if ($result)
                {
                    Session::set_flash('success', e('更新しました' ));

                    //Response::redirect('admin/manageaccount/edit/'.$id);
                }

                else
                {
                    Session::set_flash('error', e('更新できませんでした'));
                }
            }
        }

        else
        {
            if (Input::method() == 'POST')
            {
                $account['group_id'] = Input::post('group_id');

                Session::set_flash('error', $val->error());
            }

            $this->template->set_global('item', $account, false);
        }

        $data['account'] =$account;

        $this->template->title = "アカウント更新";
        $this->template->content = View::forge('admin/manageaccount/edit',$data);

    }

    public function action_delete()
    {
        if(Input::post('id')){

            $id = Input::post('id');

            if ($account = \Model\Auth_User::find($id))
            {
                Auth::delete_user($account->username);
                Session::set_flash('success', e('削除しました #'.$id));
            }

            else
            {
                Session::set_flash('error', e('削除できませんでした #'.$id));
            }

        }

        Response::redirect('admin/manageaccount/');

    }


    public function action_passwordreset()
    {

        if(Input::post('email')){
            $new_password = null;

            // 現在のユーザーのパスワードをリセット
            $new_password = Auth::reset_password(Input::post('email'));

            if ($new_password) {

                $body=<<<EOF1
管理者にてパスワードをリセットしました。

新しいパスワードは、以下になります。

 {$new_password}

このパスワードで再度ログインして、
メニュー → アカウント → パスワード変更　より
パスワードを変更して下さい。

-----------------------------
MatrixNeo　
URL   : http://matrixneo.com

EOF1;

                $email = \Email::forge('jis');
                $email->from('info@matrixneo.com', 'MatrixNeo');
                $email->to(Input::post('email'));
                $email->subject('【MatrixNeo】パスワードリセット');
                $email->body($body);

                try {
                    $email->send();
                } catch (\EmailValidationFailedException $e) {
                    $err_msg = '送信に失敗しました。';
                    echo $err_msg;
                } catch (\EmailSendingFailedException $e) {
                    $err_msg = '送信に失敗しました。';
                    echo $err_msg;
                }
            }

            Session::set_flash('success', e('パスワードをリセットし、対象ユーザにメールを送信しました。 '));


        }else{

            Session::set_flash('error', e('パスワードをリセットできませんでした。'));
        }

        Response::redirect('admin/manageaccount');


    }

}
