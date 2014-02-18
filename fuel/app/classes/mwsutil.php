<?php
/**
 * AmazonのMWSの
 *
 * @version 1.0.0
 */

define ('DATE_FORMAT', 'Y-m-d\TH:i:s\Z');

class Mwsutil {

    private $aws_access_key_id;
    private $aws_secret_access_key;
    private $merchant_id;
    private $marketplace_id;
    private $service;
    private $MerchantIdentifier;
    private $currency;


    function init($country,$request_type,$mwstype){

        $this->aws_access_key_id     = 'AKIAIKSZ2P3FRMZ52D5Q';
        $this->aws_secret_access_key = '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';
        $this->merchant_id           = 'A1L9IFR32CLTU8';
        $this->marketplace_id        = 'ATVPDKIKX0DER';

        $this->MerchantIdentifier    = 'M_SIGJAPAN_148586993';

        switch($request_type){
            case 'GetLowestOfferListingsForASINRequest':
                require_once( PKGPATH .'mws/MarketplaceWebServiceProducts/Client.php');
                require_once( PKGPATH .'mws/MarketplaceWebServiceProducts/Model/ASINListType.php');
                require_once( PKGPATH .'mws/MarketplaceWebServiceProducts/Model/GetLowestOfferListingsForASINRequest.php');
                break;
            case 'SubmitFeed':
                set_include_path(PKGPATH .'mws/');
                require_once( PKGPATH .'mws/MarketplaceWebService/Client.php');
                require_once( PKGPATH .'mws/MarketplaceWebService/Model/SubmitFeedRequest.php');
                require_once( PKGPATH .'mws/MarketplaceWebService/Model/IdList.php');
            default :
                break;
        }

        if($mwstype == 'Products'){
            // Web APIのエンドポイント
            switch($country){
                case 'JP':
                     $serviceUrl = "https://mws.amazonservices.jp/Products/2011-10-01";
                     break;
                case 'US':
                     $serviceUrl = "https://mws.amazonservices.com/Products/2011-10-01";
                    break;
                default :
                    $serviceUrl = "https://mws.amazonservices.jp/Products/2011-10-01";
                    break;
            }

            // proxy/retryの設定
            $config = array (
                'ServiceURL'    => $serviceUrl,
                'ProxyHost'     => null,
                'ProxyPort'     => -1,
                'MaxErrorRetry' => 3,
            );

            // Webサービスオブジェクトを生成
            $this->service = new MarketplaceWebServiceProducts_Client(
                $this->aws_access_key_id,
                $this->aws_secret_access_key,
                'worldviewing.com',
                '1.0.0.0',
                $config);

        }else{

            // Web APIのエンドポイント
            switch($country){
                case 'JP':
                    $serviceUrl = "https://mws.amazonservices.jp";
                    $this->currency = 'JPY';
                    break;
                case 'US':
                    $serviceUrl = "https://mws.amazonservices.com";
                    $this->currency = 'USD';
                    break;
                default :
                    $serviceUrl = "https://mws.amazonservices.jp";
                    $this->currency = 'JPY';
                    break;
            }

            // proxy/retryの設定
            $config = array (
                'ServiceURL'    => $serviceUrl,
                'ProxyHost'     => null,
                'ProxyPort'     => -1,
                'MaxErrorRetry' => 3,
            );

            // Webサービスオブジェクトを生成
            $this->service = new MarketplaceWebService_Client(
                $this->aws_access_key_id,
                $this->aws_secret_access_key,
                $config,
                'world-viewing.com',
                '1.0.0.0'
            );
        }


    }

    public function GetLowestOfferListingsForASINRequest($asinlist){

        $asinListModel = new MarketplaceWebServiceProducts_Model_ASINListType();
        $asinListModel->setASIN( $asinlist );

        $request = new MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINRequest();
        $request->setSellerId( $this->merchant_id );
        $request->setMarketplaceId( $this->marketplace_id );
        $request->setASINList( $asinListModel );

        //-------------------------------
        // MWSリクエストAPIの実行
        //-------------------------------
        try {
            $response = $this->service->getLowestOfferListingsForASIN($request);
        } catch (MarketplaceWebServiceProducts_Exception $ex) {
            echo("Caught Exception: "       . $ex->getMessage()    . "¥n");
            echo("Response Status Code: "   . $ex->getStatusCode() . "¥n");
            echo("Error Code: "             . $ex->getErrorCode() . "¥n");
            echo("Error Type: "             . $ex->getErrorType() . "¥n");
            echo("Request ID: "             . $ex->getRequestId() . "¥n");
            echo("XML: "                    . $ex->getXML() . "¥n");
            echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "¥n");
        }

        if($response){

            return($this->responseGetLowestOfferListingsForASINResultList($response)) ;

        }else{

            return FALSE;
        }

    }

    public function responseGetLowestOfferListingsForASINResultList($response){

        $result = array();
        //-------------------------------
        // API応答情報を表示する
        //-------------------------------
        $getLowestOfferListingsForASINResultList = $response->getGetLowestOfferListingsForASINResult();
        foreach($getLowestOfferListingsForASINResultList as $getLowestOfferListingsForASINResult){
            // 検索結果
            if ($getLowestOfferListingsForASINResult->isSetStatus()) {
                $status = $getLowestOfferListingsForASINResult->getStatus();
            }

            //----------------
            // 商品の情報
            //----------------
            if($status == 'Success'){
                $productinfo = array();
                if ($getLowestOfferListingsForASINResult->isSetASIN()) {
                    $productinfo += array('ASIN'=> $getLowestOfferListingsForASINResult->getASIN());
                }
                if ($getLowestOfferListingsForASINResult->isSetProduct()) {
                    $product = $getLowestOfferListingsForASINResult->getProduct();
                    if ($product->isSetSalesRankings()) {
                        $salesRankings = $product->getSalesRankings();
                        $salesRankList = $salesRankings->getSalesRank();
                        foreach ($salesRankList as $salesRank) {
                            if ($salesRank->isSetProductCategoryId()) {
                                $productinfo += array('ProductCategoryId'=>$salesRank->getProductCategoryId());
                            }
                            if ($salesRank->isSetRank()) {
                                $productinfo += array('Rank'=>$salesRank->getRank()) ;
                            }
                        }
                    }

                    //-------------------
                    // 出品情報
                    //-------------------
                    if ($product->isSetLowestOfferListings()) {
                        $lowestOfferListings = $product->getLowestOfferListings();
                        $lowestOfferListingList = $lowestOfferListings->getLowestOfferListing();

                        //-----------------------------------------
                        // 取得した全出品情報を表示するまで繰り返し
                        //-----------------------------------------
                        foreach ($lowestOfferListingList as $lowestOfferListing) {
                            $priceinfo = array();
                            //------------------
                            // 商品の状態
                            //------------------
                            if ($lowestOfferListing->isSetQualifiers()) {
                                $qualifiers = $lowestOfferListing->getQualifiers();
                                if ($qualifiers->isSetItemCondition()) {
                                    $condName    = $qualifiers->getItemCondition() ;
                                    $subCondName = "";
                                    if ($qualifiers->isSetItemSubcondition()) {
                                        $subCondName = $qualifiers->getItemSubcondition();
                                    }
                                    //コンディション
                                    $priceinfo += array('Condition'=> $condName);
                                    //サブコンディション
                                    $priceinfo += array('SubCondition' => $subCondName);
                                }


                                if ($qualifiers->isSetFulfillmentChannel()) {
                                    //出荷元
                                    $priceinfo += array('FulfillmentChannel' => $qualifiers->getFulfillmentChannel());
                                }
                                if ($qualifiers->isSetShipsDomestically()) {
                                    //国内より発送
                                    $priceinfo += array('ShipsDomestically' => $qualifiers->getShipsDomestically());
                                }
                                if ($qualifiers->isSetShippingTime()) {
                                    $shippingTime = $qualifiers->getShippingTime();
                                    if ($shippingTime->isSetMax()) {
                                        //発送日数(最大)
                                        $priceinfo += array('ShippingTime' => $shippingTime->getMax());
                                    }
                                }

                                if ($lowestOfferListing->isSetSellerFeedbackCount()) {
                                    //フィードバック数
                                    $priceinfo += array('SellerFeedbackCount' => $lowestOfferListing->getSellerFeedbackCount());

                                    if ($qualifiers->isSetSellerPositiveFeedbackRating()) {
                                        //高評価
                                        $priceinfo += array('SellerPositiveFeedbackRating' => $qualifiers->getSellerPositiveFeedbackRating());
                                    }
                                }
                            }
                            if ($lowestOfferListing->isSetNumberOfOfferListingsConsidered()) {
                                //出品数
                                $priceinfo += array('NumberOfOfferListingsConsidered' => $lowestOfferListing->getNumberOfOfferListingsConsidered());
                            }

                            //------------------
                            // 価格情報
                            //------------------
                            if ($lowestOfferListing->isSetPrice()) {
                                $price1 = $lowestOfferListing->getPrice();
                                if ($price1->isSetLandedPrice()) {
                                    //総額
                                    $priceinfo += array('LandedPrice' => $this->getPriceName( $price1->getLandedPrice()));
                                }
                                if ($price1->isSetShipping()) {
                                    //送料
                                    $priceinfo += array('Shipping' => $this->getPriceName( $price1->getShipping() ));
                                }
                            }

                            $productinfo['PriceInfo'][]= $priceinfo;
                        }
                    }
                }
                $result[] =  $productinfo;
            }

            if ($getLowestOfferListingsForASINResult->isSetError()) {
                echo("エラーが発生しました\n");
                $error = $getLowestOfferListingsForASINResult->getError();
                if ($error->isSetType()) {
                    echo("Type:" . $error->getType() . "\n");
                }
                if ($error->isSetCode()) {
                    echo("Code:" . $error->getCode() . "\n");
                }
                if ($error->isSetMessage()) {
                    echo("Message:" . $error->getMessage() . "\n");
                }
            }
        }

        return $result;

    }



    //****************************************************************************
    // Function		: getPriceName
    // Description	: 価格(通貨単位付き)を取得する
    //****************************************************************************
    public function getPriceName( $inData ) {
        $outStr = "";

        // 通貨単位のセット
        if ( $inData->isSetCurrencyCode() ) {
            switch ( $inData->getCurrencyCode() ) {
                case "JPY":
                    $outStr .= "¥¥";
                    break;
                case "USD":
                    $outStr .= "$";
                    break;
                default:
                    $outStr .= "(" . $inData->getCurrencyCode() . ")";
                    break;
            }
        }

        // 金額のセット
        if ($inData->isSetAmount()) {
            $outStr .= sprintf( "%.0lf", $inData->getAmount() );
        }

        return $outStr;
    }

    public function makeFeedInventoryAvailability($updateitems){
        // インスタンスの生成
        $dom = new DomDocument('1.0', 'UTF-8');
        $AmazonEnvelope = $dom->appendChild($dom->createElement('AmazonEnvelope'));
        $Header = $AmazonEnvelope->appendChild($dom->createElement('Header'));
        $Header->appendChild($dom->createElement('DocumentVersion','1.02'));
        $Header->appendChild($dom->createElement('MerchantIdentifier',$this->MerchantIdentifier));
        $AmazonEnvelope->appendChild($dom->createElement('MessageType','Inventory'));
        foreach($updateitems as $key => $updateitem){
            $Message = $AmazonEnvelope->appendChild($dom->createElement('Message'));
            $Message->appendChild($dom->createElement('MessageID',$key + 1));
            $Message->appendChild($dom->createElement('OperationType','Update'));
            $Inventory = $Message->appendChild($dom->createElement('Inventory'));
            $Inventory->appendChild($dom->createElement('SKU',$updateitem['SKU']));
            $Inventory->appendChild($dom->createElement('Quantity',$updateitem['Quantity']));
        }

        $dom->formatOutput = true;
        $feed = $dom->saveXML();

        return $feed;
    }

    public function makeFeedProductPricing($updateitems){
        // インスタンスの生成
        $dom = new DomDocument('1.0', 'UTF-8');
        $AmazonEnvelope = $dom->appendChild($dom->createElement('AmazonEnvelope'));
        $Header = $AmazonEnvelope->appendChild($dom->createElement('Header'));
        $Header->appendChild($dom->createElement('DocumentVersion','1.02'));
        $Header->appendChild($dom->createElement('MerchantIdentifier',$this->MerchantIdentifier));
        $AmazonEnvelope->appendChild($dom->createElement('MessageType','Inventory'));
        foreach($updateitems as $key => $updateitem){
            $Message = $AmazonEnvelope->appendChild($dom->createElement('Message'));
            $Message->appendChild($dom->createElement('MessageID',$key + 1));
            $Price = $Message->appendChild($dom->createElement('Price'));
            $Price->appendChild($dom->createElement('SKU',$updateitem['SKU']));
            $StandardPrice = $Price->appendChild($dom->createElement('StandardPrice',$updateitem['StandardPrice']));
            $StandardPrice->setAttribute('currency',$this->currency);
        }

        $dom->formatOutput = true;
        $feed = $dom->saveXML();

        return $feed;
    }

    public function makeFlatFileInvloader(){

        $data_title = array('sku','product-id','product-id-type','price','minimum-seller-allowed-price','maximum-seller-allowed-price','item-condition','quantity','add-delete','will-ship-internationally','expedited-shipping','standard-plus','item-note','fulfillment-center-id','product-tax-code','leadtime-to-ship');
        $data = array(
            array('sku' => 'TEST-B001EWPYXG',
                'product-id' => 'B001EWPYXG',
                'product-id-type' => '1',
                'price'=>'100',
                'minimum-seller-allowed-price' => '50',
                'maximum-seller-allowed-price' => '200',
                'item-condition' => '11',
                'quantity' => '1',
                'add-delete' => 'a',
                'will-ship-internationally' => '1',
                'expedited-shipping' => 'N',
                'standard-plus' => 'N',
                'item-note' => 'Brand New, Worldwide Shipping Quickly . Buy with Confidence! Thank you for your order.',
                'fulfillment-center-id' => '',
                'product-tax-code' => '',
                'leadtime-to-ship' => ''),
            array('sku' => 'TEST-B00AM7B1EA',
                'product-id' => 'B00AM7B1EA',
                'product-id-type' => '1',
                'price'=>'101',
                'minimum-seller-allowed-price' => '50',
                'maximum-seller-allowed-price' => '200',
                'item-condition' => '11',
                'quantity' => '1',
                'add-delete' => 'a',
                'will-ship-internationally' => '1',
                'expedited-shipping' => 'N',
                'standard-plus' => 'N',
                'item-note' => 'Brand New, Worldwide Shipping Quickly . Buy with Confidence! Thank you for your order.',
                'fulfillment-center-id' => '',
                'product-tax-code' => '',
                'leadtime-to-ship' => ''),
            array('sku' => 'TEST-B005E2YL4U',
                'product-id' => 'B005E2YL4U',
                'product-id-type' => '1',
                'price'=>'102',
                'minimum-seller-allowed-price' => '50',
                'maximum-seller-allowed-price' => '200',
                'item-condition' => '11',
                'quantity' => '1',
                'add-delete' => 'a',
                'will-ship-internationally' => '1',
                'expedited-shipping' => 'N',
                'standard-plus' => 'N',
                'item-note' => 'Brand New, Worldwide Shipping Quickly . Buy with Confidence! Thank you for your order.',
                'fulfillment-center-id' => '',
                'product-tax-code' => '',
                'leadtime-to-ship' => '')
        );


        $feed = 'f';
        return $feed;

    }


    public function SubmitFeed($feed,$FeedType)
    {

        //--------------------------------
        // Webサービス情報の取得
        //--------------------------------
        $feedHandle = @fopen('php://memory', 'rw+');
        fwrite($feedHandle, $feed);
        rewind($feedHandle);
        $marketplaceIdArray = array("Id" => array($this->marketplace_id));

        $request = new MarketplaceWebService_Model_SubmitFeedRequest();
        $request->setMerchant($this->merchant_id);
        $request->setMarketplaceIdList($marketplaceIdArray);
        $request->setFeedType($FeedType);
        $request->setContentMd5(base64_encode(md5(stream_get_contents($feedHandle), true)));
        rewind($feedHandle);
        $request->setPurgeAndReplace(false);
        $request->setFeedContent($feedHandle);

        rewind($feedHandle);

        try {
            $response = $this->service->submitFeed($request);

            if ($response->isSetSubmitFeedResult()) {
                $result = array();
                $submitFeedResult = $response->getSubmitFeedResult();
                if ($submitFeedResult->isSetFeedSubmissionInfo()) {
                    $feedSubmissionInfo = $submitFeedResult->getFeedSubmissionInfo();
                    if ($feedSubmissionInfo->isSetFeedSubmissionId())
                    {
                        $result += array('FeedSubmissionId' =>$feedSubmissionInfo->getFeedSubmissionId());
                    }
                    if ($feedSubmissionInfo->isSetFeedType())
                    {
                        $result += array('FeedType' =>$feedSubmissionInfo->getFeedType());
                    }
                    if ($feedSubmissionInfo->isSetSubmittedDate())
                    {
                        $result += array('SubmittedDate' =>$feedSubmissionInfo->getSubmittedDate()->format(DATE_FORMAT));
                    }
                    if ($feedSubmissionInfo->isSetFeedProcessingStatus())
                    {
                        $result += array('FeedProcessingStatus' =>$feedSubmissionInfo->getFeedProcessingStatus());
                    }
                    if ($feedSubmissionInfo->isSetStartedProcessingDate())
                    {
                        //echo("                    StartedProcessingDate<br />");
                        //echo("                        " . $feedSubmissionInfo->getStartedProcessingDate()->format(DATE_FORMAT) . "<br />");
                    }
                    if ($feedSubmissionInfo->isSetCompletedProcessingDate())
                    {
                        //echo("                    CompletedProcessingDate<br />");
                        //echo("                        " . $feedSubmissionInfo->getCompletedProcessingDate()->format(DATE_FORMAT) . "<br />");
                    }
                }
            }
            /*if ($response->isSetResponseMetadata()) {
                echo("            ResponseMetadata<br />");
                $responseMetadata = $response->getResponseMetadata();
                if ($responseMetadata->isSetRequestId())
                {
                    echo("                RequestId<br />");
                    echo("                    " . $responseMetadata->getRequestId() . "<br />");
                }
            }

            echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "<br />");
            */
        } catch (MarketplaceWebService_Exception $ex) {
            echo("Caught Exception: " . $ex->getMessage() . "¥n");
            echo("Response Status Code: " . $ex->getStatusCode() . "¥n");
            echo("Error Code: " . $ex->getErrorCode() . "¥n");
            echo("Error Type: " . $ex->getErrorType() . "¥n");
            echo("Request ID: " . $ex->getRequestId() . "¥n");
            echo("XML: " . $ex->getXML() . "¥n");
            echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "¥n");
        }
//invokeSubmitFeed($service, $request);

        @fclose($feedHandle);

        return $result;
    }

} 