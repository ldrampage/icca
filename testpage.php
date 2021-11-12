<?php 

$header = array();
$header[] = "Authorization: Wbv2091aez8QvM9SUE6jFAtt";
$header[] = "AppAuthorization: THMlBa09tQdjgWV5K0naYAtt";
$header[] = "Content-Type: application/json";

$data = array("text"=>"This article has an error","responseType"=>array("corrected","grammarScore","rulesApplied","offset","summary"));

//echo json_encode($data);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://api.perfecttense.com/correct");
curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
curl_setopt ($curl, CURLOPT_POST, 1);
curl_setopt ($curl, CURLOPT_POSTFIELDS, json_encode($data));

$result = curl_exec($curl);
echo "<pre>";
print_r($result);
echo "</pre>";

curl_close($curl);



?>