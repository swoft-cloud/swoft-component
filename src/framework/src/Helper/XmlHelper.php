<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/4/20
 * Time: 下午1:58
 * @author April2 <ott321@yeah.net>
 */

namespace Swoft\Helper;

/**
 * User: sl
 * Date: 2018/4/20
 * Time: 下午2:22
 * Class XmlHelper
 * @package Swoft\Helper
 * @author April2 <ott321@yeah.net>
 */
class XmlHelper
{

    /**
     * User: sl
     * Date: 2018/4/20
     * Time: 下午2:07
     * @param string $xml
     * @author April2 <ott321@yeah.net>
     * @return array
     */
    public static function decode(string $xml){
        return self::xmlToArray($xml);
    }

    /**
     * User: sl
     * Date: 2018/4/20
     * Time: 下午2:07
     * @param array $data
     * @return string
     * @author April2 <ott321@yeah.net>
     */
    public static function encode(array $data){
        $xml = '<xml>';
        $xml.= self::arrayToXml($data);
        $xml.='</xml>';
        return $xml;
    }

    /**
     * User: sl
     * Date: 2018/4/20
     * Time: 下午2:07
     * @param string $xml
     * @author April2 <ott321@yeah.net>
     * @return array
     */
    public static function xmlToArray(string $xml){
        $res = [];
        //如果为空,一般是xml有空格之类的,导致解析失败
        $data = @(array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        if(isset($data[0]) && $data[0] ===false){
            $data = null;
        }
        if($data){
            $res = self::parseToArray($data);
        }
        return $res;
    }

    /**
     * User: sl
     * Date: 2018/4/20
     * Time: 下午3:18
     * @param $data
     * @author April2 <ott321@yeah.net>
     * @return array
     */
    protected static function parseToArray($data){
        $res = null;
        if(is_object($data)){
            $data = (array)$data;
        }
        if(is_array($data)){
            foreach ($data as $key=>$val){
                if(is_iterable($val)){
                    $res[$key] = self::parseToArray($val);
                }else{
                    $res[$key] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * User: sl
     * Date: 2018/4/20
     * Time: 下午2:42
     * @param array $data
     * @return string
     * @author April2 <ott321@yeah.net>
     */
    public static function arrayToXml(array $data){
        $xml = '';
        if(!empty($data)){
            foreach ($data as $key=>$val){
                $xml.="<$key>";
                if(is_iterable($val)){
                    $xml.=self::arrayToXml($val);
                }elseif(is_numeric($val)){
                    $xml.=$val;
                }else{
                    $xml.=sprintf('<![CDATA[%s]]>', $val);
                }
                $xml.="</$key>";
            }
        }
        return $xml;
    }

}