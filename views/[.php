<?php 
    if(!isset($_GET['articId']) || empty($_GET['articId'])){
        echo "<script> location.href='?page=rewriteArticle'</script>";
    } else{
        if(!$iccaFunc->articleExist($_GET['articId'])){
            echo "<script> location.href='?page=rewriteArticle'</script>";
        }
    }
    
    if(isset($_POST['saveParagraph'])){
        $message = $iccaFunc->insertRewrittenIntro($_POST, $_GET['paragId'], $_GET['articId'], $_SESSION['login_id']);
    }
    
    if(isset($_POST['savesubHParagraph'])) {
        $iccaFunc->insertRewrittenSubheading($_POST, $_GET['subHParagId'],$_GET['subHid'], $_GET['articId'], $_SESSION['login_id']);
    }
?>
<section class="content">
    
    <div class="row">
        <div class="col-md-12">
            <?php if(isset($message)): ?>
            <?php if($message == true) {?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                You have just rewrote a paragraph. Please wait while it is being reviewed. <a href="?page=rewriting">Go back</a>
            </div>
            <?php } else { ?>

            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
                Something went wrong this time. Please try again!
            </div>
            <?php } endif;?>
            
            <div class="col-md-3">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Criteria</h4>
                    </div>
                    <div class="box-body">
                        <?php 
                            $article = $iccaFunc->getArticle($_GET['articId']);
                            $article_intro = $iccaFunc->getArticleIntro($_GET['articId']);
                            $subHeadings = $iccaFunc->getSubHeadings($_GET['articId']);
                            //print_r($subHeadings);
                            //print_r($article_intro);
                        ?>
                        <p class="text-muted">You are rewriting article: <strong><?php echo "<br>". $article[0]['title']; ?></strong></p>
                        
                        <div class="form-group">
                            <label>Which part of the article are you going to rewrite?</label>
                            <select class="form-control" id="criteria1">
                                <option value=""></option>
                                <option value="artIntro" <?php if(isset($_GET['artIntro'])) { echo "selected";} ?>>Article Introduction</option>
                                <option value="subHContent" <?php if(isset($_GET['subHContent'])) { echo "selected";} ?>>Article Subheadings</option>
                            </select>
                        </div>
                        
                        <?php if(isset($_GET['subHContent'])): ?>
                        <div class="form-group">
                            <label>Which subheading are you going to rewrite?</label>
                            <select class="form-control" id="criteria2">
                                <option value=""></option>
                                <?php foreach($subHeadings as $item): ?>
                                    <option value="<?php echo $item['_id']?>"<?php if(isset($_GET['subHId'])){ if($item['_id'] == $_GET['subHId']) {echo "selected";} } ?>><?php echo $item['subheading']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="box-footer">
                    </div>
                </div>
                <?php if(isset($_GET['artIntro'])): ?>
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Article Introduction</h4>
                    </div>
                    <div class="box-body">
                        <label>Choose which paragraph</label>
                        <?php foreach($article_intro as $item): ?>
                                <a href="?page=rewriting&articId=<?php echo $_GET['articId']; ?>&artIntro=<?php echo $_GET['artIntro']; ?>&paragId=<?php echo $item['_id'];?>">
                                    <p class="text-break" style="background-color:#337ab7; border-radius: 5px; padding: 10px; color:white;" >
                                    <?php echo $item['intro_paragraph']; ?>
                                    </p>
                                </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="box-footer">
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(isset($_GET['subHId'])): ?>
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Article Subheading</h4>
                    </div>
                    <div class="box-body">
                        <label>Choose which paragraph</label>
                        <?php
                           $subHParagraphs =  $iccaFunc->getSubHContent($_GET['subHId']);
                        ?>
                        <?php foreach($subHParagraphs as $item): ?>
                                <a href="?page=rewriting&articId=<?php echo $_GET['articId']; ?>&subHContent=<?php echo $_GET['subHContent']; ?>&subHId=<?php echo $_GET['subHId']; ?>&subHParagId=<?php echo $item['_id'];?>">
                                    <p class="text-break" style="background-color:#337ab7; border-radius: 5px; padding: 10px; color:white;" >
                                    <?php echo $item['content']; ?>
                                    </p>
                                </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="box-footer">
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <?php 
                if(isset($_GET['paragId'])){
                    $artParagraph = $iccaFunc->getArticleIntroParagraph($_GET['paragId']); 
                }
                if(!empty($artParagraph)){ 
            ?>
            <div class="col-md-9">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">
                            Rewrite Paragraph
                        </h4>
                    </div>
                    <div class="box-body">
                        <p class="text-break" style="background-color:#337ab7; border-radius: 5px; padding: 10px; color:white;" >
                            <?php echo $artParagraph[0]['intro_paragraph']; ?>
                        </p>
                        <form method="post" id="rewriteForm">
                        <?php 
                            
                            $sentences = explode(".",$artParagraph[0]['intro_paragraph']);
                            $sentences = array_filter($sentences);
                            //print_r($sentences);
                            for($i=0; $i< count($sentences); $i++){
                        ?>
                        
                        <div class="form-group" id="sentence<?php echo $i?>">
                            <p class="characters" hidden><?php echo strlen($sentences[$i]); ?></p>
                            <p class="words" hidden><?php echo str_word_count($sentences[$i]); ?></p>
                            <p class="text-muted">(sentence <?php echo ($i+1).") No. of characters: ".strlen($sentences[$i])." No. of words: ".str_word_count($sentences[$i])." "; ?></p>
                            <p><strong><?php echo $sentences[$i].". "; ?></strong></p>
                            <div id="error<?php echo $i?>" style="display:none; color:red;"></div>
                            <textarea class="form-control" rows="3" placeholder="Enter..." name="<?php echo 'sentence'.$i; ?>" required></textarea>
                        </div>
                        
                        <?php } ?>
                        
                    </div>
                    <div class="box-footer">
                        <div class="form-group">
                            <input  class="btn btn-success" type="submit" name="saveParagraph" value="save">
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            
            <?php } ?>
            
            <?php 
                if(isset($_GET['subHParagId'])){
                    $subHparagraph = $iccaFunc->getSubHParagraph($_GET['subHParagId']); 
                }
                if(!empty($subHparagraph)){
                    
            ?>
            <div class="col-md-9">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">
                            Rewrite Paragraph
                        </h4>
                    </div>
                    <div class="box-body">
                        <p class="text-break" style="background-color:#337ab7; border-radius: 5px; padding: 10px; color:white;" >
                            <?php echo $subHparagraph[0]['content']; ?>
                        </p>
                        <form method="post" id="rewriteForm">
                        <?php 
                            
                            $sentences = explode(".",$subHparagraph[0]['content']);
                            $sentences = array_filter($sentences);
                            //print_r($sentences);
                            for($i=0; $i< count($sentences); $i++){
                        ?>
                        
                        <div class="form-group" id="sentence<?php echo $i?>">
                            <p class="characters" hidden><?php echo strlen($sentences[$i]); ?></p>
                            <p class="words" hidden><?php echo str_word_count($sentences[$i]); ?></p>
                            <p class="text-muted">(sentence <?php echo ($i+1).") No. of characters: ".strlen($sentences[$i])." No. of words: ".str_word_count($sentences[$i])." "; ?></p>
                            <p><strong><?php echo $sentences[$i].". "; ?></strong></p>
                            <div id="error<?php echo $i?>" style="display:none; color:red;"></div>
                            <textarea class="form-control" rows="3" placeholder="Enter..." name="<?php echo 'sentence'.$i; ?>" required></textarea>
                        </div>
                        
                        <?php } ?>
                        
                    </div>
                    <div class="box-footer">
                        <div class="form-group">
                            <input  class="btn btn-success" type="submit" name="savesubHParagraph" value="save">
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            
            
            <?php } else { ?>
            
            <?php } ?>
            
        </div>
    </div>
    
</section>
<script>
    $("#rewriteForm").on("submit",function(){
        var data = $(this).serializeArray();
        console.log(data);
        var num = 0;
        var flag = false;
        $(data).each(function(i, field){
            
            function countWords(s){
	            s = s.replace(/(^\s*)|(\s*$)/gi,"");
	            s = s.replace(/[ ]{2,}/gi," ");
	            s = s.replace(/\n /,"\n");
	            return s.split(' ').length;
            }
            
            var err = "error"+num;
            var id = "#"+field.name;
            
            var wordCount = $(id).find('.words').html();
            var allowedWordCount = Math.round(wordCount * 0.70);
            
            var charCount = $(id).find('.characters').html();
            var allowedCharCount = Math.round(charCount * 0.60);
            //console.log(allowedCharCount);

            var wordsEntered = countWords(field.value);
            var charEntered = field.value.length;
            
            if(charEntered > allowedCharCount){
                 document.getElementById(err).style.display="none";
                if(wordsEntered > allowedWordCount){
                    document.getElementById(err).style.display="none";
                    flag = true;
                } else{
                    document.getElementById(err).innerHTML = "Words Entered: <strong>"+wordsEntered+".</strong> Word count must be at least 70% of original sentence!";
                    document.getElementById(err).style.display="block";
                    flag = false;
                }
            } else{
                document.getElementById(err).innerHTML = "Characters Entered: <strong>"+charEntered+".</strong> Character length must be at least 60% of original sentence!";
                document.getElementById(err).style.display="block";
                flag = false;
            }
            num = num + 1;
        });
        if(flag){
            return true;
        } else{
            return false;
        }
        
    });
    

    $("#criteria1").change(function(){
        var criteria1 = $(this).val();
        console.log(criteria1);
        
        if(criteria1 == "artIntro"){
            location.href='?page=rewriting&articId=<?php echo $_GET['articId']; ?>&artIntro=true';
        } else if(criteria1 == "subHContent"){
            location.href='?page=rewriting&articId=<?php echo $_GET['articId']; ?>&subHContent=true';
        } else{
            location.href='?page=rewriting&articId=<?php echo $_GET['articId']; ?>';
        }
    })
    
    $("#criteria2").change(function(){
        var criteria2 = $(this).val();
        location.href="?page=rewriting&articId=<?php echo $_GET['articId']; ?>&subHContent=true&subHId="+criteria2;
    })
    
    
    
    
    
</script>