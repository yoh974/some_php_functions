<?php


class SimpleXMLPlugiMMOProgram
{
    const PROGRAM = 'program';
    const NODES = 'nodes';
    const PROGRAMME = 'programme';

    /**
     * @var SimpleXMLElement
     */
    private $xml_plugimmo_program;
    private $json_conf_tab;


    public function __construct($xml_path, $json_conf_path)
    {
        if (file_exists($xml_path)) {
            $xml_string = str_replace("&nbsp;", " ", file_get_contents($xml_path));
            $this->xml_plugimmo_program = simplexml_load_string($xml_string);

            unset($xml_string);
            if (file_exists($json_conf_path)) {
                $this->json_conf_tab = json_decode(
                    file_get_contents($json_conf_path), true);
                $this->xml_plugimmo_program = $this->xml_plugimmo_program->xpath("//".$this->json_conf_tab[self::PROGRAM][self::NODES][self::PROGRAMME]);

            }
            else {
                echo "json file not found\n";
            }
        }
        else {
            echo "xml file not found\n";
        }

    }

    /**
     * @return SimpleXMLElement
     */
    public function getXmlPlugimmoProgram(): SimpleXMLElement
    {
        return $this->xml_plugimmo_program;
    }

    /**
     * @param SimpleXMLElement $xml_plugimmo_program
     */
    public function setXmlPlugimmoProgram(SimpleXMLElement $xml_plugimmo_program): void
    {
        $this->xml_plugimmo_program = $xml_plugimmo_program;
    }


    public function getValueFromXml($attribute, $simpleXMLElement,$defaut_value = "")
    {

        if (isset($this->json_conf_tab[self::PROGRAM][self::NODES][$attribute])) {
            $this->{$attribute} = $this->processSpecialValues(
                $attribute . "s", $this->json_conf_tab,
                $this->processXPath($simpleXMLElement,
                    "./".$this->json_conf_tab[self::PROGRAM][self::NODES][$attribute]),
                $defaut_value);
        }
    }

    /**
     * @param SimpleXMLElement $xml
     * @param string $xpath
     * @param bool $multiple
     * @return array|string
     */
    protected function processXPath(SimpleXMLElement &$xml, string $xpath, $multiple = false)
    {
        $arrayIfMultiple = [];
        $return = "";
        if ($xpath !== "./" && $xpath !== ".//" && $xpath !== "") {


            if (!$objectXML = $xml->xpath($xpath)) {
                return "";
            }
            if (!$multiple) {
                if (is_array($objectXML)) {
                    if (count($objectXML) > 1 && trim(current($objectXML)->__toString()) === "") {
                        foreach ($objectXML as $objet) {
                            $xpath_result_formatted = trim($objet->__toString());
                            if ($xpath_result_formatted !== "") {
                                return $xpath_result_formatted;
                            }
                        }
                    }
                }
                return (!empty($objectXML)) ? trim(current($objectXML)->__toString()) : "";
            }
            else {
                if (!empty($objectXML)) {
                    foreach ($objectXML as $value) {
                        //permet de retourner un tableau de string
                        $arrayIfMultiple[] = trim($value->__toString());
                    }

                    return array_unique($arrayIfMultiple);
                }
            }
        }
        return $return;
    }

    /**
     *
     * check in json file if the processed file has special value to process
     * you need to add the field with an s to the end of the word
     *
     * @param                  $field
     * @param $conf
     * @param string $value
     * @param string $defaut_value
     * @return mixed|string
     */
    protected function processSpecialValues($field, &$conf, string $value, $defaut_value = "")
    {

        if (isset($conf[$field])) {
            if (isset($conf[$field][$value])) {
                $value = $conf[$field][$value];
            }
            else {
                if ($defaut_value !== "") {
                    $value = $defaut_value;
                }
            }
        }
        return $value;
    }


}