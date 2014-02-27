<?php
namespace Amazon;
class Controller_Offerprice extends Controller_User{

	public function action_index()
	{
		$data['offerprices'] = Model_offerprice::find('all');
		$this->template->title = "offerprices";
		$this->template->content = View::forge('user/offerprice/index', $data);

	}

	public function action_view($id = null)
	{
		$data['offerprice'] = Model_offerprice::find($id);

		$this->template->title = "offerprice";
		$this->template->content = View::forge('user/offerprice/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_offerprice::validate('create');

			if ($val->run())
			{
				$offerprice = Model_offerprice::forge(array(
					'part_id' => Input::post('part_id'),
					'survey_dtm' => Input::post('survey_dtm'),
					'country' => Input::post('country'),
					'new_1st_price' => Input::post('new_1st_price'),
					'new_2nd_price' => Input::post('new_2nd_price'),
					'new_3rd_price' => Input::post('new_3rd_price'),
					'used_1st_price' => Input::post('used_1st_price'),
					'used_2nd_price' => Input::post('used_2nd_price'),
					'used_3rd_price' => Input::post('used_3rd_price'),
					'new_1st_qty' => Input::post('new_1st_qty'),
					'new_2nd_qty' => Input::post('new_2nd_qty'),
					'new_3rd_qty' => Input::post('new_3rd_qty'),
					'used_1st_qty' => Input::post('used_1st_qty'),
					'used_2nd_qty' => Input::post('used_2nd_qty'),
					'used_3rd_qty' => Input::post('used_3rd_qty'),
					'new_1st_shipfee' => Input::post('new_1st_shipfee'),
					'new_2nd_shipfee' => Input::post('new_2nd_shipfee'),
					'new_3rd_shipfee' => Input::post('new_3rd_shipfee'),
					'used_1st_shipfee' => Input::post('used_1st_shipfee'),
					'used_2nd_shipfee' => Input::post('used_2nd_shipfee'),
					'used_3rd_shipfee' => Input::post('used_3rd_shipfee'),
				));

				if ($offerprice and $offerprice->save())
				{
					Session::set_flash('success', e('Added offerprice #'.$offerprice->id.'.'));

					Response::redirect('user/offerprice');
				}

				else
				{
					Session::set_flash('error', e('Could not save offerprice.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "offerprices";
		$this->template->content = View::forge('user/offerprice/create');

	}

	public function action_edit($id = null)
	{
		$offerprice = Model_offerprice::find($id);
		$val = Model_offerprice::validate('edit');

		if ($val->run())
		{
			$offerprice->part_id = Input::post('part_id');
			$offerprice->survery_dtm = Input::post('survey_dtm');
			$offerprice->countory = Input::post('country');
			$offerprice->new_1st_price = Input::post('new_1st_price');
			$offerprice->new_2nd_price = Input::post('new_2nd_price');
			$offerprice->new_3rd_price = Input::post('new_3rd_price');
			$offerprice->used_1st_price = Input::post('used_1st_price');
			$offerprice->used_2nd_price = Input::post('used_2nd_price');
			$offerprice->used_3rd_price = Input::post('used_3rd_price');
			$offerprice->new_1st_qty = Input::post('new_1st_qty');
			$offerprice->new_2nd_qty = Input::post('new_2nd_qty');
			$offerprice->new_3rd_qty = Input::post('new_3rd_qty');
			$offerprice->used_1st_qty = Input::post('used_1st_qty');
			$offerprice->used_2nd_qty = Input::post('used_2nd_qty');
			$offerprice->used_3rd_qty = Input::post('used_3rd_qty');
			$offerprice->new_1st_shipfee = Input::post('new_1st_shipfee');
			$offerprice->new_2nd_shipfee = Input::post('new_2nd_shipfee');
			$offerprice->new_3rd_shipfee = Input::post('new_3rd_shipfee');
			$offerprice->used_1st_shipfee = Input::post('used_1st_shipfee');
			$offerprice->used_2nd_shipfee = Input::post('used_2nd_shipfee');
			$offerprice->used_3rd_shipfee = Input::post('used_3rd_shipfee');

			if ($offerprice->save())
			{
				Session::set_flash('success', e('Updated offerprice #' . $id));

				Response::redirect('user/offerprice');
			}

			else
			{
				Session::set_flash('error', e('Could not update offerprice #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$offerprice->part_id = $val->validated('part_id');
				$offerprice->survery_dtm = $val->validated('survey_dtm');
				$offerprice->countory = $val->validated('country');
				$offerprice->new_1st_price = $val->validated('new_1st_price');
				$offerprice->new_2nd_price = $val->validated('new_2nd_price');
				$offerprice->new_3rd_price = $val->validated('new_3rd_price');
				$offerprice->used_1st_price = $val->validated('used_1st_price');
				$offerprice->used_2nd_price = $val->validated('used_2nd_price');
				$offerprice->used_3rd_price = $val->validated('used_3rd_price');
				$offerprice->new_1st_qty = $val->validated('new_1st_qty');
				$offerprice->new_2nd_qty = $val->validated('new_2nd_qty');
				$offerprice->new_3rd_qty = $val->validated('new_3rd_qty');
				$offerprice->used_1st_qty = $val->validated('used_1st_qty');
				$offerprice->used_2nd_qty = $val->validated('used_2nd_qty');
				$offerprice->used_3rd_qty = $val->validated('used_3rd_qty');
				$offerprice->new_1st_shipfee = $val->validated('new_1st_shipfee');
				$offerprice->new_2nd_shipfee = $val->validated('new_2nd_shipfee');
				$offerprice->new_3rd_shipfee = $val->validated('new_3rd_shipfee');
				$offerprice->used_1st_shipfee = $val->validated('used_1st_shipfee');
				$offerprice->used_2nd_shipfee = $val->validated('used_2nd_shipfee');
				$offerprice->used_3rd_shipfee = $val->validated('used_3rd_shipfee');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('offerprice', $offerprice, false);
		}

		$this->template->title = "offerprices";
		$this->template->content = View::forge('user/offerprice/edit');

	}

	public function action_delete($id = null)
	{
		if ($offerprice = Model_offerprice::find($id))
		{
			$offerprice->delete();

			Session::set_flash('success', e('Deleted offerprice #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete offerprice #'.$id));
		}

		Response::redirect('user/offerprice');

	}


}