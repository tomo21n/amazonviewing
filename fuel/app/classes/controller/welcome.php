<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */

class Controller_Welcome extends Controller
{

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
        $url = 'https://auctions.yahooapis.jp/AuctionWebService/V2/openWatchList';
        $curl = \Request::forge($url, 'curl');
        $param['start'] = '1';
        $param['output'] = 'php';
        $curl->set_params($param);
        $curl->set_header('Authorization', 'Bearer STTRe3QSlJvi4RJHmSABuC1HEZYk3dN0cpA0ZdF9S7kE9PUFe_gFDL6u3Cf79MTNDC8vE1ewBzX62iEoAPniVPEwo8.QqwoW4ftm.Z6Subejj7odaXSqdmESavQUBGyvBKDx0vymFXSyefpPRHfKOewmo8G91h.Y8yKkFM_VEl1spxc6vX91k9_BEw8dSKCElEA_ArfGxbQosLdizuxT_9AkLvHWewGuykSgo1dgxQOh74SITBcDXOpNIjBqYGqgrd8VaLjTBg1_UKJmeSYmNJa.jV5InYglRhMWwYu4iysU_0OykKi4ez4M1vnQ9ccsUEnDjXwk2VYxGi5IsQxmVx_itwvSW8N8D_8vaLXJmM.cC9kgHSuL3ugfsWQ7dsiR91DH0CLtOZt7LXV.QxceNpDy6oEfBLQLFDO5Qc21Dz9MqAI5EkupbNQV1Ug99mm6ZmgCmWyt3.TdgfhiFUR.bvCAYtUiqgLvWQMzhRKpNVbkkIJ7qTgKNOmOorGce08slvg8359t_FvnucbdTcfc8hyoEQMW4k6K95wCdBAFlFhDiSDwMB_ioZWtaLTY1ZURpv9RM0w44g6r7J941Rnq1c7C95M2mexc41eMKKF3Ot4Ybr7y5b2nYpcjvQXzphWoLJenZ3dbgPS04A7GDP3Z6SGRu_kBdqN4lkfiAjSJ2DQmXxqAw7ZjFf2eEj8ml06xTqJ9Q6VyA6_I89qIfEJ8OPacC57OQE4-');
        $curl->execute();
        //$response = $curl->response();
        echo '<pre>';
        $size = $curl->response_info();
        var_dump($size);
        echo '</pre>';


        /*$test = new Mwsutil();
        $test->init('US','SubmitFeed','MWS');

        $updateitems[] = array('SKU'=>'CE-B00B547VD4','StandardPrice'=>'176.50');
        $updateitems[] = array('SKU'=>'KITCHEN-B000S7Q5MC','StandardPrice'=>'226.70');
        $updateitems[] = array('SKU'=>'KITCHEN-B000FVRI2W','StandardPrice'=>'244.07');
        $updateitems[] = array('SKU'=>'KITCHEN-B004Y9OILA','StandardPrice'=>'137.48');

        $feed = $test->makeFlatFileInvloader();
        //$FeedType = '_POST_INVENTORY_AVAILABILITY_DATA_';
        //$FeedType = '_POST_PRODUCT_PRICING_DATA_';
        $FeedType = '_POST_FLAT_FILE_INVLOADER_DATA_';

        $result = $test->submitFeed($feed,$FeedType);

        echo '<pre>';
        var_dump($result);
        echo '</pre>';*/

        //Response::redirect('user/login');

	}

    public function action_test(){

        $test = new Yauction();
        $test->yconnect('nakamura');

    }

    public function action_test2(){
        $result =Model_Yauctiontoken::getAccessToken('nakamura');
        echo '<pre>';
        var_dump($result->refresh_token);
        echo '</pre>';
        //$test = new Yauction();
        //$test->refreshToken();
    }


	/**
	 * A typical "Hello, Bob!" type example.  This uses a ViewModel to
	 * show how to use them.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_hello()
	{
		return Response::forge(ViewModel::forge('welcome/hello'));
	}

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(ViewModel::forge('welcome/404'), 404);
	}

}
