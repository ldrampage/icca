<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    if(isset($_POST['id'])) {
        # delete sentence
        $sentence = $icca_new_obj->getSentenceById($_POST['id']);
        $result = $icca_new_obj->deleteSentence($_POST['id']);
        echo json_encode($result);
        
        # insert log
        $user = $icca_new_obj->getUserById($_POST['uid']);
        $action = trim($user['fname'])." ".trim($user['lname'])." deleted the sentence";
        $from_ = $sentence['sentence'];
        $log = $icca_new_obj->addSentLog($sentence['article_id'], $_POST['id'], $action, $from_, '');
        
        # adjust positioning of other sentences in the paragraph (if needed)
        $isLastSentence = false;
        $par_sentences = $icca_new_obj->getSentences('WHERE content_id = '.$sentence['content_id'].' AND paragraph_no = '.$sentence['paragraph_no'].' ORDER BY order_no');
        if(count($par_sentences) == intval($sentence['order_no'])) {
            $isLastSentence = true;
        }
            
        if(!$isLastSentence) {
            foreach($par_sentences as $sentence1) {
                if(intval($sentence1['order_no']) > $sentence['order_no']) {
                    $new_order = intval($sentence1['order_no']) - 1;
                    $icca_new_obj->adjustSentencesOrder($sentence1['id'], $new_order);
                }
            }
        }
    }
?>