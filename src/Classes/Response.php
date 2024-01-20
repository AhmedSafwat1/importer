<?php

namespace Safwat\Importer\Classes;

use SimpleXMLElement;

class Response
{
    /**
     * @return never
     */
    public static function json(array $data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    /**
     * @return never
     */
    public static function xml(array $data)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: text/xml; charset=UTF-8');
        $xml = new SimpleXMLElement('<root/>');
        self::toXml($xml, $data);
        echo $xml->asXML();
        exit;
    }

    public static function toXml(SimpleXMLElement $object, array $data): void
    {
        foreach ($data as $key => $value) {
            // if the key is an integer, it needs text with it to actually work.
            $valid_key = is_numeric($key) ? "key_$key" : $key;
            $new_object = $object->addChild(
                $valid_key,
                is_array($value) ? null : htmlspecialchars($value)
            );

            if (is_array($value)) {
                self::toXml($new_object, $value);
            }
        }
    }
}
