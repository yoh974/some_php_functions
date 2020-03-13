<?php


/**
 *
 * Execute xpath on simpleXmlElement and return a string or an array of strings
 *
 * @param SimpleXMLElement $simpleXMLElement
 * @param string $xpath
 * @param bool $multiple_results
 * @return array|string
 */
function processXPath(SimpleXMLElement &$simpleXMLElement, string $xpath, $multiple_results = false)
{
    $arrayIfMultiple = [];

    if ($xpath !== "./" && $xpath !== ".//" && $xpath !== "") {


        $XMLElement = $simpleXMLElement->xpath($xpath);
        if (!$multiple_results) {
            if (is_array($XMLElement)) {
                if (count($XMLElement) > 1 && trim(current($XMLElement)->__toString()) === "") {
                    foreach ($XMLElement as $item) {
                        $xpath_result_formatted = trim($item->__toString());
                        if ($xpath_result_formatted !== "") {
                            return $xpath_result_formatted;
                        }
                    }
                }
            }
            return (!empty($XMLElement)) ? trim(current($XMLElement)->__toString()) : "";
        } else {
            if (!empty($XMLElement)) {
                foreach ($XMLElement as $item) {
                    //permet de retourner un tableau de string
                    $arrayIfMultiple[] = trim($item->__toString());
                }

                return array_unique($arrayIfMultiple);
            }
        }
    }
    return "";
}