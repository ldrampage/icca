<?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1); 
    $message = "";
    
    $isContentAdmin = $icca_new_obj->isContentAdmin($_SESSION['login_id']); 
    $isAdmin = $icca_new_obj->isAdmin($_SESSION['login_id']);
    if(!$isContentAdmin) {
        header('Location: http://icca.authoritativecontent.net/index.php'); 
    }
    
    //export rewrites
    if(isset($_POST['exportArticle']) || isset($_POST['regenArticle'])) {
        $message = $icca_new_obj->exportArticle($_POST);
        // echo "<script>console.log(".json_encode($message).")</script>";
    }
?>

<section class="content" >
    <div class="row">
        <div class="col-xs-12">
            <?php if($message != "") { ?>
                <div class="row">
                    <?php if($message['status'] == "invalid") { ?>
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                Each sentence of the article should have at least ONE available rewritten version in order to export the article.
                            </div>
                        </div>
                    <?php } else if($message['status'] == "incomplete") { ?>
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Incomplete!</h4>
                                The article should have an introduction, at least ONE subheading, and a conclusion.
                            </div>
                        </div>
                    <?php } else if($message['status'] == "success") { ?>
                        <div class="col-md-12">
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-check"></i> Success!</h4>
                                Rewritten version of the article was exported successfully.
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
                                Error occurred when exporting the article. Please try again later.
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <div class="col-xs-12">
            <form name="user" method="post"  enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 style="text-align: center; color: darkred; font-weight: bold;">Export Article</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Category:</label>
                                    <select class="form-control select2 select2-hidden-accessible" name="sel_category" id="sel_category" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option value="">Fetching...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Article:</label>
                                    <select class="form-control select2 select2-hidden-accessible" name="sel_article" id="sel_article" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value="">Fetching...</option>
                                    </select>
                                    <button type="submit" class="btn btn-success btn-md" name="exportArticle" id="exportArticle" style="position: absolute;margin-left: 25px;">Export</button>
                                </div>
                            </div>
                        </div>
                        
                        <?php if($message != "" && $message['status'] == 'success') { ?>
                            <div class="row" id="export_results">
                                <hr>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                   <div class="form-group">
                                        <label>
                                            Original Article:&nbsp;
                                            <span id="sel_art_title" style="font-weight:normal;font-style:italic"><?= $message['article_title'] ?></span>
                                        </label>
                                        <div class="form-group mb-0">
                                            <label>
                                                <small id="o_count" class="form-text text-muted" style="font-weight: 500;">
                                                    <?php
                                                        $o_sentence = $icca_new_obj->convert_smart_quotes($icca_new_obj->htmlallentities($message['original']));
                                                        $o_w_count = str_word_count(utf8_decode(trim(preg_replace('!\s+!', ' ', $o_sentence))));
                                                        $o_c_count = strlen(utf8_decode(trim(preg_replace('!\s+!', ' ', $o_sentence))));
                                                        echo "(Word count: ".$o_w_count.", Character count: ".$o_c_count.")";
                                                    ?>
                                                </small>
                                            </label>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            <p>
                                                               <?= nl2br($icca_new_obj->htmlallentities($o_sentence)) ?> 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                   <div class="form-group">
                                        <label>Rewritten Version:</label>
                                        <div class="pull-right" style="margin-top: -5px;">
                                            <a href="javascript:copyscape(<?= htmlspecialchars(json_encode($icca_new_obj->convert_smart_quotes($message['rewrite']))) ?>)"><label class="btn btn-xs btn-primary">CopyScape</label></a> 
                                            <a href="<?= $message['link'] ?>" download><label class="btn btn-xs btn-success">Download</label></a> 
                                            <button type="submit" class="btn btn-xs btn-danger" name="regenArticle" id="regenArticle">Regenerate</button>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>
                                                <small id="o_count" class="form-text text-muted" style="font-weight: 500;">
                                                    <?php
                                                        $n_sentence = $icca_new_obj->convert_smart_quotes($message['rewrite']);
                                                        $n_w_count = str_word_count(utf8_decode(trim(preg_replace('!\s+!', ' ', $n_sentence))));
                                                        $n_c_count = strlen(utf8_decode(trim(preg_replace('!\s+!', ' ', $n_sentence))));
                                                        echo "(Word count: ".$n_w_count.", Character count: ".$n_c_count.")";
                                                    ?>
                                                </small>
                                            </label>
                                        </div>
                                        <div class="form-group mb-0" id="copyscape_result" style="display:none">
                                            <label>CopyScape Result:</label>&nbsp;&nbsp;
                                            <span id="cs_result_url" style="display:inline-block"></span>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            <p id="rewrite_preview">
                                                                <?= nl2br($icca_new_obj->htmlallentities($n_sentence)) ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <!--<div class="modal-footer">-->
                    <!--    <button type="submit" class="btn btn-success btn-sm" name="exportArticle" id="exportArticle">Export</button>-->
                    <!--</div>-->
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    //get selected article after exporting (for regenerate)
    const sel_aid = '<?= ($message != "" && $message['status'] == "success") ? $message['article_id'] : '' ?>';
    const sel_cid = '<?= ($message != "" && $message['status'] == "success") ? $message['category_id'] : '' ?>';
    
    //category dropdown
    jQuery.ajax({
        url :"./ajax/export-category-dropdown.php",
        type: "GET", 
        data: { 'cid' : sel_cid },
        success: function(data) {
            $('#sel_category').html(data);
        },
        error: function(xhr, status, error){  
            console.log(xhr)
            console.log(xhr.responseText, status, error);
            alert("Error occurred when fetching categories for articles. Please try again later.");
        }
    });
    
    //article (available for export) dropdown
    jQuery.ajax({
        url :"./ajax/export-article-dropdown.php",
        type: "GET", 
        data: { 'aid' : sel_aid, 'cid' : sel_cid },
        success: function(data) {
            $('#sel_article').html(data);
        },
        error: function(xhr, status, error){  
            console.log(xhr)
            console.log(xhr.responseText, status, error);
            alert("Error occurred when fetching export-ready articles for Articles dropdown. Please try again later.");
        }
    });
    
    $('#sel_category').on('change', function() {
        $('#sel_article').html('<option value="">Fetching...</option>');
    
        //article (available for export) dropdown
        jQuery.ajax({
            url :"./ajax/export-article-dropdown.php",
            type: "GET", 
            data: { 'aid' : sel_aid, 'cid' : this.value },
            success: function(data) {
                $('#sel_article').html(data);
            },
            error: function(xhr, status, error){  
                console.log(xhr)
                console.log(xhr.responseText, status, error);
                alert("Error occurred when fetching export-ready articles for Articles dropdown. Please try again later.");
            }
        }); 
    });

    function cleanString(str) {
        return str.replace(/\s+/g,' ').trim();
    }
    
    function wordCount(str) {
        return str.split(' ').length;
    }

    function copyscape(rewrite) {
        //calculate costs
        const word_count = wordCount(cleanString(rewrite));
        let cost = 0;
        if(word_count <= 200) {
            cost = 0.03;   
        }
        else {
            cost = 0.03 + (0.01 * Math.ceil((word_count - 200)/100));
        }
        
        confirmed = confirm(`Checking this rewritten article for DUPLICATES via CopyScape will cost $${cost.toFixed(2)}. Press OK to proceed.`);
        if (confirmed) {
            $('#cs_result_url').html('<i>Fetching results...</i>');
            $('#copyscape_result').show();
            jQuery.ajax({
                url :"./ajax/copyscape-api.php",
                type: "GET", 
                data: { rewrite : rewrite },
                success: function(data) {
                    const data1 = JSON.parse(data);
                    if('error' in data1) {
                        $('#cs_result_url').html('<i>Error occurred. Please check console for details (Shift+Ctrl+I).</i>');
                        console.log(data1['error']);
                    } else if('allviewurl' in data1) {
                        $('#cs_result_url').html('<a href="'+data1['allviewurl']+'" target="_blank">'+data1['allviewurl']+'</a>');
                    } 
                    console.log(data1);
                },
                error: function(xhr, status, error){  
                    console.log(xhr)
                    console.log(xhr.responseText, status, error);
                    alert("Error occurred when confirming the uniqueness of the content via the CopyScape API. Please try again later.");
                }
            });
        }
    }
</script>