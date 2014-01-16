<?php
/**
 * User: tomoki
 * Date: 2013/10/06
 * Amazon PAAPI Class
 */

class Paapi {

    public static function set_config($awsaccesskeyid,$secret_key,$associateid,$locale = 'JP',$response_group = null,$ecs_version = null){

        $config = array();

        //ECSバージョン
        if(is_null($ecs_version)){
            $config['ecs_version'] = '2011-08-02';
        }
        //レスポンスバージョン
        if(is_null($response_group)){
            $config['response_group'] = 'ItemAttributes,SalesRank,OfferSummary,Images';
        }

        //URL
        $baseurl['US']='http://webservices.amazon.com/onca/xml'; //US
        $baseurl['CA']='http://ecs.amazonaws.ca/onca/xml';       //CA
        $baseurl['CN']='http://webservices.amazon.cn/onca/xml';  //CN
        $baseurl['DE']='http://ecs.amazonaws.de/onca/xml';       //DE
        $baseurl['ES']='http://webservices.amazon.es/onca/xml';  //ES
        $baseurl['FR']='http://ecs.amazonaws.fr/onca/xml';       //FR
        $baseurl['IT']='http://webservices.amazon.it/onca/xml';  //IT
        $baseurl['JP']='http://ecs.amazonaws.jp/onca/xml';       //JP
        $baseurl['UK']='http://ecs.amazonaws.co.uk/onca/xml';    //UK

        $config['url'] = $baseurl[$locale];

        //アソシエイトID
        $associateid_base['US']='rju-20'; //US 審査OK
        $associateid_base['CA']='amazo028-20'; //CA 審査OK
        $associateid_base['CN']='sedotomsignum-23';//CN
        $associateid_base['DE']='amazo013-21';//DE 審査OK
        $associateid_base['ES']='worldcompa01-21';//ES
        $associateid_base['FR']='amazo00-21';//FR
        $associateid_base['IT']='amazo07-21';//IT
        $associateid_base['JP']='sedotomsignum-22';//JP 審査OK
        $associateid_base['UK']='worldv-21';//UK

        if(is_null($associateid)){
            $config['associateid'] = $associateid_base[$locale];
        }else{
            $config['associateid'] = $associateid;
        }

        $config['awsaccesskeyid'] = $awsaccesskeyid;
        $config['secret_key']     = $secret_key;
        $config['locale']         = $locale;

        return $config;

    }

    //------------------------------------------------------//
    // 世界のAmazon検索
    //------------------------------------------------------//
    public static function itemlookup_asin($asin,$config){

        $result = array();

        if($config['awsaccesskeyid']=='') die('AWSAccessKeyId is not set.');

        //共通のパラメータ
        $url = $config['url'];
        $url.="?Service=AWSECommerceService&AWSAccessKeyId=".$config['awsaccesskeyid']."&Version=".$config['ecs_version']."&AssociateTag=".$config['associateid'];

        $request=$url
            .'&ItemId='.$asin//ItemIdはカンマで区切って10個まで複数指定できる
            .'&Operation=ItemLookup'
            .'&MerchantId=All'
            .'&ResponseGroup='.$config['response_group'];

        //ItemLookupリクエストで有効なレスポンス・グループは、Accessories, AlternateVersions, BrowseNodes, Collections, EditorialReview, Images, ItemAttributes, ItemIds, Large, ListmaniaLists, Medium, MerchantItemAttributes, OfferFull, OfferListings, OfferSummary, Offers, PromotionDetails, PromotionSummary, PromotionalTag, RelatedItems, Request, Reviews, SalesRank, SearchBins, SearchInside, ShippingCharges, ShippingOptions, Similarities, Small, Subjects, Tags, TagsSummary, Tracks, VariationImages, VariationMatrix, VariationMinimum, VariationOffers, VariationSummary, Variationsなどです。

        //キーワードで書籍を検索
        $request=Paapi::add_signature($request,$config['secret_key']);
        //問い合わせ、結果のXMLを処理可能な形に変換
        try {
            ob_start();
            $request_result=simplexml_load_string(file_get_contents($request));
            $warning = ob_get_contents();
            ob_end_clean();
            //Warningがあれば例外を投げる
            if ($warning) {
                throw new Exception($warning);
            }
        } catch (Exception $e) {
            Log::debug( 'エラーが発生しました.');
            Log::debug( 'メッセージ：' . $e->getMessage() );
            Log::debug( '発生箇所：' . $e->getFile() . ' 行：' . $e->getLine());
            Log::debug( 'デバッグ：');
            Log::debug( $e->getTraceAsString());

            return false;
        }
        if(@!$request_result->Items->Request->Errors->Error->Message){
            foreach($request_result->Items->Item as $item){//結果のItemを一つずつ処理する
                $result['locale']=$config['locale'];
                $result['ean']=(String)$item->ItemAttributes->EAN;
                $result['asin']=(String)$item->ASIN;
                $result['image_S']=(String)$item->SmallImage->URL;
                $result['image_M']=(String)$item->MediumImage->URL;
                $result['artist']=(String)$item->ItemAttributes->Artist;
                $result['brand']=(String)$item->ItemAttributes->Brand;
                $result['author']=(String)$item->ItemAttributes->Author;
                $result['searchindex'] = (String)$item->ItemAttributes->ProductGroup;
                $result['title']=mb_convert_encoding((String)$item->ItemAttributes->Title, "UTF-8","auto");
                $result['url']=(String)$item->DetailPageURL;
                $result['fixedprice'] = (Integer)$item->ItemAttributes->ListPrice->Amount;
                $result['releasedate']=(String)$item->ItemAttributes->ReleaseDate;
                $result['rank']=(Integer)$item->SalesRank;
                $result['totalnew']=(Integer)$item->OfferSummary->TotalNew;
                $result['totalused']=(Integer)$item->OfferSummary->TotalUsed;
                $result['totalcollection']=(Integer)$item->OfferSummary->TotalCollectible;
                $result['lowestusedprice'] =(Integer)$item->OfferSummary->LowestUsedPrice->Amount;
                $result['lowestnewprice'] =(Integer)$item->OfferSummary->LowestNewPrice->Amount;
                //サイズ変換
                $result['length'] = round($item->ItemAttributes->PackageDimensions->Length * 2.54 / 100,1);
                $result['width'] = round($item->ItemAttributes->PackageDimensions->Width * 2.54 / 100,1);
                $result['height'] = round($item->ItemAttributes->PackageDimensions->Height * 2.54 / 100,1);
                $result['volume'] = $result['length'] * $result['width'] * $result['height'];
                $result['weight'] = round($item->ItemAttributes->PackageDimensions->Weight * 453.59237 /100,1);
            }

            if(!isset($result['title'])){
                \Log::debug("PAAPI ERROR:".$result);
             }
        }else{
            \Log::debug($request_result->Items->Request->Errors->Error->Message);
            return false;
        }

        return $result;

    }

    //------------------------------------------------------//
    // 世界のAmazon検索(EAN)
    //------------------------------------------------------//
    public static function get_world_amazon_ean($ean,$config){

        if($config['awsaccesskeyid']=='') die('AWSAccessKeyId is not set.');

        //共通のパラメータ
        $url = $config['url'];
        $url.="?Service=AWSECommerceService&AWSAccessKeyId=".$config['awsaccesskeyid']."&Version=".$config['ecs_version']."&AssociateTag=".$config['asociateid'];

        $request=$url
            .'&IdType=EAN'//IdTypeはJANでなく「EAN」
            .'&ItemId='.$ean//ItemIdはカンマで区切って10個まで複数指定できる
            .'&Operation=ItemLookup'
            .'&SearchIndex=All'//検索対象は「Books」（IdTypeがデフォルト(ASIN)でないときに必要）
            .'&MerchantId=All'
            .'&ResponseGroup='.$config['response_group'];

        //ItemLookupリクエストで有効なレスポンス・グループは、Accessories, AlternateVersions, BrowseNodes, Collections, EditorialReview, Images, ItemAttributes, ItemIds, Large, ListmaniaLists, Medium, MerchantItemAttributes, OfferFull, OfferListings, OfferSummary, Offers, PromotionDetails, PromotionSummary, PromotionalTag, RelatedItems, Request, Reviews, SalesRank, SearchBins, SearchInside, ShippingCharges, ShippingOptions, Similarities, Small, Subjects, Tags, TagsSummary, Tracks, VariationImages, VariationMatrix, VariationMinimum, VariationOffers, VariationSummary, Variationsなどです。

        //キーワードで書籍を検索
        $request=Paapi::add_signature($request,$config['secret_key']);
        //問い合わせ、結果のXMLを処理可能な形に変換
        try {
            ob_start();
            //warningが出るコード
            $request_result=simplexml_load_string(file_get_contents($request));
            $warning = ob_get_contents();
            ob_end_clean();
            //Warningがあれば例外を投げる
            if ($warning) {
                throw new Exception($warning);
            }
        } catch (Exception $e) {
            Log::debug( 'エラーが発生しました.');
            Log::debug( 'メッセージ：' . $e->getMessage() );
            Log::debug( '発生箇所：' . $e->getFile() . ' 行：' . $e->getLine());
            Log::debug( 'デバッグ：');
            Log::debug( $e->getTraceAsString());

            return false;
        }

        if(!$request_result->Items->Request->Errors->Error->Message){
            foreach($request_result->Items->Item as $item){//結果のItemを一つずつ処理する
                //var_dump($item);
                $result['locale']=$config['locale'];
                $result['ean']=(String)$item->ItemAttributes->EAN;
                $result['asin']=(String)$item->ASIN;
                $result['image_S']=(String)$item->SmallImage->URL;
                $result['image_M']=(String)$item->MediumImage->URL;
                $result['artist']=(String)$item->ItemAttributes->Artist;
                $result['title']=mb_convert_encoding((String)$item->ItemAttributes->Title, "UTF-8","auto");
                $result['url']=(String)$item->DetailPageURL;
                $result['fixedprice'] = (Integer)$item->ItemAttributes->ListPrice->Amount;
                $result['releasedate']=(String)$item->ItemAttributes->ReleaseDate;
                $result['rank']=(Integer)$item->SalesRank;
                $result['totalnew']=(Integer)$item->OfferSummary->TotalNew;
                $result['totalused']=(Integer)$item->OfferSummary->TotalUsed;
                $result['totalcollection']=(Integer)$item->OfferSummary->TotalCollectible;
                $result['lowestusedprice'] =(Integer)$item->OfferSummary->LowestUsedPrice->Amount;
                $result['lowestnewprice'] =(Integer)$item->OfferSummary->LowestNewPrice->Amount;
                //サイズ変換
                $result['length'] = round($item->ItemAttributes->PackageDimensions->Length * 2.54 / 100,1);
                $result['width'] = round($item->ItemAttributes->PackageDimensions->Width * 2.54 / 100,1);
                $result['height'] = round($item->ItemAttributes->PackageDimensions->Height * 2.54 / 100,1);
                $result['volume'] = $result['length'] * $result['width'] * $result['height'];
                $result['weight'] = round($item->ItemAttributes->PackageDimensions->Weight * 453.59237 /100,1);
            }

            return $result;
        }else{
            return false;
        }

    }


    //------------------------------------------------------//
    // 世界のAmazonSearch
    //------------------------------------------------------//
    public static function itemSearch($config){

        $result = array();

        if($config['awsaccesskeyid']=='') die('AWSAccessKeyId is not set.');

        //共通のパラメータ
        $url = $config['url'];
        $url.="?Service=AWSECommerceService&AWSAccessKeyId=".$config['awsaccesskeyid']."&Version=".$config['ecs_version']."&AssociateTag=".$config['associateid'];

        $request=$url
            .'&Operation=ItemSearch'
            .'&MerchantId=A1L9IFR32CLTU8'
            .'&Keywords=Java'
            .'&SearchIndex=Toys'
            .'&ResponseGroup='.$config['response_group'];

        //ItemLookupリクエストで有効なレスポンス・グループは、Accessories, AlternateVersions, BrowseNodes, Collections, EditorialReview, Images, ItemAttributes, ItemIds, Large, ListmaniaLists, Medium, MerchantItemAttributes, OfferFull, OfferListings, OfferSummary, Offers, PromotionDetails, PromotionSummary, PromotionalTag, RelatedItems, Request, Reviews, SalesRank, SearchBins, SearchInside, ShippingCharges, ShippingOptions, Similarities, Small, Subjects, Tags, TagsSummary, Tracks, VariationImages, VariationMatrix, VariationMinimum, VariationOffers, VariationSummary, Variationsなどです。

        //キーワードで書籍を検索
        $request=Paapi::add_signature($request,$config['secret_key']);
        //問い合わせ、結果のXMLを処理可能な形に変換
        try {
            ob_start();
            $request_result=simplexml_load_string(file_get_contents($request));
            $warning = ob_get_contents();
            ob_end_clean();
            //Warningがあれば例外を投げる
            if ($warning) {
                throw new Exception($warning);
            }
        } catch (Exception $e) {
            Log::debug( 'エラーが発生しました.');
            Log::debug( 'メッセージ：' . $e->getMessage() );
            Log::debug( '発生箇所：' . $e->getFile() . ' 行：' . $e->getLine());
            Log::debug( 'デバッグ：');
            Log::debug( $e->getTraceAsString());

            return false;
        }

        var_dump($request_result);
        /*if(@!$request_result->Items->Request->Errors->Error->Message){
            foreach($request_result->Items->Item as $item){//結果のItemを一つずつ処理する
                $result['locale']=$config['locale'];
                $result['ean']=(String)$item->ItemAttributes->EAN;
                $result['asin']=(String)$item->ASIN;
                $result['image_S']=(String)$item->SmallImage->URL;
                $result['image_M']=(String)$item->MediumImage->URL;
                $result['artist']=(String)$item->ItemAttributes->Artist;
                $result['brand']=(String)$item->ItemAttributes->Brand;
                $result['author']=(String)$item->ItemAttributes->Author;
                $result['searchindex'] = (String)$item->ItemAttributes->ProductGroup;
                $result['title']=mb_convert_encoding((String)$item->ItemAttributes->Title, "UTF-8","auto");
                $result['url']=(String)$item->DetailPageURL;
                $result['fixedprice'] = (Integer)$item->ItemAttributes->ListPrice->Amount;
                $result['releasedate']=(String)$item->ItemAttributes->ReleaseDate;
                $result['rank']=(Integer)$item->SalesRank;
                $result['totalnew']=(Integer)$item->OfferSummary->TotalNew;
                $result['totalused']=(Integer)$item->OfferSummary->TotalUsed;
                $result['totalcollection']=(Integer)$item->OfferSummary->TotalCollectible;
                $result['lowestusedprice'] =(Integer)$item->OfferSummary->LowestUsedPrice->Amount;
                $result['lowestnewprice'] =(Integer)$item->OfferSummary->LowestNewPrice->Amount;
                //サイズ変換
                $result['length'] = round($item->ItemAttributes->PackageDimensions->Length * 2.54 / 100,1);
                $result['width'] = round($item->ItemAttributes->PackageDimensions->Width * 2.54 / 100,1);
                $result['height'] = round($item->ItemAttributes->PackageDimensions->Height * 2.54 / 100,1);
                $result['volume'] = $result['length'] * $result['width'] * $result['height'];
                $result['weight'] = round($item->ItemAttributes->PackageDimensions->Weight * 453.59237 /100,1);
            }

            if(!isset($result['title'])){
                \Log::debug("PAAPI ERROR:".$result);
            }
        }else{
            \Log::debug($request_result->Items->Request->Errors->Error->Message);
            return false;
        }*/

        return $result;

    }

    //シグネチャ追加
    public static function add_signature($url,$secret_key){
        $ret_char = "\n";
        $url_array = parse_url($url);
        parse_str($url_array["query"], $param_array);
        $param_array["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
        ksort($param_array);
        $str = "GET".$ret_char.$url_array["host"].$ret_char.$url_array["path"].$ret_char;
        $str_param = "";
        while( list($key, $value) = each($param_array) ){
            $str_param =
                $str_param.strtr($key, "_", ".")."=".rawurlencode($value)."&";
        }
        $str = $str.substr($str_param, 0, strlen($str_param)-1);
        $signature = base64_encode( hash_hmac("sha256", $str, $secret_key, true) );
        $url_sig =
            "http://".$url_array["host"].$url_array["path"]."?".
            $str_param."Signature=".rawurlencode($signature);
        return $url_sig;
    }

    public static function storeresult($result){

        $item = Model_Amazonitems::find($result['asin']);

        if($result['locale'] === 'JP'){
            $item->title = $result['title'];
            $item->image_s = $result['image_S'];
            $item->searchindex = $result['searchindex'];
            $item->jp_survey_dtm = date("Y-m-d H:i:s");
            $item->jp_new_number = $result['totalnew'];
            $item->jp_new_price = $result['lowestnewprice'];
            $item->jp_used_number = $result['totalused'];
            $item->jp_used_price = $result['lowestusedprice'];
            $item->jp_url = $result['url'];

        }elseif($result['locale'] === 'US'){

            $item->us_survey_dtm = date("Y-m-d H:i:s");
            $item->us_new_number = $result['totalnew'];
            $item->us_new_price = $result['lowestnewprice'];
            $item->us_used_number = $result['totalused'];
            $item->us_used_price = $result['lowestusedprice'];
            $item->us_url = $result['url'];
        }

        if ($item->save())
        {
            Log::info("Data Save OK ASIN:".$result['asin']);
        }

        else
        {
            Log::error("Data Save Error ASIN:".$result['asin']);
        }

    }


    public static function change_assciate_tag($url){
        // パターン
        $pattern = '/tag%3D.*-22%26/';
        // 置換後の文字列
        $replacement = 'tag%3Dsedotomsignum-22%26';
        // 置換
        $text= preg_replace($pattern,$replacement,$url);
        // パターン
        $pattern2 = '/SubscriptionId%3D....................%26/';
        // 置換後の文字列
        $replacement2 = 'SubscriptionId%3DAKIAJV4E4VX7S4C72LQA%26';
        // 置換
        $text2= preg_replace($pattern2,$replacement2,$text);
        // 出力
        return $text2;
    }


    public static function check_access_key($awsaccesskeyid,$secret_key,$associateid){

        $config = Paapi::set_config($awsaccesskeyid,$secret_key,$associateid);

        $url = $config['url'];
        $url.="?Service=AWSECommerceService&AWSAccessKeyId=".$config['awsaccesskeyid']."&Version=".$config['ecs_version']."&AssociateTag=".$config['associateid'];
        $request=$url
            .'&ItemId='."B005JWX5PQ"//ItemIdはカンマで区切って10個まで複数指定できる
            .'&Operation=ItemLookup'
            .'&MerchantId=All'
            .'&ResponseGroup='.$config['response_group'];
        $request=Paapi::add_signature($request,$config['secret_key']);
        try {
            ob_start();
            $request_result=simplexml_load_string(file_get_contents($request));
            $warning = ob_get_contents();
            ob_end_clean();
            //Warningがあれば例外を投げる
            if ($warning) {
                throw new Exception($warning);
            }
        } catch (Exception $e) {
            Log::debug( 'エラーが発生しました.');
            Log::debug( 'メッセージ：' . $e->getMessage() );
            Log::debug( '発生箇所：' . $e->getFile() . ' 行：' . $e->getLine());
            Log::debug( 'デバッグ：');
            Log::debug( $e->getTraceAsString());

            return 'アクセスキーID、シークレットキー、アソシエイトタグが正しく設定されていません';
        }

        if(isset($request_result->Items->Request->Errors->Error->Message)){
            return $request_result->Items->Request->Errors->Error->Message;
        }else{
            return 1 ;
        }


    }

}