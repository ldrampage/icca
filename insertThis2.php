<?php 

include("core/iccaFunctionsNew.php");
$iccaFunc = new iccaFunc();

if(isset($_POST['formP'])){
    if($_POST['introduction_title'] == ""){ $_POST['introduction_title'] = "none"; } 
    echo $iccaFunc->insertIntroductionTitleAndParagraphs($_POST,$_POST['article_id'],$_POST['type'], $_POST['introduction_title']);
}

if(isset($_POST['formS'])){
    //print_r($_POST);
    echo $iccaFunc->insertSubheadingAndParagraph($_POST,$_POST['article_id'],$_POST['subheading_title'],$_POST['type']);

}
if(isset($_POST['formG'])){
    if($_POST['conclusion_title'] == ""){ $_POST['conclusion_title'] = "none"; } 
    echo $iccaFunc->insertConclusion($_POST,$_POST['article_id'],$_POST['type'],$_POST['conclusion_title']);
}

if(isset($_POST['editSentenceSave'])){
    //print_r($_POST);
    echo $iccaFunc->insertEditedSentence($_POST);
}

if(isset($_POST['deleteThis'])){
    //print_r($_POST);
    echo $iccaFunc->deleteRewrite($_POST);
}

if(isset($_POST['deleteArticle'])){
    echo $iccaFunc->deleteArticle($_POST);
}

if(isset($_POST['ready'])){
    echo $iccaFunc->switchStatus($_POST);
}

if(isset($_POST['trigger'])){
    if($_POST['trigger'] == "this2"){
        echo $iccaFunc->addDelimeter2($_POST['value']);
    } else if($_POST['trigger'] == "this3"){
        echo $iccaFunc->applyTitleTag($_POST['value']);
    } else if($_POST['trigger'] == "artTitle") {
        echo $iccaFunc->applyArtTitleTag($_POST['value']);
    } else if($_POST['trigger'] == "artIntro") {
        echo $iccaFunc->applyArtIntroTag($_POST['value']);
    } else if($_POST['trigger'] == "artItems") {
        echo $iccaFunc->applyArtItemsTag($_POST['value']);
    } else if($_POST['trigger'] == "artConclu") {
        echo $iccaFunc->applyArtConcluTag($_POST['value']);
    } else {
        echo $iccaFunc->addDelimeter($_POST['value']);
    }
}


if(isset($_POST['perfectTense'])){
    //print_r($_POST);
    echo $iccaFunc->perfectTense($_POST);
}

if(isset($_POST['importArticle'])){
    print_r($iccaFunc->importArticle($_POST));
}

if(isset($_POST['rejectedDetails'])){
    print_r($iccaFunc->rejectedDetails($_POST));
}

if(isset($_POST['rejectedDetails2'])){
    print_r($iccaFunc->rejectedDetails2($_POST));
}

if(isset($_POST['article_id']) && isset($_POST['sentence'])){
    print_r($iccaFunc->insertRewrittenSentence($_POST));
}

?>