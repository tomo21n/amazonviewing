<?php
/**
 * AmazonのMWSのタスク
 *
 * @version 1.0.0
 */
namespace Fuel\Tasks;


class Mws {
    /**
     * Amazonの出品者サイトから取り扱っているブランドを取得
     *
     * @param  String $merchantid 出品者ID
     * @param  String $indexField ブランドのアルファベット文字
     */
    public function getBrandList()
    {

        require_once( PKGPATH .'mws/MarketplaceWebServiceProducts/Client.php');
        require_once( PKGPATH .'mws/MarketplaceWebServiceProducts/Model/ASINListType.php');
        require_once( PKGPATH .'mws/MarketplaceWebServiceProducts/Model/GetLowestOfferListingsForASINRequest.php');

        // Web APIのエンドポイント(japan)
        //$serviceUrl = "https://mws.amazonservices.jp/Products/2011-10-01";
        $serviceUrl = "https://mws.amazonservices.com/Products/2011-10-01";


        // proxy/retryの設定
        $config = array (
            'ServiceURL'    => $serviceUrl,
            'ProxyHost'     => null,
            'ProxyPort'     => -1,
            'MaxErrorRetry' => 3,
        );

        // Webサービスオブジェクトを生成
        $service = new MarketplaceWebServiceProducts_Client(
            AWS_ACCESS_KEY_ID,
            AWS_SECRET_ACCESS_KEY,
            'nanoappli.com_SampleApp',
            '1.0.0.0',
            $config);


        //--------------------------------
        // Webサービス情報の取得
        //--------------------------------
        //$service = getService();
        //単体テスト用(MarketplaceWebServiceProducts/Mockのxmlファイルを使用)
        //$service = new MarketplaceWebServiceProducts_Mock();


        //-------------------------------
        // リクエストパラメータのセット
        //-------------------------------
        $asinList = new MarketplaceWebServiceProducts_Model_ASINListType();
        $asinList->setASIN( array( '4478312141', '4797330058' ) );

        $request = new MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINRequest();
        $request->setSellerId( MERCHANT_ID );
        $request->setMarketplaceId( MARKETPLACE_ID );
        $request->setASINList( $asinList );

        echo '<pre>';
        var_dump($request);
        echo '</pre>';
        //-------------------------------
        // MWSリクエストAPIの実行
        //-------------------------------
        try {
            $response = $service->getLowestOfferListingsForASIN($request);
        } catch (MarketplaceWebServiceProducts_Exception $ex) {
            echo("Caught Exception: "       . $ex->getMessage()    . "¥n");
            echo("Response Status Code: "   . $ex->getStatusCode() . "¥n");
            echo("Error Code: "             . $ex->getErrorCode() . "¥n");
            echo("Error Type: "             . $ex->getErrorType() . "¥n");
            echo("Request ID: "             . $ex->getRequestId() . "¥n");
            echo("XML: "                    . $ex->getXML() . "¥n");
            echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "¥n");
        }

        //-------------------------------
        // API応答情報を表示する
        //-------------------------------
        echo '<pre>';
        var_dump($response);
        echo '</pre>';//showResponse( $response );

        //return Response::forge(View::forge('welcome/index'));


    }

} 