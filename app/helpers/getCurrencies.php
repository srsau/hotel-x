
<?php

function getCurrencies()
{
    $yesterday = (new DateTime('yesterday'))->format('Y-m-d');
    $url = "https://www.xe.com/currencytables/?from=USD&date=$yesterday";

    $html = file_get_contents($url);
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    $xpath = new DOMXPath($dom);
    $currencyNodes = $xpath->query('//table[contains(.,"Currency")]/tbody/tr/th');

    $currencies = ['USD'];
    foreach ($currencyNodes as $node) {
        $currencyCode = $node->nodeValue;
        if ($currencyCode !== 'USD') {
            $currencies[] = $currencyCode;
        }
    }

    return $currencies;
}
?>