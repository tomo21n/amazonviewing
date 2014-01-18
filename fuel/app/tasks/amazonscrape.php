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
                        'brandurl' => $baseurl,
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

            exec("casperjs ".APPPATH."getItemList.js '".$url."'", $asinlist);

            $maxpage = 1;

            if($asinlist){
                foreach($asinlist as $asin){

                    if(mb_strlen($asin) === 10 ){
                        try{
                            $m_asin = \Model_Asin::forge(array(
                                'merchant_id' => $baseurl->merchant_id,
                                'brandurl' =>$url,
                                'asin' => $asin,
                                'price' => '0',
                                'search_index' => '0',
                            ));

                            if ($m_asin and $m_asin->save())
                            {
                                \Log::DEBUG('DB SUCCESS getAsin ASIN:'.$asin);

                            }
                            else
                            {
                                \Log::ERROR('DB ERROR getAsin ASIN:'.$asin);
                            }

                        }catch (\Database_Exception $e){

                            \Log::ERROR('DB EXCEPTION getAsin ASIN:'.$asin.' DETAIL:'.$e);

                        }


                    }elseif(strstr($asin, "Showing"))
                    {

                        if(preg_match("/^Showing [0-9]+ - [0-9]+ of [0-9]+ Results$/",$asin)){

                            if(preg_match("/of [0-9]+ Results$/",$asin,$match)){

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
