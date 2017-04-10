<?php

namespace Hyperbolaa\Wechatpay\Lib;

use SimpleXMLElement;

/**
 * Class XML.
 */
class XML
{
    /**
     * XML to array.
     *
     * @param string $xml XML string
     *
     * @return array|\SimpleXMLElement
     */
    public static function parse($xml)
    {
        return self::normalize(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS));
    }


    /**
     * Object to array.
     *
     *
     * @param SimpleXMLElement $obj
     *
     * @return array
     */
    protected static function normalize($obj)
    {
        $result = null;

        if (is_object($obj)) {
            $obj = (array) $obj;
        }

        if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                $res = self::normalize($value);
                if (($key === '@attributes') && ($key)) {
                    $result = $res;
                } else {
                    $result[$key] = $res;
                }
            }
        } else {
            $result = $obj;
        }

        return $result;
    }

}
