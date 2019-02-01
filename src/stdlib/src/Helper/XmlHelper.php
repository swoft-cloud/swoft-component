<?php declare(strict_types=1);


namespace Swoft\Stdlib\Helper;

/**
 * Class XmlHelper
 *
 * @since 2.0
 */
class XmlHelper
{
    /**
     * @param string $xml
     *
     * @return array
     */
    public static function decode(string $xml): array
    {
        return self::xmlToArray($xml);
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public static function encode(array $data): string
    {
        $xml = '<xml>';
        $xml .= self::arrayToXml($data);
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * @param string $xml
     *
     * @return array
     */
    public static function xmlToArray(string $xml): array
    {
        $res = [];
        //如果为空,一般是xml有空格之类的,导致解析失败
        $data = @(array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        if (isset($data[0]) && $data[0] === false) {
            $data = null;
        }
        if ($data) {
            $res = self::parseToArray($data);
        }
        return $res;
    }

    /**
     * @param $data
     *
     * @return array
     */
    protected static function parseToArray($data): array
    {
        $res = null;
        if (is_object($data)) {
            $data = (array)$data;
        }
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (is_iterable($val)) {
                    $res[$key] = self::parseToArray($val);
                } else {
                    $res[$key] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public static function arrayToXml(array $data): string
    {
        $xml = '';
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $xml .= "<$key>";
                if (is_iterable($val)) {
                    $xml .= self::arrayToXml($val);
                } elseif (is_numeric($val)) {
                    $xml .= $val;
                } else {
                    $xml .= self::characterDataReplace($val);
                }
                $xml .= "</$key>";
            }
        }
        return $xml;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected static function characterDataReplace(string $string): string
    {
        return sprintf('<![CDATA[%s]]>', $string);
    }
}