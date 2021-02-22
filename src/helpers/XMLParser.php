<?php
class XMLParser
{
    private $xmlData, $htmlData;
    public function __construct(string $data, bool $isXML = false)
    {
        if ($isXML) {
            $this->xmlData = $data;
        } else {
            $this->htmlData = $data;
        }
    }

    public function parseHTML($encoding = 'UTF-8')
    {
        $dom = new DOMDocument('', $encoding);
        $dom->loadHTML($this->htmlData);
    }
}
?>
