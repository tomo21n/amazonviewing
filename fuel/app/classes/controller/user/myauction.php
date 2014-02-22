<?php
class Controller_User_Myauction extends Controller_User{

	public function action_index()
	{
        if(Input::get('update')){
            Response::redirect('user/myauction/updatelist/'.Input::get('open_id'));
        }else{
            $user_info = Auth::get_user_id();
            $where_array = array();
            if(Input::get('open_id')){
                $where_array[] = array('open_id','=',Input::get('open_id'));
            }

            $condition = array(
                'where'=> array(    // リレーション内での検索
                    array( 'user_id', '=', $user_info[1] )
                ),
                'related' => array(
                    'yauctiontoken' => array(   // リレーション条件を指定
                        'where' => $where_array
                    ),
                ),
            );
            $data['count'] = Model_Yauctionsell::count($condition);
            //$getsegment = '?word='. Input::get('word');
            //Paginationの環境設定
            $config = array(
                'name' => 'default',
                'pagination_url' => ('user/myauction/index') ,
                'uri_segment' => 4,
                'num_links' => 5,
                'per_page' => 50,
                'total_items' => $data['count'],
            );

            //Paginationのセット
            $pagination = Pagination::forge('pagination', $config);
            $condition += array('limit' => $pagination->per_page);
            $condition += array('offset' => $pagination->offset);
            $data['myauctions'] = Model_Yauctionsell::find('all',$condition);

            $myauctiontokens = Model_Yauctiontoken::find('all',array(
                'where'=> array(    // リレーション内での検索
                    array( 'user_id', '=', $user_info[1] )
                ),
            ));
            $open_ids = array(''=>'指定なし');
            foreach($myauctiontokens as $myauctiontoken){
                $open_ids += array($myauctiontoken->open_id => $myauctiontoken->open_id );
            }
            $data['open_ids'] = $open_ids;

            $this->template->title = "My Auction";
            $this->template->content = View::forge('user/myauction/index', $data);
        }

	}
    public function action_updatelist($open_id = null){

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
                Session::set_flash('error', e('Error :' . $result));

            }else if($result){
                Session::set_flash('success', e('Updated myauction '));

            }

            Response::redirect('user/myauction/');

        }

    }

    public function action_accesstokenlist(){

        $user_info = Auth::get_user_id();

        $access_tokens = Model_Yauctiontoken::find('all',array(
            'where'=>array(
                array('user_id'=> $user_info[1])
            )
        ));

        $data['access_token'] = $access_tokens;

        $this->template->title = "myauction";
        $this->template->content = View::forge('user/myauction/access_token', $data);

    }

    public function action_editaccesstoken($id = null)
    {
        $myaccesstoken = Model_Yauctiontoken::find($id);
        $val = Model_Yauctiontoken::validate('edit');

        if ($val->run())
        {
            $myaccesstoken->yahoo_user_id = Input::post('yahoo_user_id');


            if ($myaccesstoken->save())
            {
                Session::set_flash('success', e('Updated myauction #' . $id));

                Response::redirect('user/myauction/accesstokenlist');
            }

            else
            {
                Session::set_flash('error', e('Could not update myauction #' . $id));
            }
        }

        else
        {
            if (Input::method() == 'POST')
            {
                $myaccesstoken->yahoo_user_id = $val->validated('yahoo_user_id');

                Session::set_flash('error', $val->error());
            }

            $this->template->set_global('myaccesstoken', $myaccesstoken, false);
        }

        $this->template->title = "my access token";
        $this->template->content = View::forge('user/myauction/editaccesstoken');

    }

    public function action_yconnect(){

        $user_info = Auth::get_user_id();

        $yauction = new Yauction();
        $yauction->setRequestUri(Uri::current());
        $yauction->setUserId($user_info[1]);
        if($yauction->yconnect()){

            Session::set_flash('success', e('アクセストークンを獲得しました'));
            Response::redirect('user/myauction/accesstokenlist');

        }else{
            Session::set_flash('error', e('アクセストークンを獲得できませんでした'));
            Response::redirect('user/myauction/accesstokenlist');
        }

    }
    public function action_refreshtoken($open_id = null){

        $yauction = new Yauction();
        $yauction->setOpenId($open_id);
        if($yauction->refreshToken()){

            Session::set_flash('success', e('アクセストークンを獲得しました'));
            Response::redirect('user/myauction/accesstokenlist');

        }else{
            Session::set_flash('error', e('アクセストークンを獲得できませんでした'));
            Response::redirect('user/myauction/accesstokenlist');
        }

    }

    public function validatea(){
        $user_info = Auth::get_user_id();

        $yauction = new Yauction();
        $yauction->setUserId($user_info[1]);
        $yauction->validateAccessToken();
    }

	public function action_view($id = null)
	{
		$data['myauction'] = Model_Yauctionsell::find($id);

		$this->template->title = "myauction";
		$this->template->content = View::forge('user/myauction/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Yauctionsell::validate('create');

			if ($val->run())
			{
				$myauction = Model_Yauctionsell::forge(array(
                    'user_id' => Input::post('user_id'),
                    'auction_id' => Input::post('auction_id'),
                    'title' => Input::post('title'),
                    'highest_price'=> Input::post('highest_price'),
                    'winner_id'=> Input::post('winner_id'),
                    'item_list_url'=> Input::post('item_list_url'),
                    'message_title'=> Input::post('message_title'),
                    'end_time'=> Input::post('end_time'),
                    'auction_item_url'=> Input::post('auction_item_url'),
                    'image_url'=> Input::post('image_url'),
				));

				if ($myauction and $myauction->save())
				{
					Session::set_flash('success', e('Added myauction #'.$myauction->id.'.'));

					Response::redirect('user/myauction');
				}

				else
				{
					Session::set_flash('error', e('Could not save myauction.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "myauctions";
		$this->template->content = View::forge('user/myauction/create');

	}

	public function action_edit($id = null)
	{
		$myauction = Model_Yauctionsell::find($id);
		$val = Model_Yauctionsell::validate('edit');

		if ($val->run())
		{
            $myauction->user_id = Input::post('user_id');
            $myauction->auction_id = Input::post('auction_id');
            $myauction->title = Input::post('title');
            $myauction->highest_price= Input::post('highest_price');
            $myauction->winner_id= Input::post('winner_id');
            $myauction->item_list_url= Input::post('item_list_url');
            $myauction->message_title= Input::post('message_title');
            $myauction->end_time= Input::post('end_time');
            $myauction->auction_item_url= Input::post('auction_item_url');
            $myauction->image_url= Input::post('image_url');

			if ($myauction->save())
			{
				Session::set_flash('success', e('Updated myauction #' . $id));

				Response::redirect('user/myauction');
			}

			else
			{
				Session::set_flash('error', e('Could not update myauction #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
                $myauction->user_id = $val->validated('user_id');
                $myauction->auction_id = $val->validated('auction_id');
                $myauction->title = $val->validated('title');
                $myauction->highest_price= $val->validated('highest_price');
                $myauction->winner_id= $val->validated('winner_id');
                $myauction->item_list_url= $val->validated('item_list_url');
                $myauction->message_title= $val->validated('message_title');
                $myauction->end_time= $val->validated('end_time');
                $myauction->auction_item_url= $val->validated('auction_item_url');
                $myauction->image_url= $val->validated('image_url');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('myauction', $myauction, false);
		}

		$this->template->title = "myauctions";
		$this->template->content = View::forge('user/myauction/edit');

	}

	public function action_delete($id = null)
	{
		if ($myauction = Model_Yauctionsell::find($id))
		{
			$myauction->delete();

			Session::set_flash('success', e('Deleted myauction #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete myauction #'.$id));
		}

		Response::redirect('user/myauction');

	}


}