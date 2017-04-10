<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$xml = simplexml_load_file("tours.xml");

$xml2csvFields = array();

$deperture["code"] = $deperture["starts"] = $deperture["gbp"] = $deperture["eur"] = $deperture["usd"] = $deperture["discount"] = array();

echo "<h1>XML to HTML</h1>";

foreach ($xml->TOUR as $xmlTour) {
	echo "<b>Title : </b>".strip_tags($xmlTour->Title);
	$title = strip_tags($xmlTour->Title);
	echo "<br><b>Code : </b>".$xmlTour->Code;
	$code = $xmlTour->Code;
	echo "<br><b>Duration : </b>".$xmlTour->Duration;
	$duration = $xmlTour->Duration;
	echo "<br><b>Start : </b>".$xmlTour->Start;
	$start = $xmlTour->Start;
	echo "<br><b>End : </b>".$xmlTour->End;
	$end = $xmlTour->End;
	echo "<br><b>Inclusions : </b>".strip_tags($xmlTour->Inclusions);
	$inclusions = strip_tags($xmlTour->Inclusions);
	echo "<br><b>Deperture : </b>";
	foreach ($xmlTour->DEP as $childDep) {
		echo "<br>&emsp;<b>Departure Code : </b>".$childDep['DepartureCode'];
		array_push($deperture["code"], $childDep['DepartureCode']);
		echo "&emsp;<b>Starts : </b>".$childDep['Starts'];
		array_push($deperture["starts"], $childDep['Starts']);
		echo "&emsp;<b>GBP : </b>".$childDep['GBP'];
		array_push($deperture["gbp"], $childDep['GBP']);
		echo "&emsp;<b>EUR : </b>".$childDep['EUR'];
		array_push($deperture["eur"], $childDep['EUR']);
		echo "&emsp;<b>USD : </b>".$childDep['USD'];
		array_push($deperture["usd"], $childDep['USD']);
		echo "&emsp;<b>Discount : </b>".$childDep['DISCOUNT'];
		array_push($deperture["discount"], $childDep['DISCOUNT']);
	}

	for($i=0; $i<count($deperture['eur']);$i++) {
		$price[$i] = $deperture['eur'][$i] - $deperture['eur'][$i] * str_replace('%','',$deperture['discount'][$i]) / 100;
	}
	sort($price);
	echo "<br><b>MinPrice (EUR) : </b>".$minPrice = str_replace(",","",number_format($price[0],2));

	array_push($xml2csvFields, $title,$code,$duration,$inclusions,$minPrice);
}

xml2csv($xml2csvFields);

function xml2csv($array) {
	$csvFile = fopen("xml2csv.csv","w");
	fputcsv(
		$csvFile,
		$newArray = array_map(function($v){
		    return strip_tags(html_entity_decode(trim($v)));
		}, $array),
		"|",
		chr(8)
	);
   	fclose($csvFile);
}

echo "<h1>XML to CSV FILE</h1>";
echo "<a href='http://".substr($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],0,-3)."csv'>xml2csv.csv</a>";
?>