<?php
class Controller_User_Inventory extends Controller_User{

	public function action_index()
	{
        //$currency = \MyUtil::get_rate('US');

        $user_info = Auth::get_user_id();
        $where_array = array();
        //$where_array[] = array('inventories.user_id','=',$user_info[1]);
        $ary_keyword = preg_split('/[\s]+/', mb_convert_kana(Input::get('word'), 's'), -1, PREG_SPLIT_NO_EMPTY);
        if(Input::get('asin')){
            $where_array[] = array('ASIN','=',Input::get('asin'));
        }
        foreach($ary_keyword as $keyword){
            $where_array[] = array('TITLE','like','%'.$keyword.'%');
        };

        $condition = array(
            'where'=> array(    // リレーション内での検索
                array( 'user_id', '=', $user_info[1] )
            ),
            'related' => array(
                'salespart' => array(   // リレーション条件を指定
                    'where' => $where_array
                ),
                'offerprice' => array(   // リレーション条件を指定
                    'where' => $where_array
                )

            ),
        );
        $data['count'] = Model_Inventory::count($condition);
        //$getsegment = '?word='. Input::get('word');
        //Paginationの環境設定
        $config = array(
            'name' => 'default',
            'pagination_url' => ('user/inventory/index') ,
            'uri_segment' => 4,
            'num_links' => 5,
            'per_page' => 50,
            'total_items' => $data['count'],
        );

        //Paginationのセット
        $pagination = Pagination::forge('pagination', $config);
        $condition += array('limit' => $pagination->per_page);
        $condition += array('offset' => $pagination->offset);
        $data['inventories'] = Model_Inventory::find('all',$condition);


        $this->template->title = "在庫一覧";
        $this->template->content = View::forge('user/inventory/index', $data);
	}

	public function action_view($id = null)
	{
		$data['inventory'] = Model_Inventory::find($id);

		$this->template->title = "Inventory";
		$this->template->content = View::forge('user/inventory/view', $data);

	}

	public function action_create()
    {
        if (Input::method() == 'POST') {
            $val = Model_Inventory::validate('create');

            if ($val->run()) {

                $salespart = Model_Salespart::find('first', array('where' => array(array('asin' => Input::post('asin')))));

                if(count($salespart) == 0){

                    $salespart = Model_Salespart::forge(array(
                        'asin' => Input::post('asin')
                    ));

                    if (!$salespart or !($salespart->save())) {

                        Session::set_flash('error', e('Could not save inventory.'));

                        Response::redirect('user/inventory/create');

                    }

                }
                $inventory = Model_Inventory::forge(array(
                    'user_id' => MyUtil::get_myuserid(),
                    'part_id' => $salespart->part_id,
                    'sku' => Input::post('sku'),
                    'sale_qty' => Input::post('sale_qty'),
                    'sale_price' => Input::post('sale_price'),
                    'condition' => Input::post('condition'),
                    'channel' => Input::post('channel'),
                    'comment' => Input::post('comment'),
                ));


                if ($inventory and $inventory->save()) {
                    Session::set_flash('success', e('Added inventory #' . $inventory->id . '.'));

                    Response::redirect('user/inventory/');

                } else {
                    Session::set_flash('error', e('Could not save inventory.'));
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }

		$this->template->title = "Inventories";
		$this->template->content = View::forge('user/inventory/create');

	}

	public function action_edit($id = null)
	{
		$inventory = Model_Inventory::find($id);
		$val = Model_Inventory::validate('edit');

		if ($val->run())
		{
			$inventory->sku = Input::post('sku');
			$inventory->sale_qty = Input::post('sale_qty');
			$inventory->sale_price = Input::post('sale_price');
			$inventory->condition = Input::post('condition');
			$inventory->channel = Input::post('channel');
			$inventory->comment = Input::post('comment');

			if ($inventory->save())
			{
				Session::set_flash('success', e('Updated inventory #' . $id));

				Response::redirect('user/inventory');
			}

			else
			{
				Session::set_flash('error', e('Could not update inventory #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$inventory->sku = $val->validated('sku');
				$inventory->sale_qty = $val->validated('sale_qty');
				$inventory->sale_price = $val->validated('sale_price');
				$inventory->condition = $val->validated('condition');
				$inventory->channel = $val->validated('channel');
				$inventory->comment = $val->validated('comment');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('inventory', $inventory, false);
		}

		$this->template->title = "Inventories";
		$this->template->content = View::forge('user/inventory/edit');

	}

	public function action_delete($id = null)
	{
		if ($inventory = Model_Inventory::find($id))
		{
			$inventory->delete();

			Session::set_flash('success', e('Deleted inventory #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete inventory #'.$id));
		}

		Response::redirect('user/inventory');

	}


}