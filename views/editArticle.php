<?php 
if(!isset($_GET['articleId']) || empty($_GET['articleId'])){
        echo "<script> location.href='?page=createNewArticle'</script>";
    } else{
        if(!$iccaFunc->articleExist($_GET['articleId'])){
            echo "<script> location.href='?page=createNewArticle'</script>";
        }
    }

if(isset($_POST['saveEditSentence'])){
    $message = $iccaFunc->editSentence($_POST);
}

if(isset($_POST['formA'])){
    $message = $iccaFunc->editIntroductionTitle($_POST);
}

if(isset($_POST['formB'])){
    $message = $iccaFunc->editSubheading($_POST);
}

if(isset($_POST['formD'])){
    $message = $iccaFunc->editArticleTitle($_POST);
}

if(isset($_POST['formE'])){
    //print_r($_POST);
    $message = $iccaFunc->editArticleConcluTitle($_POST);
}
    
?>

<section class="content">
    <?php if(isset($message)): ?>
    <div class="row">
        <div class="col-md-12">
            <?php if($message == "success:editsentence"): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                You have successfully edited a sentence.
            </div>
            <?php endif; ?>
            <?php if($message == "success:editintrotitle"): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                You have successfully edited the introduction title.
            </div>
            <?php endif; ?>
            <?php if($message == "success:editsubhtitle"): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                You have successfully edited the item title.
            </div>
            <?php endif; ?>
            <?php if($message == "success:editarticletitle"): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                You have successfully change the article name.
            </div>
            <?php endif; ?>
            
            <?php if($message == "success:editconclusiontitle"): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                You have successfully change the conclusion title.
            </div>
            <?php endif; ?>
            
            <?php if($message == "0"): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Error!</h4>
                Something went wrong. Please try again.
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-3">
            <?php 
                $article = $iccaFunc->fetchArticleByid($_GET['articleId']);
            ?>
            <div class="box">
                <div class="box-header">
                    <h4 class="box-title">Article Title</h4>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" type="button" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <form method="POST" id="formD">
                    <h4 class="box-title center"><?php echo $article[0]['title']; ?></h4>
                    <div class="form-group">
                        <label>Enter changes: </label>
                        <input type="hidden" name="article_id" value="<?php echo $article[0]['id']?>" >
                        <input type="hidden" name="previous_title" value="<?php echo $article[0]['title']?>" >
                        <input type="text" name="changes" class="form-control" required/>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['login_id']; ?>"/>
                    <input type="submit" class="btn btn-success btn-small" name="formD" value="save"/>
                    </form>
                </div>
            </div>
            
            <?php 
                $articleIntros = $iccaFunc->fetchArticleIntroduction($_GET['articleId']);
                //print_r($articleIntros);
            ?>
            <div class="box">
                <div class="box-header">
                    <h4 class="box-title">Article Introduction Title</h4>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" type="button" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php if(!empty($articleIntros)): ?>
                    <?php if($articleIntros[0]['title'] != "none"){ ?>
                    <form method="POST" id="formA">
                    <div class="form-group">
                        <input class="form-control" type="text" name="orig_title" value="<?php echo $articleIntros[0]['title']; ?>" readonly required/>
                    </div>
                    <div class="form-group">
                        <label>Enter changes: </label>
                        <input type="text" name="changes" class="form-control" required/>
                    </div>
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['login_id']; ?>"/>
                    <input type="hidden" name="introId" value="<?php echo $articleIntros[0]['id']; ?>">
                    <input type="submit" class="btn btn-success btn-small" name="formA" value="save"/>
                    </form>
                    <?php } else {?>
                    <div class="form-group">
                        <h4 class="center box-title">No Title</h4>
                    </div>
                    <?php } ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php 
                $subheadingTitles = $iccaFunc->fetchSubheadingTitle($_GET['articleId']);
                
            ?>
            <div class="box">
                <div class="box-header">
                    <h4 class="box-title">Article Item Titles</h4>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" type="button" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php if(!empty($subheadingTitles)) { ?>
                    <form method="POST">
                        <div class="form-group">
                            <label>Choose an Introduction Title</label>
                            <select class="form-control select2" id="articleIntroList" name="subHId">
                                <?php foreach($subheadingTitles as $subH): ?>
                                <option value="<?php echo $subH['id']."::".$subH['title']; ?>"> <?php echo $subH['title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Enter changes: </label>
                            <input type="text" name="changes" class="form-control" required/>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['login_id']; ?>"/>
                            <input type="submit" class="btn btn-success btn-small" name="formB" value="save"/>

                        </div>
                    </form>
                    <?php } else { ?>
                    <div class="form-group">
                        <h4 class="center box-title">No Item</h4>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php 
                $artConclusion = $iccaFunc->fetchArtConclusion($_GET['articleId']);
                //print_r($artConclusion);
            ?>
            <div class="box">
                <div class="box-header">
                    <h4 class="box-title">Article Conclusion</h4>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" type="button" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php if(!empty($artConclusion)): ?>
                    <?php if($artConclusion[0]['title'] != "none"){ ?>
                    <form method="POST" id="formE">
                    <div class="form-group">
                        <input type="text" class="form-control" name="orig_title" value="<?php echo $artConclusion[0]['title']; ?>" readonly required/>
                    </div>
                    <div class="form-group">
                        <label>Enter changes: </label>
                        <input type="text" name="changes" class="form-control" required/>
                    </div>
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['login_id']; ?>"/>
                    <input type="hidden" name="concluId" value="<?php echo $artConclusion[0]['id']; ?>">
                    <input type="submit" class="btn btn-success btn-small" name="formE" value="save"/>
                    </form>
                    <?php } else {?>
                    <div class="form-group">
                        <h4 class="center box-title">No Title</h4>
                    </div>
                    <?php } ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="box">
                <div class="box-header">
                    <h4 class="box-title">Sentence</h4>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" type="button" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <?php 
                            $allContent = $iccaFunc->fetchAllContent($_GET['articleId']);
                        ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Article components </label>
                                <select class="form-control select2" id="selectComponent" >
                                    <option value=""></option>
                                    <option value="introduction" <?php if(isset($_GET['introduction'])){echo "selected"; }?>>Introduction</option>
                                    <option value="subheading" <?php if(isset($_GET['subheading'])){echo "selected"; }?>>Items</option>
                                    <option value="conclusion" <?php if(isset($_GET['conclusion'])){echo "selected"; }?>>Conclusion</option>
                                </select>
                            </div>
                        <?php if(isset($_GET['introduction'])){ $articleInt = $iccaFunc->fetchContentByComponent($_GET['articleId'],'introduction'); } ?>
                        
                        <?php if(isset($_GET['subheading'])){ $articleItems = $iccaFunc->fetchContentByComponent($_GET['articleId'],'subheading'); ?> 
                        
                            <div class="form-group">
                                <label>Choose the Item Title </label>
                                <select class="form-control select2" id="selectItemTitle" >
                                    <option value=""></option>
                                    <?php foreach($articleItems as $item) :?>
                                    <option value="<?php echo $item['id'] ?>" <?php if(isset($_GET['contentId'])) {if($_GET['contentId'] == $item['id' ]) { echo "selected"; } } ?> ><?php echo $item['title']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                        <?php } ?>
                    
                        <?php  if(isset($_GET['conclusion'])){ $articleConclu = $iccaFunc->fetchContentByComponent($_GET['articleId'],'conclusion'); } ?>
                    
                        </div>
                    </div>
                    <?php if(isset($articleInt) || isset($_GET['contentId']) || isset($articleConclu)): ?>
                    
                    <?php
                        if(!empty($articleInt)) {$sentences = $iccaFunc->fetchSentences($articleInt[0]['id']); } else {$sentences = array(); }
                        if(isset($_GET['contentId'])) { $sentences = $iccaFunc->fetchSentences($_GET['contentId']); }
                        if(!empty($articleConclu)) { $sentences = $iccaFunc->fetchSentences($articleConclu[0]['id']); } else { $sentence = array(); }
                        $paragraph = $iccaFunc->mergeSentences($sentences);
                    ?>
                    
                    <div class="row">
                        <div class="col-md-3">
                        <label>Choose which paragraph</label>
                        <?php if(!empty($paragraph)){ ?>
                        <?php $parNo=1; foreach($paragraph as $par): ?>
                        <?php if(isset($articleInt)): ?>
                        <a href="?page=editArticle&articleId=<?php echo $_GET['articleId']; ?>&introduction=true&contentId=<?php echo $articleInt[0]['id']; ?>&parNo=<?php echo $parNo;?>">
                            <p class="text-break" style="background-color:#337ab7; border-radius: 5px; padding: 10px; color:white;" >
                            <?php if (htmlentities($par) == "") echo $par; else echo htmlentities($par); ?>
                            </p>
                        </a>
                        <?php endif; ?>
                        
                        <?php if(isset($_GET['contentId']) && isset($_GET['subheading'])): ?>
                        <a href="?page=editArticle&articleId=<?php echo $_GET['articleId']; ?>&subheading=true&contentId=<?php echo $_GET['contentId']; ?>&parNo=<?php echo $parNo;?>">
                            <p class="text-break" style="background-color:#337ab7; border-radius: 5px; padding: 10px; color:white;" >
                            <?php if (htmlentities($par) == "") echo $par; else echo htmlentities($par); ?>
                            </p>
                        </a>
                        <?php endif; ?>
                        
                        <?php if(isset($articleConclu) && isset($_GET['conclusion'])): ?>
                        <a href="?page=editArticle&articleId=<?php echo $_GET['articleId']; ?>&conclusion=true&contentId=<?php echo $articleConclu[0]['id']; ?>&parNo=<?php echo $parNo;?>">
                            <p class="text-break" style="background-color:#337ab7; border-radius: 5px; padding: 10px; color:white;" >
                            <?php if (htmlentities($par) == "") echo $par; else echo htmlentities($par); ?>
                            </p>
                        </a>
                        <?php endif; ?>
                        
                        <?php $parNo++; endforeach; ?>
                        </div>
                        <?php } else { ?>
                        <div class="col-md-3">
                            <h5 class="box-title">No content</h5>
                        </div>
                        
                        <?php }?>
                        
                        <?php if(isset($_GET['parNo'])) { ?>
                        <?php
                            $sentence = $iccaFunc->fetchSentenceByContentIdAndParNo($_GET['contentId'], $_GET['parNo']);
                            //print_r($sentence);
                        ?>
                        <div class="col-md-9">
                            <label>Choose a sentence:</label>
                            <?php foreach($sentence as $sent) {?>
                            <a href="" class="sentenceOption" value="<?php echo $sent['id']; ?>">
                                <p class="text-break" style="background-color:#337ab7; border-radius: 5px; padding: 5px; color:white;" >
                                    <?php if(htmlentities($sent['sentence']) == "") echo $sent['sentence']; else echo htmlentities($sent['sentence']); ?>
                                </p>
                            </a>
                            
                            <?php } ?>
                            <div id="displayChosenSentence">
                            </div>
                            <form method="POST" id="formC" style="display:none;">
                                <div class="form-group" id="hiddenId">
                                </div>
                                <div class="form-group">
                                    <label>Edit sentence</label>
                                    <textarea name="editedSentence" class="form-control" rows="5" required></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['login_id']; ?>"/>
                                    <input type="submit" class="btn btn-success btn-small" name="saveEditSentence" value="save"/>
                                </div>
                            </form> 
                            
                        </div>
                        <?php } ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="box-footer">
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function(){
    $("#selectComponent").change(function(){
       var component = $(this).val();
       if($(this).val() != ""){
           location.href="?page=editArticle&articleId=<?php echo $_GET['articleId'];?>&"+component+"=true";
       }
    });
    
    $("#selectItemTitle").change(function(){
        var id = $(this).val();
        if($(this).val() != ""){
            location.href="?page=editArticle&articleId=<?php echo $_GET['articleId'];?>&subheading=true&contentId="+id;
        }
    })


    $("#selectContent").change(function(){
        var id = $(this).val();
        if($(this).val() != ""){
            location.href='?page=editArticle&articleId=<?php echo $_GET['articleId']; ?>&contentId='+id;
        }
    })
    
    $(".sentenceOption").on("click",function(e){
        var id = e.currentTarget.attributes.value.value;
        e.preventDefault(); 
        $("#sentenceCaption").remove();
        $("#hiddens").remove();
        $("#displayChosenSentence").append("<div id='sentenceCaption'><hr><p class='text-break' style='background-color:#333; border-radius: 5px; padding: 5px; color:white;' >"+e.target.innerText+"</p></div>");
        $("#hiddenId").append("<div id='hiddens'><input type='hidden' value='"+id+"' name='chosenSentenceId' required/><input type='hidden' name='previous_sentence' value='"+e.target.innerText+"' required/></div>");
        document.getElementById("formC").style.display="block";
        $("textarea[name=editedSentence]").focus();
    });

});
</script>
