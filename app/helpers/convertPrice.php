<?php

function convertPrice($amount, $currency = 'USD')
{
    if($currency === 'USD') {
        return formatPrice($amount, $currency);
    }

    $convertedAmount = getLiveConversionRate($amount, 'USD', $currency);

    if ($convertedAmount === false) {
        return formatPrice($amount, 'USD'); 
    }

    return formatPrice($convertedAmount, $currency);
}

function getLiveConversionRate($amount, $fromCurrency, $toCurrency)
{
    $url = "https://www.xe.com/currencyconverter/convert/?Amount=$amount&From=$fromCurrency&To=$toCurrency";

    // Use @ to suppress warnings, check if URL fetch was successful
    $html = @file_get_contents($url);

    // If we couldn't fetch the page, return false to trigger fallback to USD
    if ($html === false) {
        return false;
    }

    // Try to extract the conversion rate from the HTML content
    $conversionRate = extractConversionRateFromHtml($html);

    // If extraction fails, return false to trigger fallback to USD
    if ($conversionRate === false) {
        return false;
    }

    // Return the converted amount
    return $conversionRate;
}

function extractConversionRateFromHtml($html)
{
    // Create a DOMDocument instance to parse the HTML
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    $xpath = new DOMXPath($dom);

    // Search for the element with the "data-testid" attribute
    $conversionDiv = $xpath->query("//div[@data-testid='conversion']");

    // Ensure the element is found
    if ($conversionDiv->length > 0) {
        // Get the second <p> tag (which contains the conversion amount)
        $secondPTag = $conversionDiv->item(0)->getElementsByTagName("p")->item(1);

        // Check if the second <p> tag exists and extract the text content
        if ($secondPTag) {
            $convertedText = $secondPTag->nodeValue;

            // Use regular expression to extract the numeric value
            preg_match('/([\d,]+\.\d+)/', $convertedText, $matches);

            // Return the extracted conversion rate as a float
            return isset($matches[1]) ? (float)str_replace(',', '', $matches[1]) : false;
        }
    }

    return false;
}

function formatPrice($amount, $currency)
{
    $currencySymbols = [
        'USD' => '$',       
        'EUR' => '€',       
        'GBP' => '£',       
        'JPY' => '¥',       
        'INR' => '₹',       
        'CNY' => '¥',       
        'AUD' => 'A$',      
        'CAD' => 'C$',      
        'CHF' => 'CHF',     
    ];

    if (isset($currencySymbols[$currency])) {
        $symbol = $currencySymbols[$currency];
    } else {
        $symbol = $currency;
    }

    return $symbol . number_format($amount, 2);
}
?>
