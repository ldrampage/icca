<?php
    $r_article = $icca_obj->getRewrittenArticle($_GET['id']); 
    
    //original article
    $originalIntro = $icca_obj->getOriginalIntro($r_article['article_id']); 
    $originalSubH = $icca_obj->getOriginalSubH($r_article['article_id']); 
    
    //rewritten version
    $rewrittenIntro = $icca_obj->getRewrittenIntro($_GET['id']); 
    $rewrittenSubC = $icca_obj->getRewrittenSubC($_GET['id']); 
    
    // echo "<script>console.log(".json_encode($rewrittenSubC).")</script>";
?>

<section class="content" style="min-height:300px">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <form name="user" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Rewritten Article Details</h4>
                        <div class="pull-right" style="margin-top: -25px;">
                            <a href="?page=rewritten-articles"><label class="btn btn-xs btn-primary">Go Back</label></a> 
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="state" class="control-label">Original Article:</label>
                                    <p>
                                        <?php 
                                            $article = $icca_obj->getArticleById($r_article['article_id']);
                                            echo $article['title'];
                                        ?>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label for="state" class="control-label">Rewritten By:</label>
                                    <p>
                                        <?php 
                                            $user = $icca_obj->getUserById($r_article['user_id']);
                                            $fullName = $user['fname'].' '.$user['lname'];
                                            echo $fullName;
                                        ?>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label for="state" class="control-label">Created At:</label>
                                    <p><?= date("F d, Y  h:iA", strtotime($r_article['created_at'])); ?></p>
                                </div>
                                <div class="form-group">
                                    <label for="state" class="control-label">Last Edited At:</label>
                                    <p><?= date("F d, Y  h:iA", strtotime($r_article['last_edited_at'])); ?></p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="state" class="control-label">Status:</label>
                                    <?php 
                                        if($r_article['status'] == 0) { //pending
                                            echo '<b><p class="text-light-blue">PENDING</p></b>';
                                        } else if($r_article['status'] == 1) { //approved
                                            echo '<b><p class="text-green">APPROVED</p></b>';
                                        } else { //rejected
                                            echo '<b><p class="text-red">REJECTED</p></b>';
                                        }  
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label for="state" class="control-label">Reviewed By:</label>
                                    <p>
                                        <?php 
                                            if($r_article['reviewed_by']) {
                                                $user = $icca_obj->getUserById($r_article['reviewed_by']);
                                                if($user) echo $user['fname'].' '.$user['lname'];
                                                else echo "None";
                                            } else {
                                                echo "None";
                                            }
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="content" style="min-height:300px">
    <div class="row">
        <div class="col-lg-6 col-md-12 col-xs-12">
            <form name="user" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Original Article</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12">
                                <b><p><?= $article['title'] ?></p></b>
                                
                                <?php 
                                    foreach($originalIntro as $intro) {
                                        echo "<p>".$intro['intro_paragraph']."</p>";
                                    }
                                    
                                    $subheadings = array();
                                    $count = 1;
                                    foreach($originalSubH as $subh) {
                                        $subheadings[] = $subh['subheading'];
                                        echo "<i><p>".$count++.". ".$subh['subheading']."</p></i>";
                                        
                                        $originalSubC = $icca_obj->getOriginalSubC($subh['_id']); 
                                        foreach($originalSubC as $subc) {
                                            echo "<p>".$icca_obj->htmlallentities($subc['content'])."</p>";
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="col-lg-6 col-md-12 col-xs-12">
            <form name="user" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Rewritten Version</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12">
                                <b><p><?= $article['title'] ?></p></b>
                                
                                <?php 
                                    foreach($rewrittenIntro as $intro) {
                                        echo "<p>".$intro['paragraph']."</p>";
                                    }
                                    
                                    $current_heading = null;
                                    foreach($rewrittenSubC as $subc) {
                                        $subheading = $icca_obj->getRewrittenSubH($subc['subh_id']);
                                        $key = array_search($subheading['subheading'], $subheadings);
                                        
                                        echo "<i><p>".++$key.". ".$subheading['subheading']."</p></i>";
                                        echo "<p>".$icca_obj->htmlallentities($subc['paragraph'])."</p>";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
