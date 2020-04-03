<?php

Class ArrayToXml
{
    /**
     * convert an array to xml
     * @param array $array
     * @param string $node
     * @param int $nb_indentation
     * @return string
     */
    public static function convert(Array $array, $node = "root", $nb_indentation = 0): string
    {
        $tabulation = "";
        for ($i = 0; $i < $nb_indentation; $i++) {
            $tabulation .= "\t";
        }
        $xml = "$tabulation<$node>\n";
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $xml .= self::convert($value, $key, ++$nb_indentation);
                }
                else {
                    $xml .= "$tabulation\t<$key>$value</$key>\n";
                }

            }
        }

        $xml .= "$tabulation</$node>\n";
        return $xml;
    }
}