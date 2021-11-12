<?php

// check if user is allowed
if(!$iccaFunc->isAllowed($_SESSION['login_id'], "rewriteArticle")){
    echo "<script> alert('User not allowed'); location.href='index.php' </script>";
}

if(!isset($_GET['articleId']) || empty($_GET['articleId'])){
        echo "<script> location.href='?page=rewriteArticleNew'</script>";
    } else{
        if(!$iccaFunc->articleExist($_GET['articleId'])){
            echo "<script> location.href='?page=rewriteArticleNew'</script>";
        }
    }

if(isset($_POST['saveSentence'])){
    $message = $iccaFunc->insertRewrittenSentence($_POST);
}
    
?>

<section class="content">
    
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible" id="messageSuccess" style="display:none;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i>Rewrite Success!</h4>
                Choose another sentence to rewrite.
            </div>

            <div class="alert alert-danger alert-dismissible" id="messageFailed" style="display:none;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
                Something went wrong this time. Please try again!
            </div>
            
            <div class="col-md-3">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Criteria</h4>
                        <a href="?page=rewriteArticleNew" class="btn btn-xs btn-primary pull-right">Go back</a>
                    </div>
                    <div class="box-body">
                        <?php 
                            $article = $iccaFunc->fetchArticle($_GET['articleId']);
                            $introduction = $iccaFunc->fetchArticleIntroduction($_GET['articleId']);
                            $subHeadings = $iccaFunc->fetchSubheadingTitle($_GET['articleId']);
                            $conclusion = $iccaFunc->fetchConclusion($_GET['articleId']);
                           // print_r($subHeadings);
                            
                        ?>
                        <p class="text-muted">You are rewriting article: <strong><?php echo "<br>". $article[0]['title']; ?></strong></p>
                        
                        <div class="form-group">
                            <label>Which part of the article are you going to rewrite?</label>
                            <select class="form-control" id="criteria1">
                                <option value=""></option>
                                <option value="artIntro" <?php if(isset($_GET['artIntro'])) { echo "selected";} ?>>Article Introduction</option>
                                <option value="subHContent" <?php if(isset($_GET['subHContent'])) { echo "selected";} ?>>Article Items</option>
                                <option value="conclu" <?php if(isset($_GET['conclu'])) { echo "selected";} ?>>Article Conclusion</option>
                            </select>
                        </div>
                        
                        <?php if(isset($_GET['subHContent'])): ?>
                        <?php if(!empty($subHeadings)) { ?>
                        <div class="form-group">
                            <label>Which item are you going to rewrite?</label>
                            <select class="form-control" id="criteria2">
                                <option value=""></option>
                                <?php foreach($subHeadings as $item): ?>
                                    <option value="<?php echo $item['id']?>"<?php if(isset($_GET['subHId'])){ if($item['id'] == $_GET['subHId']) {echo "selected";} } ?>><?php echo $item['title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php } else {?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-ban"></i> No content</h4>
                            If you're having trouble. Please contact administrator.
                        </div>
                        <?php } ?>
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
                        <?php 
                        if(!empty($introduction)){
                            foreach($introduction as $item){
                                $sentences = $iccaFunc->fetchSentences($item['id']);
                                $paragraph = $iccaFunc->mergeSentences($sentences);
                                
                        ?>
                        <label>Introduction Heading</label>
                        <input class="form-control" type="text" value="<?php if(htmlentities($item['title'])== "") echo $item['title']; else echo htmlentities($item['title']); ?>" disabled="true"></h5>
                        <br>
                        <label>Choose which paragraph</label>
                        <?php $parNo = 1; foreach($paragraph as $par): ?>
                            <a href="?page=rewritingNew&articleId=<?php echo $_GET['articleId']; ?>&artIntro=<?php echo $_GET['artIntro']; ?>&contId=<?php echo $item['id']; ?>&parNo=<?php echo $parNo;?>">
                                <p class="text-break" style="background-color:#337ab7; border-radius: 5px; padding: 10px; color:white;" >
                                <?php if(htmlentities($par)== "") echo $par; else echo htmlentities($par); ?>
                                </p>
                            </a>
                        <?php $parNo++; endforeach; ?>
                        <?php } } else { ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-ban"></i> No content</h4>
                            If you're having trouble. Please contact administrator.
                        </div>
                        <?php } ?>
                    </div>
                    <div class="box-footer">
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(isset($_GET['subHId'])): ?>
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Article Item</h4>
                    </div>
                    <div class="box-body">
                        <label>Choose which paragraph</label>
                        <?php
                           $sentences =  $iccaFunc->fetchSentences($_GET['subHId']);
                           $paragraph = $iccaFunc->mergeSentences($sentences);
                        ?>
                        <?php $parNo=1; foreach($paragraph as $item): ?>
                                <a href="?page=rewritingNew&articleId=<?php echo $_GET['articleId']; ?>&subHContent=<?php echo $_GET['subHContent']; ?>&subHId=<?php echo $_GET['subHId']; ?>&contId=<?php echo $sentences[0]['content_id']; ?>&parNo=<?php echo $parNo; ?>">
                                    <p class="text-break" style="background-color:#337ab7; border-radius: 5px; padding: 10px; color:white;" >
                                    <?php if(htmlentities($item)== "") echo $item; else echo htmlentities($item); ?>
                                    </p>
                                </a>
                        <?php $parNo++; endforeach; ?>
                    </div>
                    <div class="box-footer">
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(isset($_GET['conclu'])) : ?>
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Article Conclusion</h4>
                    </div>
                    <div class="box-body">
                        <?php 
                        if(!empty($conclusion)){
                            foreach($conclusion as $item){
                                $sentences = $iccaFunc->fetchSentences($item['id']);
                                $paragraph = $iccaFunc->mergeSentences($sentences);
                                
                        ?>
                        <label>Conclusion Heading</label>
                        <input class="form-control" type="text" value="<?php echo $item['title']; ?>" disabled="true"></h5>
                        <br>
                        <label>Choose which paragraph</label>
                        <?php $parNo = 1; foreach($paragraph as $par): ?>
                            <a href="?page=rewritingNew&articleId=<?php echo $_GET['articleId']; ?>&conclu=<?php echo $_GET['conclu']; ?>&contId=<?php echo $item['id']; ?>&parNo=<?php echo $parNo;?>">
                                <p class="text-break" style="background-color:#337ab7; border-radius: 5px; padding: 10px; color:white;" >
                                <?php if(htmlentities($par)== "") echo $par; else echo htmlentities($par); ?>
                                </p>
                            </a>
                        <?php $parNo++; endforeach; ?>
                        <?php } } else { ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-ban"></i> No content</h4>
                            If you're having trouble. Please contact administrator.
                        </div>
                        <?php } ?>
                    </div>
                    <div class="box-footer">
                    </div>
                </div>
                
                <?php endif; ?>
            </div>
            
            <?php 
                if(isset($_GET['contId']) && isset($_GET['parNo'])){
                    $artParagraph = $iccaFunc->fetchParagraph($_GET['contId'], $_GET['parNo']); 
                }
                if(!empty($artParagraph)){ 
            ?>
            <div class="col-md-9">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">
                            Rewrite 
                        </h4>
                    </div>
                    <div class="box-body">
                        <label>Choose a sentence</label>
                        <?php foreach($artParagraph as $sentence): $ar = $iccaFunc->fetchApprovedRewrites($sentence['id']); ?>
                        
                        <a href=""  data-toggle="tooltip" title="<?php echo 'No. of Approved Rewrites: ' . $ar; ?>" class="sentenceOption" value="<?php echo $sentence['id']; ?>">
                            <strong>
                                <p class="text-break" value="<?php echo $sentence['id']."::".$sentence['order_no']."::".$sentence['paragraph_no']; ?>" style="font-size: 15px; background-color:#777; border-radius: 10px; padding: 5px; color:white;" >
                                    <?php if(htmlentities($sentence['sentence']) == "" || strpos($sentence['sentence'],'&frac')!==false) echo $sentence['sentence']; else echo htmlentities($sentence['sentence']); ?>
                                </p>
                              
                            </strong>
                        </a>
                  
                        
                        <?php endforeach; ?>
                        <form method="post" id="formA">
                            <div class='form-group' id="formAContent" style="display:none;">
                                <hr/>
                                <p class="text-muted" id="sentenceWordCharCount"></p>
                                <p id='tobeEditedSentence' style='font-size: 18px; background-color:#337ab7; border-radius: 5px; padding: 5px; color:white;'></p>
                                <input type='hidden' name='orig_sentence'/>
                                <input type='hidden' name='sentenceId' />
                                <input type='hidden' name='sentenceOrderNo'/>
                                <input type='hidden' name='sentenceParNo'/>
                                <input type='hidden' id='wordOrigCount'/>
                                <label>Rewrite Sentence</label>
                                <div id='error' style='display:none; color:red;'></div>
                                <p class="text-muted" id="textareasentenceWordCharCount"></p>
                                <textarea name='sentence' oninput='CheckInputText(this.value)' rows='8' class='form-control' required></textarea>
                                <p class="text-muted" style="color:green;" id="showWordCharCountRealTime"/></p>
                            </div>
                        
                        
                    </div>
                    <div class="box-footer">
                        <div class="form-group">
                            <input type="hidden" name="article_id" value="<?php echo $_GET['articleId']; ?>">
                            <input type="hidden" name="editor" value="<?php echo $_SESSION['login_id']; ?>">
                            <input class="btn btn-success" type="submit" id='saveSentence' name="saveSentence" value="save">
                        </form> 
                            <button class="btn btn-info btn-small" id="wordAI"><img id="loadingSpin" src="images/loading.gif" height="20" width="26" style="display:none;" ><span id="spinText">Spin</span></button>
                            <button onclick="perfectTense()" class="btn btn-warning btn-small" id="perfectTense"><img id="loadingPerfectTense" src="images/loading.gif" height="20" width="26" style="display:none;"><span id="ptText">PerfectTense</span></button>
                        </div>
                        
                    </div>
                </div>
            </div>
            <?php } else{ ?>
            <!--<div class="col-md-9">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> No content</h4>
                    If you're having trouble. Please contact administrator.
                </div>
            </div> -->
            <?php }?>
        </div>
    </div>
    <!--
    <div class="row">
        <div class="col-md-12" id='rewroteHistoryIntro' style="display:none;">
            <div class="box sentenceBox">
                <div class="box-header">
                    <h4 class="box-title">History</h4>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sentence</th>
                            <th>Date Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>    
                        
                    <tbody class="rewroteSetences">
                            
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Sentence</th>
                            <th>Date Created</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">
            </div>
        </div>
        </div>
    </div> -->
    
    
     <!-- Modal -->
    <div class="modal fade" id="editSentence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit sentence</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="formEditSentence">
                        <div class="form-group">
                            <label>Sentence</label>
                            <div id='error2' style='display:none; color:red;'></div>
                            <textarea class="form-control" name="sentence" placeholder="Enter..." rows="6" id="sentenceTextArea" required></textarea>
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['login_id']; ?>">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" >Save</button>
                    </form> 
                </div>
            </div>
        </div>
    </div>
    
</section>
<script>
    $(document).ready(function(){
        $(function () {
            $('data-toggle-tooltip').tooltip();
        })
        
        $("#wordAI").click(function(){
            var sentence = $("textarea[name=sentence]").val();
            sentence.trim();

            if(sentence != ""){
                var data = {wordAI:"ready", sentence: sentence, user_id: <?php echo $_SESSION['login_id']; ?> }
                //console.log(data);
                
                $.ajax({
                    type: "POST",
                    url: "getThis.php",
                    data: data,
                    beforeSend: function(){
                        document.getElementById("loadingSpin").style.display="block";
                        document.getElementById("spinText").innerText = "";
                        document.getElementById("wordAI").disabled = true;
                        
                    },
                    complete: function(){
                        document.getElementById("spinText").innerText = "Spin";
                        document.getElementById("loadingSpin").style.display="none";
                        document.getElementById("wordAI").disabled = false;
                    },
                    success: function(response){
                        //console.log(response);
                        const obj = JSON.parse(response);
                        //console.log(obj);
                        $("textarea[name=sentence]").val(obj.text);
                    }
                    
                })  
                    
            }
        });
        
        
        $("textarea[name=sentence]").on("change paste keyup", function(){
            function countWords(s){
        	   s = s.replace(/(^\s*)|(\s*$)/gi,"");
        	   s = s.replace(/[ ]{2,}/gi," ");
        	   s = s.replace(/\n /,"\n");
        	   return s.split(' ').length;
            }
            
            var enteredWord = countWords($(this).val());
            
            var orig_sentence = $("input[name=orig_sentence]").val();
            var wordCount = countWords(orig_sentence);
            var allowedWordCount = Math.round(wordCount * 0.70);
            //console.log(allowedWordCount);
            
            var wordPercent = Math.floor((enteredWord / allowedWordCount) * 100);
            
            
            //document.getElementById("showWordCharCountRealTime").innerText = "Words: "+wordPercent;
            //console.log($(this).val());
        })
        
        $("#formEditSentence").submit(function(e){
            
            var data = $(this).serializeArray();
            console.log(data);
            
            var splitData = data[2].value.split(",");
            data[data.length] = { name: "sentenceId", value: splitData[0] }
            data[data.length] = { name: "editSentenceSave", value: "save" }
            data[data.length] = { name: "orig_sentence", value: document.getElementById("tobeEditedSentence").innerText }
            
            function countWords(s){
        	        s = s.replace(/(^\s*)|(\s*$)/gi,"");
        	        s = s.replace(/[ ]{2,}/gi," ");
        	        s = s.replace(/\n /,"\n");
        	        return s.split(' ').length;
                }
                
            var wordCount = countWords(splitData[1]);
            var allowedWordCount = Math.round(wordCount * 0.90);
        
            var charCount = splitData[1].length;
            var allowedCharCount = Math.round(charCount * 0.90);
                
            var wordsEntered = countWords(data[0].value);
            var charEntered = data[0].value.length;
            
            if(charEntered > allowedCharCount){
                document.getElementById("error2").style.display="none";
                if(wordsEntered > allowedWordCount){
                    document.getElementById("error2").style.display="none";
                    
                    // continue here
                    $.ajax({
                        type: "POST",
                        url: "insertThis2.php",
                        data: data,
                        success: function(response){
                            if(response == "1"){
                                location.reload();
                            } else{
                                alert("Something went wrong");
                            }
                        }
                    })  
                    
                } else{
                    document.getElementById("error2").innerHTML = "Words Entered: <strong>"+wordsEntered+".</strong> Word count must be at least 100% of original sentence!";
                    document.getElementById("error2").style.display="block";
                }
            }else{
                document.getElementById("error2").innerHTML = "Characters Entered: <strong>"+charEntered+".</strong> Character length must be at least 100% of original sentence!";
                document.getElementById("error2").style.display="block";
            } 
                    
            return false;
        });
        
        $("#editSentence").on("hide.bs.modal",function(){
            document.getElementById("sentenceTextArea").value = "";
            $("#idAndSentence").remove();
        })

        $(".rewroteSetences").on('click', '.editButton', function(e){
            var s1 = e.target.value;
            //console.log(s1);
            if(typeof s1 === "undefined"){
                alert("Problem searching for content. Please try again...");
            } else{
                $("#editSentence").modal("show");
                //console.log(e.target.value);
                $("#formEditSentence").append("<div id='idAndSentence'><input type='hidden' name='rewriteId' value='"+e.target.value+"'></div>");
                var splitData = s1.split("::");
                $("#sentenceTextArea").val(splitData[1]);
            }
        })
        
        $(".rewroteSetences").on('click', '.deleteButton', function(e){
            var s1 = e.target.value;
            if(typeof s1 === "undefined") {
                alert("Problem searching for content. Please try again...")
            } else{
                var decision = confirm("Are you sure you want to delete this?");
                if(decision){
                    var splitData = s1.split(",");
                    var data = {rewrite_id: splitData[0], user_id: splitData[1], deleteThis: "delete"};
                    //console.log(data);
                    $.ajax({
                        type: "POST",
                        url: "insertThis2.php",
                        data: data,
                        success: function(response){
                            //console.log(response);
                            if(response == "1"){
                                location.reload();
                            } else{
                                alert("Something went wrong");
                            } 
                        }
                    }) 
                }
            }
        });
        
        
        $("#formA").submit(function(e){
            $("#messageFailed").fadeOut();
            
            var data = $(this).serializeArray();
            //check if form is complete
            if(data.length != 7) {
                return false; // incomplete form , will not execute
            } else {
       
                //console.log(data);
                var flag = false;
                var sentenceDuplicate = true;
                
                function countWords(s){
        	        s = s.replace(/(^\s*)|(\s*$)/gi,"");
        	        s = s.replace(/[ ]{2,}/gi," ");
        	        s = s.replace(/\n /,"\n");
        	        return s.split(' ').length;
                }
                
                var wordCount = countWords(data[0].value);
                var allowedWordCount = Math.round(wordCount * 0.70);
        
                var charCount = data[0].value.length;
                var allowedCharCount = Math.round(charCount * 0.60);
                
                var wordsEntered = countWords(data[4].value);
                var charEntered = data[4].value.length;
                
                
                var orig_sentence = data[0].value.replace(".","").split(" ");
                var sentence_entered = data[4].value.replace(".","").split(" ");
                
                // check if sentence is a ducplicate 
                if(sentence_entered.length > orig_sentence.length){
                        sentenceDuplicate = false;
                } else if(sentence_entered.length < orig_sentence.length){
                        sentenceDuplicate = false;
                }else {
                    for(var i = 0; i < orig_sentence.length; i++){
                        if(orig_sentence[i].toLowerCase() == sentence_entered[i].toLowerCase()){
                                continue;
                                console.log("the same");
                            }else{
                                console.log("no the same");
                                sentenceDuplicate = false;
                                break;
                            }
                        }
                }
                        
                if(sentenceDuplicate){
                    document.getElementById("error").innerHTML = "Original sentence and Rewritten sentence is the same!";
                    document.getElementById("error").style.display="block";
                    flag = false;
                } else{
                    flag = true;
                    }
                if(flag){
                    //return true;
                    //return true;
                     $.ajax({
                        type: "POST",
                        url: "insertThis2.php",
                        data: data,
                        success: function(response){
                            //console.log(response)
                            //console.log(data[4]['value'].trim().toLowerCase().replace(/\s/g, ''))
                            if(response){
                                var allSentence = document.getElementsByClassName("sentenceOption");
                                //console.log(allSentence)
                                for (var i = 0; i < allSentence.length; i++){
                                    if(allSentence.item(i).innerText.trim().toLowerCase().replace(/\s/g, '') == data[0]['value'].trim().toLowerCase().replace(/\s/g, '')){
                                        //console.log((allSentence.item(i).innerText.toLowerCase()))
                                        //var editedVersion = allSentence.item(i).childNodes[1].childNodes[1].innerText + "&nbsp&nbsp<span style='border:1px solid white; border-radius: 5px'><i class='icon fa fa-check'></i></span>";
                                        //allSentence.item(i).childNodes[1].childNodes[1].innerHTML = editedVersion
                                        //allSentence.item(i).style.pointerEvents = "none";
                                        //allSentence.item(i).style.cursor = "default";
                                        //allSentence.item(i).childNodes[1].childNodes[1].style.backgroundColor = "#00a65a"
                                        
                                        window.scrollTo(0, 0);
                                        $(allSentence.item(i)).fadeOut("slow");
                                        $("#messageSuccess").fadeIn();
                                        document.getElementById("saveSentence").disabled = true
                                    }
                                }
                            } else{
                                $("#messageFailed").fadeIn();
                            }
                        }
                    })  
                    
                    return false;
                    
                } else{
                    return false;
                }  
                /*
                if(charEntered > allowedCharCount){
                         document.getElementById("error").style.display="none";
                        if(wordsEntered > allowedWordCount){
                            document.getElementById("error").style.display="none";
                            
                            
                            
                            
                            
                  
                        } else{
                            document.getElementById("error").innerHTML = "Words Entered: <strong>"+wordsEntered+".</strong> Word count must be at least 70% of original sentence!";
                            document.getElementById("error").style.display="block";
                            flag = false;
                        }
                    } else{
                        document.getElementById("error").innerHTML = "Characters Entered: <strong>"+charEntered+".</strong> Character length must be at least 60% of original sentence!";
                        document.getElementById("error").style.display="block";
                        flag = false;
                    }
                */
            }
        });
    
        $(".sentenceOption").click(function(e){
            
            $("#messageSuccess").fadeOut();
            $("#messageFailed").fadeOut();
            document.getElementById("saveSentence").disabled = false
            
            function countWords(s){
        	   s = s.replace(/(^\s*)|(\s*$)/gi,"");
        	   s = s.replace(/[ ]{2,}/gi," ");
        	   s = s.replace(/\n /,"\n");
        	   return s.split(' ').length;
            }
            
            e.preventDefault();
            var sentenceData = e.target.attributes.value.nodeValue;
            var sentence = e.target.innerText;
            var data = sentenceData.split("::");
            //console.log(data);
            var info = {sentenceId:data[0], orderNo:data[1], parNo:data[2],user_id:<?php echo $_SESSION['login_id']; ?> }
            //console.log(sentenceData);
            $("#wordOrigCount").val(countWords(sentence));
            document.getElementById("formAContent").style.display="block";
            document.getElementById("sentenceWordCharCount").innerHTML = "Words: <strong>"+countWords(sentence)+ "</strong> Characters: <strong>" + sentence.length + "</strong>";
            document.getElementById("textareasentenceWordCharCount").innerHTML = "Words: <strong>"+countWords(sentence)+ "</strong> Characters: <strong>" + sentence.length + "</strong>";
            document.getElementById("tobeEditedSentence").innerHTML = sentence;
            $("input[name=orig_sentence]").val(sentence);
            $("input[name=sentenceId]").val(data[0]);
            $("input[name=sentenceOrderNo]").val(data[1]);
            $("input[name=sentenceParNo]").val(data[2]);
            $("textarea[name=sentence]").focus();
            $("textarea[name=sentence]").val(sentence);
            $(".sentenceHistories").remove();
            
            /*
            $.ajax({
                type: "POST",
                url: "getThis.php",
                data: info,
                success: function(response){
                    //console.log(response);
                    
                    if(response != "empty"){
                        const obj = JSON.parse(response);
                        //console.log(obj[0].sentence);
                        for(var i=0; i < obj.length; i++){
                            $(".rewroteSetences").append("<tr class='sentenceHistories'><td>"+obj[i].sentence+"</td><td>"+obj[i].created_at+"</td><td class='center'><button value='"+obj[i].id+"::"+obj[i].sentence+"' class='btn btn-info btn-xs editButton'><i class='fa fa-magic'></i>&nbsp; Edit</button>&nbsp;<button value='"+obj[i].id+","+<?php echo $_SESSION['login_id']; ?>+"' class='btn btn-danger btn-xs deleteButton'><i class='fa fa-trash'></i>&nbsp; Delete</button></td></tr>");
                        }
                        document.getElementById("rewroteHistoryIntro").style.display="block";
                    } else {
                        $(".rewroteSetences tr").remove();
                        document.getElementById("rewroteHistoryIntro").style.display="none";
                    } 
                    
                }
            }) */
        });
    
        $("#criteria1").change(function(){
            var criteria1 = $(this).val();
            console.log(criteria1);
            
            if(criteria1 == "artIntro"){
                location.href='?page=rewritingNew&articleId=<?php echo $_GET['articleId']; ?>&artIntro=true';
            } else if(criteria1 == "subHContent"){
                location.href='?page=rewritingNew&articleId=<?php echo $_GET['articleId']; ?>&subHContent=true';
            } else if(criteria1 == "conclu") {
                location.href='?page=rewritingNew&articleId=<?php echo $_GET['articleId']; ?>&conclu=true';
            } else{
                 location.href='?page=rewritingNew&articleId=<?php echo $_GET['articleId']; ?>';
            }
        })
        
        $("#criteria2").change(function(){
            var criteria2 = $(this).val();
            location.href="?page=rewritingNew&articleId=<?php echo $_GET['articleId']; ?>&subHContent=true&subHId="+criteria2;
        })
    });
    function countWords(s){ s = s.replace(/(^\s*)|(\s*$)/gi,""); s = s.replace(/[ ]{2,}/gi," "); s = s.replace(/\n /,"\n"); return s.split(' ').length; }
    function CheckInputText(x){ 
        var f = $("#wordOrigCount").val();
        wordLim = f*.7;
        if(countWords(x)<wordLim){ 
            document.getElementById("wordAI").disabled = true;
            document.getElementById("saveSentence").disabled = true;
            document.getElementById("textareasentenceWordCharCount").innerHTML = "Words: <strong style='color: red'>"+countWords(x)+ "</strong> Characters: <strong>" + x.length + "</strong> (<strong style='color: red'> Word count must be at least 70% of original sentence</strong> )"; 
        }else{
            document.getElementById("wordAI").disabled = false;
            document.getElementById("textareasentenceWordCharCount").innerHTML = "Words: <strong>"+countWords(x)+ "</strong> Characters: <strong>" + x.length + "</strong>"; 
            document.getElementById("saveSentence").disabled = false;
        }
    }
    
    function perfectTense(){
        var sentence = $("textarea[name=sentence]").val();
        if(sentence != ""){
            var data = {"text":sentence, "perfectTense":"set"}
            $.ajax({
                type: "POST",
                url: "insertThis2.php",
                data: data,
                beforeSend: function(){
                    document.getElementById("loadingPerfectTense").style.display="block";
                    document.getElementById("ptText").innerText = "";
                    document.getElementById("perfectTense").disabled = true;
                    
                },
                complete: function(){
                    document.getElementById("ptText").innerText = "PerfectTense";
                    document.getElementById("loadingPerfectTense").style.display="none";
                    document.getElementById("perfectTense").disabled = false;
                },
                success: function(response){
                    //console.log(response);
                    const obj = JSON.parse(response);
                    //console.log(obj);
                    $("textarea[name=sentence]").val(obj.corrected);
                }
            })
        } 
    }
    
</script>    
    
    