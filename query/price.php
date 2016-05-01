<?php
function getPrice($isbn){
    $response = file_get_contents('http://prices.valorebooks.com/lookup-multiple-categories?SiteID=nuTnh3&ProductCode='.$isbn.'&ShowType=BuybackPriceOnly&MinimumCondition=3&Level=Normal');
    $xml=simplexml_load_string($response);
    $value = .8 * (float)$xml->{'buy-offer'}->{'item-price'};

    return number_format($value, 2);
}

$isbn = $_POST['isbn'];

echo getPrice($isbn);
?>
