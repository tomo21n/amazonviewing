<?php
/**
 * YahooAuctionAPI Class
 */

class Yauction{

    private $client_id;
    private $client_secret;
    private $access_token;
    private $refresh_token;
    private $expiration;
    private $updated_at;
    private $redirect_uri;
    private $state;
    private $nonce;
    private $user_id;
    private $open_id;

    public function __construct(){

        require_once(PKGPATH . 'yconnect/YConnect.inc');

        // アプリケーションID, シークレッvト
        $this->client_id     = "dj0zaiZpPUZLTFlOR2tOVGJjcCZzPWNvbnN1bWVyc2VjcmV0Jng9NTg-";
        $this->client_secret = "020f106d95756fd10ba6001ccbabc1cee2790c5e";
        // リクエストとコールバック間の検証用のランダムな文字列を指定してください
        $this->state = "44Oq44Ki5YWF44Gr5L+644Gv44Gq44KL77yB";
        // リプレイアタック対策のランダムな文字列を指定してください
        $this->nonce = "5YOV44Go5aWR57SE44GX44GmSUTljqjjgavjgarjgaPjgabjgog=";

    }

    public function setRequestUri($redirect_uri){

        $this->redirect_uri = $redirect_uri;

    }

    public function setUserId($user_id){

        $this->user_id = $user_id;

    }

    public function setOpenId($open_id){

        $this->open_id = $open_id;

    }

    public function setTokenFromDb($env){

        if($env){
            $this->access_token = $env->access_token;
            $this->refresh_token = $env->refresh_token;
            $this->expiration = $env->expiration;
            $this->open_id = $env->open_id;
            $this->user_id = $env->user_id;
            $this->updated_at = $env->updated_at;
        }
    }

    public function yconnect(){

        // 各パラメータ初期化
        //$redirect_uri = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];

        $response_type = OAuth2ResponseType::CODE_IDTOKEN;
        $scope = array(
            OIDConnectScope::OPENID,
            OIDConnectScope::PROFILE,
            OIDConnectScope::EMAIL,
            OIDConnectScope::ADDRESS
        );
        $display = OIDConnectDisplay::DEFAULT_DISPLAY;
        $prompt = array(
            OIDConnectPrompt::DEFAULT_PROMPT
        );

        // クレデンシャルインスタンス生成
        $cred = new ClientCredential( $this->client_id, $this->client_secret );
        // YConnectクライアントインスタンス生成
        $client = new YConnectClient( $cred );

        // デバッグ用ログ出力
        $client->enableDebugMode();

        try {

            // Authorization Codeを取得
            $code_result = $client->getAuthorizationCode( $this->state );

            if( !$code_result ) {

                /*****************************
                Authorization Request
                 *****************************/

                // Authorizationエンドポイントにリクエスト
                $client->requestAuth(
                    $this->redirect_uri,
                    $this->state,
                    $this->nonce,
                    $response_type,
                    $scope,
                    $display,
                    $prompt
                );

            } else {

                /****************************
                Access Token Request
                 ****************************/

                // Tokenエンドポイントにリクエスト
                $client->requestAccessToken(
                    $this->redirect_uri,
                    $code_result
                );
                $this->access_token  = $client->getAccessToken();
                $this->refresh_token = $client->getRefreshToken();
                $this->expiration    = $client->getAccessTokenExpiration();
                $this->open_id      = $client->getIdToken()->user_id;
                Model_Yauctiontoken::setAccessToken($this->user_id,$this->open_id,$this->access_token,$this->refresh_token,$this->expiration);

                return true;

            }

        } catch ( OAuth2ApiException $ae ) {

            // アクセストークンが有効期限切れであるかチェック
            if( $ae->invalidToken() ) {

                /************************************
                Refresh Access Token Request
                 ************************************/

                try {
                    $token = Model_Yauctiontoken::getAccessToken($this->user_id,$this->open_id);

                    // Tokenエンドポイントにリクエストしてアクセストークンを更新
                    $client->refreshAccessToken( $token->refresh_token );
                    $this->access_token  = $client->getAccessToken();
                    $this->expiration    = $client->getAccessTokenExpiration();
                    if(Model_Yauctiontoken::setRefreshToken($this->user_id,$this->open_id,$this->access_token ,$this->expiration)){

                        return true;

                    }else{

                        return false;

                    };


                } catch ( OAuth2TokenException $te ) {

                    // リフレッシュトークンが有効期限切れであるかチェック
                    if( $te->invalidGrant() ) {
                        // はじめのAuthorizationエンドポイントリクエストからやり直してください

                        echo "<h1>Refresh Token has Expired</h1>";
                    }

                    echo "<pre>" . print_r( $te, true ) . "</pre>";
                    return false;

                } catch ( Exception $e ) {

                    echo "<pre>" . print_r( $e, true ) . "</pre>";
                    return false;

                }

            } else if( $ae->invalidRequest() ) {
                echo "<h1>Invalid Request</h1>";
                echo "<pre>" . print_r( $ae, true ) . "</pre>";
                return false;

            } else {
                echo "<h1>Other Error</h1>";
                echo "<pre>" . print_r( $ae, true ) . "</pre>";
                return false;

            }

        } catch ( Exception $e ) {
            echo "<pre>" . print_r( $e, true ) . "</pre>";
            return false;

        }

    }

    public function openWatchList(){

        $url = 'https://auctions.yahooapis.jp/AuctionWebService/V2/openWatchList';
        $curl = \Request::forge($url, 'curl');
        $param['start'] = '1';
        $param['output'] = 'php';
        try{
            $curl->set_params($param);
            $curl->set_header('Authorization', 'Bearer '.$this->access_token);
            $curl->execute();
            $response = $curl->response();

            $result = unserialize($response->body);
            echo '<pre>';
            var_dump($result['ResultSet']);
            echo '</pre>';


            if($result['ResultSet']['totalResultsReturned'] > 0){

                foreach($result['ResultSet']['Result'] as $item){
                    $dbitem = Model_Yauctionsell::find('first', array(
                        'where' => array(
                            array('open_id', $this->open_id),
                            array('auction_id', $item['AuctionID']),
                        ),
                    ));
                    if(count($dbitem) > 0){
                        $dbitem->auction_id = $item['AuctionID'];
                        $dbitem->title = $item['Title'];

                        if ($dbitem->save())
                        {
                            Session::set_flash('success', e('Updated yauctionsell #' . $dbitem->auction_id));
                        }

                        else
                        {
                            Session::set_flash('error', e('Could not update inventory #'));
                            Response::redirect('user/myauction/');

                        }

                    }else{
                        $yauctionsell = Model_Yauctionsell::forge(array(
                            'user_id' => $this->user_id,
                            'open_id' => $this->open_id,
                            'auction_id' => $item['AuctionID'],
                            'title' => $item['Title'],
                        ));


                        if ($yauctionsell and $yauctionsell->save()) {
                            Session::set_flash('success', e('Added yauctionsell #' . $yauctionsell->auction_id . '.'));

                        } else {
                            Session::set_flash('error', e('Could not save inventory.'));
                            Response::redirect('user/myauction/');

                        }
                    }

                }

            }else{
                Session::set_flash('error', e('No Update.'));
            }
            Response::redirect('user/myauction/');

        }catch (\Fuel\Core\HttpNotFoundException $e){
            if(self::invalidToken($e->getMessage())){
                return 'Invalid Token';
            }else if(self::invalidRequest($e->getMessage())){
                return 'Invalid Request';
            }else{
                return 'Other Error';
            }

        }
    }



    public function myCloseList(){

        $url = 'https://auctions.yahooapis.jp/AuctionWebService/V2/myCloseList';
        $curl = \Request::forge($url, 'curl');
        $param['start'] = '1';
        $param['output'] = 'php';
        try{
            $curl->set_params($param);
            $curl->set_header('Authorization', 'Bearer '.$this->access_token);
            $curl->execute();
            $response = $curl->response();

            $result = unserialize($response->body);
            if($result['ResultSet']['totalResultsReturned'] > 0){

                foreach($result['ResultSet']['Result'] as $item){
                    $dbitem = Model_Yauctionsell::find('first', array(
                        'where' => array(
                            array('open_id', $this->open_id),
                            array('auction_id', $item['AuctionID']),
                        ),
                    ));

                    if(count($dbitem) > 0){
                        $dbitem->auction_id        = $item['AuctionID'];
                        $dbitem->title             = $item['Title'];
                        $dbitem->highest_price      = $item['HighestPrice'];
                        $dbitem->winner_id          = $item['Winner']['Id'];
                        $dbitem->winner_contact_url = $item['Winner']['ContactUrl'];
                        $dbitem->message_title      = $item['Message']['Title'];
                        $dbitem->end_time           = $item['EndTime'];
                        $dbitem->auction_item_url    = $item['AuctionItemUrl'];
                        $dbitem->image_url          = $item['Image']['Url'];

                        if ($dbitem->save())
                        {
                            //return true;
                        }

                        else
                        {

                            return false;

                        }

                    }else{
                        $yauctionsell = Model_Yauctionsell::forge(array(
                            'user_id' => $this->user_id,
                            'open_id' => $this->open_id,
                            'auction_id' => $item['AuctionID'],
                            'title' => $item['Title'],
                            'highest_price' => $item['HighestPrice'],
                            'winner_id' => $item['Winner']['Id'],
                            'winner_contact_url' => $item['Winner']['ContactUrl'],
                            'message_title' => $item['Message']['Title'],
                            'end_time' => $item['EndTime'],
                            'auction_item_url' => $item['AuctionItemUrl'],
                            'image_url' => $item['Image']['Url'],
                        ));


                        if ($yauctionsell and $yauctionsell->save()) {

                            //return true;

                        } else {

                            return false;
                        }
                    }

                }

                return true;

            }else{

                return false;
            }

        }catch (\Fuel\Core\HttpNotFoundException $e){
            if(self::invalidToken($e->getMessage())){
                return 'Invalid Token';
            }else if(self::invalidRequest($e->getMessage())){
                return 'Invalid Request';
            }else{
                return 'Other Error';
            }

        }
    }

    public function myWonList(){

        $url = 'https://auctions.yahooapis.jp/AuctionWebService/V2/myWonList';
        $curl = \Request::forge($url, 'curl');
        $param['start'] = '1';
        $param['output'] = 'php';
        try{
            $curl->set_params($param);
            $curl->set_header('Authorization', 'Bearer '.$this->access_token);
            $curl->execute();
            $response = $curl->response();

            $result = unserialize($response->body);

            //echo '<pre>';
            //var_dump($result);
            //echo '</pre>';

            if($result['ResultSet']['totalResultsReturned'] > 0){

                foreach($result['ResultSet']['Result'] as $item){
                    $dbitem = Model_Yauctionwon::find('first', array(
                        'where' => array(
                            array('open_id', $this->open_id),
                            array('auction_id', $item['AuctionID']),
                        ),
                    ));

                    if(count($dbitem) > 0){
                        $dbitem->auction_id        = $item['AuctionID'];
                        $dbitem->title             = $item['Title'];
                        $dbitem->won_price         = $item['WonPrice'];
                        $dbitem->seller_id          = $item['Seller']['Id'];
                        $dbitem->seller_contact_url = isset($item['ContactUrl'])? $item['ContactUrl']:'';
                        $dbitem->message_title      = $item['Message']['Title'];
                        $dbitem->end_time           = $item['EndTime'];
                        $dbitem->auction_item_url    = $item['AuctionItemUrl'];
                        $dbitem->image_url          = $item['Image']['Url'];

                        if ($dbitem->save())
                        {
                            //return true;
                        }

                        else
                        {

                            return false;

                        }

                    }else{
                        $yauctionwon = Model_Yauctionwon::forge(array(
                            'user_id' => $this->user_id,
                            'open_id' => $this->open_id,
                            'auction_id' => $item['AuctionID'],
                            'title' => $item['Title'],
                            'won_price' => $item['WonPrice'],
                            'seller_id' => $item['Seller']['Id'],
                            'seller_contact_url' => isset($item['ContactUrl'])? $item['ContactUrl']:'',
                            'message_title' => $item['Message']['Title'],
                            'end_time' => $item['EndTime'],
                            'auction_item_url' => $item['AuctionItemUrl'],
                            'image_url' => $item['Image']['Url'],
                        ));


                        if ($yauctionwon and $yauctionwon->save()) {

                            //return true;

                        } else {

                            return false;
                        }
                    }

                }

                return true;


            }else{

                return false;
            }

        }catch (\Fuel\Core\HttpNotFoundException $e){
            if(self::invalidToken($e->getMessage())){
                return 'Invalid Token';
            }else if(self::invalidRequest($e->getMessage())){
                return 'Invalid Request';
            }else{
                return 'Other Error';
            }

        }
    }

    public function refreshToken(){
        // クレデンシャルインスタンス生成
        $cred = new ClientCredential( $this->client_id, $this->client_secret );
        // YConnectクライアントインスタンス生成
        $client = new YConnectClient( $cred );

        try {

            $token = Model_Yauctiontoken::getAccessToken($this->open_id);

            // Tokenエンドポイントにリクエストしてアクセストークンを更新
            $client->refreshAccessToken( $token->refresh_token );
            $this->access_token  = $client->getAccessToken();
            $this->expiration    = $client->getAccessTokenExpiration();
            if(Model_Yauctiontoken::setRefreshToken($this->open_id,$this->access_token ,$this->expiration)){
                return true;
            }else{
                return false;
            }
        } catch ( OAuth2TokenException $te ) {

            // リフレッシュトークンが有効期限切れであるかチェック
            if( $te->invalidGrant() ) {
                // はじめのAuthorizationエンドポイントリクエストからやり直してください
                echo "<h1>Refresh Token has Expired</h1>";
            }

            echo "<pre>" . print_r( $te, true ) . "</pre>";
            return false;


        } catch ( Exception $e ) {
            echo "<pre>" . print_r( $e, true ) . "</pre>";
            return false;

        }

    }

    public function validateAccessToken(){

        $result = Model_Yauctiontoken::getAccessToken($this->user_id,$this->open_id);
        echo '<pre>';
        var_dump($result);
        echo '</pre>';
        $this->updated_at    = $result->updated_at;
        $this->refresh_token = $result->refresh_token;
        $this->access_token  = $result->access_token;
        $this->expiration    = $result->expiration;

        echo '<pre>';
        var_dump($this);
        echo '</pre>';

    }



    /**
     * \brief 無効なアクセストークンエラー確認メソッド
     *
     * @return	true or false
     */
    private function invalidToken($error)
    {
        if( preg_match( "/invalid_token/", $error ) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * \brief パラメータ関連エラー確認メソッド
     *
     * @return	true or false
     */
    private function invalidRequest($error)
    {
        if( preg_match( "/invalid_request/", $error ) ) {
            return true;
        } else {
            return false;
        }
    }


}