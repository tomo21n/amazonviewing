<?php
namespace Amazon;
class Controller_SalesPart extends Controller_User{

	public function action_index()
	{
		$data['salesParts'] = Model_SalesPart::find('all');
		$this->template->title = "SalesParts";
		$this->template->content = View::forge('user/salespart/index', $data);

	}

	public function action_view($id = null)
	{
		$data['salesPart'] = Model_SalesPart::find($id);

		$this->template->title = "SalesPart";
		$this->template->content = View::forge('user/salespart/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_SalesPart::validate('create');

			if ($val->run())
			{
				$salesPart = Model_SalesPart::forge(array(
					'part_id' => Input::post('part_id'),
					'asin' => Input::post('asin'),
					'ean' => Input::post('ean'),
					'title' => Input::post('title'),
					'category' => Input::post('category'),
					'url' => Input::post('url'),
					'image_s' => Input::post('image_s'),
					'image_l' => Input::post('image_l'),
					'volume' => Input::post('volume'),
					'wight' => Input::post('wight'),
					'release_date' => Input::post('release_date'),
				));

				if ($salesPart and $salesPart->save())
				{
					Session::set_flash('success', e('Added salesPart #'.$salesPart->id.'.'));

					Response::redirect('user/salespart');
				}

				else
				{
					Session::set_flash('error', e('Could not save salesPart.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Salesparts";
		$this->template->content = View::forge('user/salespart/create');

	}

	public function action_edit($id = null)
	{
		$salesPart = Model_SalesPart::find($id);
		$val = Model_SalesPart::validate('edit');

		if ($val->run())
		{
			$salesPart->part_id = Input::post('part_id');
			$salesPart->asin = Input::post('asin');
			$salesPart->ean = Input::post('ean');
			$salesPart->title = Input::post('title');
			$salesPart->category = Input::post('category');
			$salesPart->url = Input::post('url');
			$salesPart->image_s = Input::post('image_s');
			$salesPart->image_l = Input::post('image_l');
			$salesPart->volume = Input::post('volume');
			$salesPart->wight = Input::post('wight');
			$salesPart->release_date = Input::post('release_date');

			if ($salesPart->save())
			{
				Session::set_flash('success', e('Updated salesPart #' . $id));

				Response::redirect('user/salespart');
			}

			else
			{
				Session::set_flash('error', e('Could not update salesPart #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$salesPart->part_id = $val->validated('part_id');
				$salesPart->asin = $val->validated('asin');
				$salesPart->ean = $val->validated('ean');
				$salesPart->title = $val->validated('title');
				$salesPart->category = $val->validated('category');
				$salesPart->url = $val->validated('url');
				$salesPart->image_s = $val->validated('image_s');
				$salesPart->image_l = $val->validated('image_l');
				$salesPart->volume = $val->validated('volume');
				$salesPart->wight = $val->validated('wight');
				$salesPart->release_date = $val->validated('release_date');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('salesPart', $salesPart, false);
		}

		$this->template->title = "SalesParts";
		$this->template->content = View::forge('user/salespart/edit');

	}

	public function action_delete($id = null)
	{
		if ($salesPart = Model_SalesPart::find($id))
		{
			$salesPart->delete();

			Session::set_flash('success', e('Deleted salesPart #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete salesPart #'.$id));
		}

		Response::redirect('user/salespart');

	}


}