<?php
/**
 *
 * convert
 * @param string $csv_path
 * @param $delimiter
 * @param string $string_delimiter
 */
function csv2xml(string $csv_path, $delimiter, $string_delimiter = "\"")
{
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<datas>\n";
    if (file_exists($csv_path)) {
        $handle = @fopen($csv_path, "r");
        if ($handle) {
            $tab_balise = [];
            $tab_data = [];
            if ($buffer = fgets($handle, 4096)) {
                $buffer = rtrim(trim($buffer), $delimiter);
                $tab_balise = explode($delimiter, $buffer);
            }
            while (($buffer = fgets($handle, 4096))) {
                $tab_data = explode($delimiter, rtrim(trim($buffer), $delimiter));
                $xml .= "\t<data>\n";
                for ($i = 0; $i < count($tab_data); $i++) {
                    $xml .= "\t\t<" . $tab_balise[$i] . ">" . ltrim(rtrim($tab_data[$i], $string_delimiter), $string_delimiter)
                        . "</" . $tab_balise[$i] . ">\n";
                }
                $xml .= "\t</data>\n";
            }
            $xml .= "</datas>";
            if (!feof($handle)) {
                echo "Erreur: fgets() a échoué\n";
            }
            fclose($handle);
            $handle = fopen("result_xml.xml", "wb");
            fwrite($handle, $xml);
            fclose($handle);
        }
    }
    else {
        die("file don't exist");
    }
}