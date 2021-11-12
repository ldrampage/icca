<?php 

if(isset($_POST['preview'])){
    if ($_FILES['article']['type'] == 'text/plain'){
        $article =  file_get_contents($_FILES['article']['tmp_name']);
        $article_id = $iccaFunc->insertImportedArticle($article,$_SESSION['login_id']);
        echo "<script> location.href='?page=importArticle&id=$article_id'</script>";
    }
}

if(isset($_POST['import'])){
    $article = $iccaFunc->fetchImportedArticle($_POST['id']);
    // step 1
    // split the components (article-title) (article introduction) (article items) (article conclusion)
        
    $article_title = $iccaFunc->getTitle($article['article']);
        
    $article_intro = $iccaFunc->getIntroduction($article['article']);
        
    $article_items = $iccaFunc->getItems($article['article']);
    
    $article_conclu = $iccaFunc->getConclusion($article['article']);
        
    // step2 
    // verify the article
    if(empty($article_title) OR empty($article_intro) OR empty($article_items) OR empty($article_conclu)){
        $message = array("type"=>false, "message"=>"Failed to import. Article component is missing.");
    } else{
            
        // verify if a paragraph title is missing from the article items
        $paragraphTitleMissing = false;
        $article_items = trim($article_items);
        $p = 0;
            
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $article_items) as $line){
            if(preg_match('/\[title\]((?s).*)\[title\]/', $line,$matchesT)){
                //echo $matchesT[1]."<br>";
                $p=0;
            } elseif($line != ""){
                $p++;
            } else{
               // echo "blank"."<br>";
            }
                
            if($p >= 6){
                $paragraphTitleMissing = true;
                break;
            }
        }
        if($paragraphTitleMissing == true){
            $message = array("type"=>false, "message"=>"Import failed. One of the article items might be missing a paragraph title.");
        } else {
            if(preg_match('/\[article-title\](.*)\[article-title\]/',$article_title,$matchesTitle)){
                $data = array(trim($matchesTitle[1]),$_SESSION['login_id']);
                $article_id = $iccaFunc->importInsertArticleTitle($data);
                
                if($article_id == "articleAlreadyExist") {
                    $message = array("type"=>false, "message"=>"Import failed. Article already exist.");
                } elseif($article_id != false) {
                    $a1 = false;
                    $a2 = false;
                    $a3 = false;
                    
                    // article intro
                    $article_intro = trim($article_intro);
                    $content_id = 0;
                    $x = 0;
                    $paragraph = 1;
                    foreach(preg_split("/((\r?\n)|(\r\n?))/", $article_intro) as $line){
                            if($line != ""){
                            if($x == 0) { // this is the title
                                preg_match('/\[title\]((?s).*)\[title\]/', $line, $matchesT);
                                if(!empty($matchesT[1])){
                                    $title = trim($matchesT[1]);
                                    $data = array("article_id"=>$article_id, "type"=>"introduction","title"=>$title,"order_no"=>"1");
                                    //print_r($data);
                                    //echo "<br>";
                                    $content_id = $iccaFunc->importInsertIntroductionTitle($data);
                                } else{
                                    $data = array("article_id"=>$article_id, "type"=>"introduction","title"=>"none","order_no"=>"1");
                                    //print_r($data);
                                    //echo "<br>";
                                    $content_id = $iccaFunc->importInsertIntroductionTitle($data);
                                }
                            } else{
                                if($content_id != false){
                                    $data = array("content_id"=>$content_id,"article_id"=>$article_id,"paragraph_no"=>$paragraph,"paragraph"=>$line);
                                    //print_r($data);
                                    //echo "<br>";
                                    $res = $iccaFunc->importInsertIntroSentences($data);
                                    if($res){
                                        $a1 = true;
                                    } else {
                                        //Continue by deleting the article 
                                        $message = array("type"=>false, "message"=>"Import Failed. The introduction paragraph does not contain a [sentence-end] delimeter.");
                                        $iccaFunc->deleteArticleAndComponents($article_id);
                                        return;
                                    }
                                    $paragraph++;
                                } else{
                                    // Continue here by deleting the article.
                                    $iccaFunc->deleteArticleAndComponents($article_id);
                                    $message = array("type"=>false, "message"=>"Import Failed, (Introduction error) something went wrong. Please try again");
                                    return;
                                }
                            
                            }
                            $x++;
                        }
                    }
                    // article intro */
                
                    // article items
                    $article_items= trim($article_items);
                    $content_id=0;
                    $subHOrder = 1;
                    $parNo = 1;
                    foreach(preg_split("/((\r?\n)|(\r\n?))/", $article_items) as $line){
                        if($line != ""){
                            if(preg_match('/\[title\]((?s).*)\[title\]/', $line, $matchesT)){
                                $title = trim($matchesT[1]);
                                $data = array("article_id"=>$article_id, "type"=>"subheading","title"=>$title,"order_no"=>$subHOrder);
                                //print_r($data);
                                //echo "<br>";
                                $content_id = $iccaFunc->importInsertItemsTitle($data);
                                $subHOrder++;
                                $parNo=1;
                                
                            } else{
                                if($content_id != false) {
                                    $data = array("content_id"=>$content_id,"article_id"=>$article_id,"paragraph_no"=>$parNo,"paragraph"=>$line);
                                    $res = $iccaFunc->importInsertIntroSentences($data);
                                    if($res){
                                        $a2 = true;
                                        } else {
                                            $a2 = false;
                                            //Continue by deleting the article 
                                            $message = array("type"=>false, "message"=>"Import Failed. (Item error) The paragraph does not contain a [sentence-end] delimeter.");
                                            $iccaFunc->deleteArticleAndComponents($article_id);
                                            return;
                                        }
                                    $parNo++;
                                    //print_r($data);
                                    //echo "<br>";
                                    } else{
                                    $a2 = false;
                                    // Continue here by deleting the article.
                                    $iccaFunc->deleteArticleAndComponents($article_id);
                                    $message = array("type"=>false, "message"=>"Import Failed. (Item error) something went wrong. Please try again");
                                    return;
                                }
                                
                            }
                        }
                        
                    }
                    // article items
                
                    //article conclusion
                    $article_conclu = trim($article_conclu);
                    $content_id = 0;
                    $x = 0;
                    $paragraph = 1;
                    foreach(preg_split("/((\r?\n)|(\r\n?))/", $article_conclu) as $line){
                        if($line != ""){
                            if($x == 0) { // this is the title
                                preg_match('/\[title\]((?s).*)\[title\]/', $line, $matchesT);
                                if(!empty($matchesT[1])){
                                    $title = trim($matchesT[1]);
                                    $data = array("article_id"=>$article_id, "type"=>"conclusion","title"=>$title,"order_no"=>"1");
                                    //print_r($data);
                                    //echo "<br>";
                                    $content_id = $iccaFunc->importInsertConclusionTitle($data);
                                } else{
                                    $data = array("article_id"=>$article_id, "type"=>"conclusion","title"=>"none","order_no"=>"1");
                                    //print_r($data);
                                    //echo "<br>";
                                    $content_id = $iccaFunc->importInsertConclusionTitle($data);
                                }
                            } else{
                                if($content_id != false){
                                    $data = array("content_id"=>$content_id,"article_id"=>$article_id,"paragraph_no"=>$paragraph,"paragraph"=>$line);
                                    //print_r($data);
                                    //echo "<br>";
                                    $res = $iccaFunc->importInsertIntroSentences($data);
                                    if($res){
                                        $a3 = true;
                                    } else {
                                        //Continue by deleting the article 
                                        $message = array("type"=>false, "message"=>"Import Failed. The conclusion paragraph does not contain a [sentence-end] delimeter.");
                                        $iccaFunc->deleteArticleAndComponents($article_id);
                                        return;
                                    }
                                    $paragraph++;
                                } else{
                                    // Continue here by deleting the article.
                                    $iccaFunc->deleteArticleAndComponents($article_id);
                                    $message = array("type"=>false, "message"=>"Import failed. (Conclusion error). Please try again");
                                    return;
                                }
                                
                            }
                            $x++;
                        }
                    }
                    //article conclusion
                
                    if($a1 == TRUE AND $a2 == TRUE AND $a3 == TRUE){
                        $message = array("type"=>true, "message"=>"Successfully imported the article.");
                    }
                } else{
                    $message = array("type"=>false, "message"=>"Import failed, something went wrong. Please try again");
                }
     
            } else{
                $message = array("type"=>false, "message"=>"Import failed. Article title wrong format.");
            }
            
            
        }
            
    }
}

?>
<section class="content">
    <div class="row">
        <?php if(isset($message)): ?>
        <?php if($message['type'] == true) { ?>
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                <?php echo $message['message'] ?>
            </div>
        </div>
        <?php } else { ?>
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                <?php echo $message['message'] ?>
            </div>
        </div>
        <?php } ?>
        <?php endif; ?>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                        </div>
                        <div class="box-body">
                            <?php if(!isset($_GET['id'])): ?>
                            <form method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Import Article</label>
                                    <input type="file" class="form-control" name="article">
                                    
                                </div>
                                <div class="form-group pull-right">
                                    <input type="submit" value="preview" name="preview" class="btn btn-success">
                                </div>
                                 <!--
                                <div class="form-group">
                                    <input type="submit" value="import" name="submit" class="btn btn-success">
                                </div> -->
                            </form>
                            <?php endif; ?>
                            
                            <?php if(isset($_GET['id'])): $article = $iccaFunc->fetchImportedArticle($_GET['id']);  ?>
                            <?php  
                                if(!empty($article)) { 
                                    if(htmlentities($article['article']) != "") { $article = htmlentities($article['article']); } else { $article = $article['article']; }
                                    $article_title = $iccaFunc->getTitle($article);
                                    $article_intro = $iccaFunc->getIntroduction($article);
                                    $article_items = $iccaFunc->getItems($article);
                                    $article_conclu = $iccaFunc->getConclusion($article);
                            ?>
                            <div class="form-group">
                                <label>Article Title</label>
                                <input type="text" class="form-control" value="<?php echo trim($article_title); ?>" readonly/>
                            </div>
                            <div class="form-group">
                                <label>Article Introduction</label>
                                <textarea class="form-control" rows="10"readonly/><?php echo trim($article_intro); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Article Items</label>
                                <textarea class="form-control" rows="15"readonly/><?php echo trim($article_items); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Article Conclusion</label>
                                <textarea class="form-control" rows="10"readonly/><?php echo trim($article_conclu); ?></textarea>
                            </div>
                            
                            <form method="POST">
                                <div class="form-group pull-right">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                    <input type="submit" value="import" name="import" class="btn btn-success">
                                </div>
                            </form>
                            <?php } else { ?>
                            <!-- DOES NOT EXIST -->
                            <?php } ?>
                            <?php endif; ?>
                        </div>
                         
                        <div class="box-footer">
                        </div
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>