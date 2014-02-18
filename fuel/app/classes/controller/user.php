<?php

class Controller_User extends Controller_Base
{
	public $template = 'template';

	public function before()
	{
		parent::before();

		if (Request::active()->controller !== 'Controller_User' or ! in_array(Request::active()->action, array('login', 'logout')))
		{
			if (Auth::check())
			{
                $group = Auth::get_groups();
				$user_group_id = 2;
				if ( ! ($group[0][1]['id'] >= $user_group_id) )
				{
                    Auth::logout();
					Session::set_flash('error', e('ログインできません。'));
					Response::redirect('user/login');
				}
			}
			else
			{
				Response::redirect('user/login');
			}
		}
	}

	public function action_login()
	{
		// Already logged in
		Auth::check() and Response::redirect('user');

		$val = Validation::forge();

		if (Input::method() == 'POST')
		{
			$val->add('email', 'Email')
			    ->add_rule('required');
			$val->add('password', 'パスワード')
			    ->add_rule('required');

			if ($val->run())
			{
				$auth = Auth::instance();

				// check the credentials. This assumes that you have the previous table created
				if (Auth::check() or $auth->login(Input::post('email'), Input::post('password')))
				{
                    if ( Auth::member(1) )
                    {
                        $this->template->set_global('login_error', 'ログインできませんでした。');
                    }else{
                        // credentials ok, go right in
                        if (Config::get('auth.driver', 'Simpleauth') == 'Ormauth')
                        {
                            $current_user = Model\Auth_User::find_by_username(Auth::get_screen_name());
                        }
                        else
                        {
                            $current_user = Model_User::find_by_username(Auth::get_screen_name());
                        }
                        Session::set_flash('success', e('Welcome, '.Auth::get('nickname')));
                        Response::redirect('user');
                    }

				}
				else
				{
					$this->template->set_global('login_error', 'ログインできませんでした。');
				}
			}
		}

		$this->template->title = 'Login';
		$this->template->content = View::forge('user/login', array('val' => $val), false);
	}

	/**
	 * The logout action.
	 *
	 * @access  public
	 * @return  void
	 */
	public function action_logout()
	{
		Auth::logout();
		Response::redirect('user');
	}

	/**
	 * The index action.
	 *
	 * @access  public
	 * @return  void
	 */
	public function action_index()
	{
		$this->template->title = 'Dashboard';
		$this->template->content = View::forge('user/dashboard');
	}

}

/* End of file admin.php */
