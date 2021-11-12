<?php

include("core/iccaFunctionsNew.php");
$iccaFunc = new iccaFunc();

if(isset($_POST['sentenceId'])){
   echo $iccaFunc->fetchRewrotedSentence($_POST);
}

if(isset($_POST['wordAI'])){
    echo $iccaFunc->processSpin($_POST);
}

?>