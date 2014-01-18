<?php
namespace Fuel\Tasks;
use Oil\Exception;

/**
 * Amazonのサイトのスクレイピングするためのクラス
 *
 * @version 1.0.0
 */
class Amazonscrape
{

    public function run($message = 'Hello!')
    {

    }

    /**
     * Amazonの出品者サイトから取り扱っているブランドを取得
     *
     * @param  String $merchantid 出品者ID
     * @param  String $indexField ブランドのアルファベット文字
     */
    public function getBrandList($merchantid = null,$indexField = null)
    {
        if(is_null($merchantid)||is_null($indexField)){
            $merchantid = 'A2SMOY8HQH0NHJ';
            $indexField = 'a';
        }

        exec('casperjs '.APPPATH.'getBrandList.js '.$merchantid." ". $indexField, $retval);

        if($retval){
            foreach($retval as $url){

                try{
                    $baseurl = 'http://www.amazon.com'.$url;

                    $m_brand = \Model_Brand::forge(array(
                        'merchant_id' => $merchantid,
                        'alphabet' => $indexField,
                        'brand_url' => $baseurl,
                    ));

                    if ($m_brand and $m_brand->save())
                    {
                        \Log::DEBUG('DB SUCCESS getBrandList URL:'.$baseurl);

                    }
                    else
                    {
                        \Log::ERROR('DB ERROR getBrandList URL:'.$baseurl);
                    }
                }catch (\Database_Exception $e){

                    \Log::ERROR('DB EXCEPTION getBrandList DETAIL:'.$e);

                }
            }
        }else{

            \Log::ERROR('SCRAPE ERROR getBrandList MERCHANT_ID:'.$merchantid);
        }

    }

    /**
     * Amazonの出品者のブランドごとのページから出品しているASINを取得
     *
     */
    public function getAsin(){

        $baseurl = \Model_Brand::query()->where('check_date','=',null)->order_by('updated_at', 'desc')->get_one();

        $page = 0;

        do{
            $page = $page + 1;
            $url =$baseurl->brandurl.'&page='.$page;
            $asinlist = null;

            exec("casperjs ".APPPATH."getItemList.js '".$url."'", $resultlist);

            $maxpage = 1;
            $firstline = true;

            if($resultlist){
                foreach($resultlist as $item){

                    if($firstline)
                    {
                        if(preg_match("/^Showing [0-9]+ - [0-9]+ of [0-9]+ Results$/",$item)){

                            if(preg_match("/of [0-9]+ Results$/",$item,$match)){

                                $replaceText = str_replace("of ", "", $match);
                                $resultcount = str_replace(" Results", "", $replaceText);
                                $intresultcount = intval($resultcount[0]);
                                $maxpage = ceil($intresultcount/24);

                                if($intresultcount > 1000){
                                    break;
                                }

                            }

                        }else{
                            $maxpage = 1;
                        }

                        $firstline = false;

                    }else{
                        $itemarray = explode(":::::", $item);

                        if(mb_strlen($itemarray[0]) === 10 ){
                            try{
                                $m_asin = \Model_Asin::forge(array(
                                    'merchant_id'  => $baseurl->merchant_id,
                                    'brand_url'    => $url,
                                    'asin'         => $itemarray[0],
                                    'product_name' => $itemarray[1],
                                    'image_url'    => $itemarray[2],
                                    'price'        => $itemarray[3],
                                ));

                                if ($m_asin and $m_asin->save())
                                {
                                    \Log::DEBUG('DB SUCCESS getAsin ASIN:'.$itemarray[0]);

                                }
                                else
                                {
                                    \Log::ERROR('DB ERROR getAsin ASIN:'.$itemarray[0]);
                                }

                            }catch (\Database_Exception $e){

                                \Log::ERROR('DB EXCEPTION getAsin ASIN:'.$itemarray[0].' DETAIL:'.$e);

                            }
                        }
                    }
                }

            }


        }while($page < $maxpage);

        $query = \DB::update('brand')
            ->value('check_date', date("Y-m-d H:i:s"))
            ->where('brandurl', '=', $baseurl->brandurl)->execute();

    }

}

/* End of file tasks/robots.php */
