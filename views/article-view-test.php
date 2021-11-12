<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1); 
    $message = "";
    $action = "";
    
    //updating a sentence
    if(isset($_POST['updateSentence'])) {
        $message = $icca_new_obj->updateSentence($_POST);
        $type = 'update';
    }
    
    //adding a sentence
    if(isset($_POST['addSentence'])) {
        $message = $icca_new_obj->addSentence($_POST);
        $type = 'add';
    }
    
    $article = $icca_new_obj->getArticleById($_GET['aid']);
    if(empty($article)) {
        echo "<script>window.location = 'index.php?page=createArticleNew'; </script>";
    } else {
        $orig_article_content = "";
        $sentences = [];
        
        //get contents of article (tbl_contents)
        $contents = $icca_new_obj->getContents1('WHERE article_id = '.$_GET['aid'].' ORDER BY FIELD(type, "introduction", "subheading", "conclusion")');
        $subh_ids = array();
        $subh_titles = array();
        
        //get sentences of article (intro, subh, conclusion)
        $intro_sentences = $icca_new_obj->getSentences('WHERE article_id = '.$_GET['aid'].' AND content_id = '.$contents[0]['id']. ' ORDER BY paragraph_no, order_no');
        
        //gets ids and titles of content subheadings (tbl_contents)
        for($i=1;$i<sizeof($contents)-1;$i++) {
            $subh_ids[] = $contents[$i]['id'];
            $subh_titles[] = $contents[$i]['title'];
        }
        $subh_sentences = $icca_new_obj->getSentences('WHERE article_id = '.$_GET['aid'].' AND content_id IN ('.implode(",", $subh_ids). ') ORDER BY content_id, paragraph_no, order_no');
        $concl_sentences = $icca_new_obj->getSentences('WHERE article_id = '.$_GET['aid'].' AND content_id = '.end($contents)['id']. ' ORDER BY paragraph_no, order_no');
        
        //insert intro sentences
        if($contents[0]['title'] && $contents[0]['title'] != 'none' && $contents[0]['title'] != '') {
            $orig_article_content .= $contents[0]['title']."\n\n";
        }
        $curr_par = $intro_sentences[0]['paragraph_no'];
        foreach($intro_sentences as $sent) {
            array_push($sentences, $sent);
            $orig_article_content .= $sent['sentence']." ";
            if($curr_par != $sent['paragraph_no']) {
                $orig_article_content .= "\n\n";
                $curr_par = $sent['paragraph_no'];
            }
        }
        $orig_article_content .= "\n\n";
        
        //insert subh sentences
        $orig_article_content .= $contents[1]['title']."\n\n";
        $curr_par = $subh_sentences[0]['paragraph_no'];
        $curr_subh = $icca_new_obj->getSentenceById($subh_sentences[0]['id']);
        $subheading = $curr_subh['content_id'];
        $count = 1;
        foreach($subh_sentences as $sent) {
            array_push($sentences, $sent);
            $curr_subh = $icca_new_obj->getSentenceById($sent['id']);
            if($curr_subh['content_id'] != $subheading) {
                $orig_article_content .= "\n\n".$contents[++$count]['title']."\n\n";
                $orig_article_content .= $sent['sentence']." ";
                $subheading = $curr_subh['content_id'];
                $curr_par = $curr_subh['paragraph_no'];
            } else {
                $orig_article_content .= $sent['sentence']." ";
            }
            
            if($curr_par != $sent['paragraph_no']) {
                $orig_article_content .= "\n\n";
                $curr_par = $sent['paragraph_no'];
            }
        }
        $orig_article_content .= "\n\n";
        
        //insert conclusion sentences
        if(end($contents)['title'] && end($contents)['title'] != 'none' && end($contents)['title'] != '' && strpos(end($contents)['title'], '[article-') === false) {
            $orig_article_content .= end($contents)['title']."\n\n";
        }
        $curr_par = $concl_sentences[0]['paragraph_no'];
        foreach($concl_sentences as $sent) {
            array_push($sentences, $sent);
            $orig_article_content .= $sent['sentence']." ";
            if($curr_par != $sent['paragraph_no']) {
                $orig_article_content .= "\n\n";
                $curr_par = $sent['paragraph_no'];
            }
        }
    }
?>

<section class="content" style="min-height:fit-content">
    <?php if($message != "") { ?>
        <div class="row">
            <?php if($message == true) { ?>
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        Sentence <?= $type == 'update' ? 'updated' : 'added' ?> successfully.
                     </div>
                </div>
            <?php } else { ?>
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
                        Error occurred when <?= $type == 'update' ? 'updating' : 'adding' ?> the sentence. Please try again later.
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="widget-content">
                <div class="box box-success collapsed-box mb-0">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= $article['title'] ?></h3>
                        <div class="box-tools pull-right" style="margin-top:3px">
                            <button type="button" style="color:white;padding:1px 5px" class="btn btn-xs btn-success btn-box-tool" data-widget="collapse">Preview</button>
                            <a href="?page=createArticleNew"><label class="btn btn-xs btn-primary">Go Back</label></a> 
                        </div>
                    </div>
                    <div class="box-body">
                        <p>
                            <?= nl2br($icca_new_obj->convert_smart_quotes($icca_new_obj->htmlallentities($orig_article_content))) ?> 
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="widget-content ">
                <div id="print" class="myDivToPrint">
                    <div class="box mb-0">
                        <div class="box-header">
                            <div class="row"> 
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <h3 class="box-title" style="font-weight: bold;">Sentences</h3>
                                    <div class="pull-right" style="margin-bottom:5px;">
                                        <a href="javascript:showAddModal()">
                                            <label class="btn btn-xs btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add sentence</label>
                                        </a> 
                                    </div>
                                </div>
                            </div> 
                            
                            <hr style="margin-top: 5px;">
                        </div>
                        <div class="box-body table-responsive">
                            <table id="example1" class="table table-bordered table-striped table-hover">
                                <thead style="background:#2b415e; color:#fff;">
                                    <tr>
                                        <th class="mwidth-10">#</th>
                                        <th class="mwidth-250">Sentence</th>
                                        <th class="mwidth-180">Type and Title</th>
                                        <th class="mwidth-100">Rewrites</th>
                                        <th class="mwidth-100">Pending</th>
                                        <th class="mwidth-100">Approved</th>
                                        <th class="mwidth-100">Rejected</th>
                                        <th class="mwidth-180">Actions</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php 
                                        //get contents of article (tbl_contents)
                                        $contents = $icca_new_obj->getContents1('WHERE article_id = '.$_GET['aid'].' ORDER BY FIELD(type, "introduction", "subheading", "conclusion")');
                                        $subh_ids = array();
                                        $subh_titles = array();
                                        
                                        //get sentences of article (intro, subh, conclusion)
                                        $intro_sentences = $icca_new_obj->getSentences('WHERE article_id = '.$_GET['aid'].' AND content_id = '.$contents[0]['id']. ' ORDER BY paragraph_no, order_no');
                                        
                                        //gets ids and titles of content subheadings (tbl_contents)
                                        for($i=1;$i<sizeof($contents)-1;$i++) {
                                            $subh_ids[] = $contents[$i]['id'];
                                            $subh_titles[] = $contents[$i]['title'];
                                        }
                                        $subh_sentences = $icca_new_obj->getSentences('WHERE article_id = '.$_GET['aid'].' AND content_id IN ('.implode(",", $subh_ids). ') ORDER BY content_id, paragraph_no, order_no');
                                        $concl_sentences = $icca_new_obj->getSentences('WHERE article_id = '.$_GET['aid'].' AND content_id = '.end($contents)['id']. ' ORDER BY paragraph_no, order_no');
                                    
                                        $sentences = array_merge($intro_sentences, $subh_sentences, $concl_sentences);
                                        
                                        $count = 0;
                                        foreach ($sentences as $sent):
                                    ?>
                                    <tr>
                                        <td><?= ++$count; ?></td>
                                        <td><?= $icca_new_obj->htmlallentities(trim($sent['sentence'])); ?></td>
                                        <td>
                                            <?php 
                                                $content = $icca_new_obj->getContentById($sent['content_id']);
                                                
                                                if($content['title'] && $content['title'] != 'none' && $content['title'] != '' &&  strpos($content['title'], '[article-') === false) {
                                                    echo "<b>".ucfirst($content['type'])."</b> - ".trim($icca_new_obj->htmlallentities($content['title']));    
                                                } else {
                                                    echo "<b>".ucfirst($content['type'])."</b>";    
                                                }
                                            ?>
                                        </td>
                                        <td>
                                          <?php 
                                            $rewrites = $icca_new_obj->getRewrittenArticles('WHERE sentence_id = '.$sent['id']);
                                            $all = 0;
                                            $pending = 0;
                                            $approved = 0;
                                            $rejected = 0;
                                            
                                            foreach($rewrites as $rewrite) {
                                                $all++;
                                                if($rewrite['status'] == 0) $pending++;
                                                else if($rewrite['status'] == 1) $approved++;
                                                else if($rewrite['status'] == 2) $rejected++;
                                            }
                                            
                                            echo $all;
                                          ?>  
                                        </td>
                                        <td><?= $pending ?></td>
                                        <td><?= $approved ?></td>
                                        <td><?= $rejected ?></td>
                                        <td class="center">
                                            <a href="javascript:showModal(<?= $sent['id'] ?>, <?= htmlspecialchars(json_encode($icca_new_obj->convert_smart_quotes($icca_new_obj->htmlallentities($sent['sentence'])))) ?>)">
                                                <button id="edit_<?= $sent['id'] ?>" class="btn btn-warning btn-xs mtop-6">
                                                    <i class="fa fa-pencil-alt"></i>&nbsp;&nbsp;Edit
                                                </button>
                                            </a>
                                            <a href="javascript:confirmDelete(<?= $sent['id'] ?>)">
                                                    <button class="btn btn-danger btn-xs mtop-6">
                                                        <i class="fa fa-trash"></i>&nbsp;&nbsp;Delete
                                                    </button>
                                                </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                
                                <tfoot>
                                    <tr>
                                        <th class="mwidth-10">#</th>
                                        <th class="mwidth-250">Sentence</th>
                                        <th class="mwidth-180">Type and Title</th>
                                        <th class="mwidth-100">Rewrites</th>
                                        <th class="mwidth-100">Pending</th>
                                        <th class="mwidth-100">Approved</th>
                                        <th class="mwidth-100">Rejected</th>
                                        <th class="mwidth-180">Actions</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add sentence modal -->
    <div class="modal fade" id="addModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form role="form" method="post" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="assignModalTitle">Add Sentence</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Article</label>
                                    <p><?= $article['title'] ?></p>
                                </div>
                                <input type="hidden" name="sel_article" id="sel_article" value="<?= $_GET['aid'] ?>">
                                <div class="form-group">
                                    <label>Content:</label> 
                                    <select class="form-control select2 select2-hidden-accessible" name="sel_content" id="sel_content" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value=''>- Select -</option>
                                        <?php 
                                            foreach($contents as $content) {
                                                if($content['title'] && $content['title'] != 'none' && $content['title'] != '' && strpos($content['title'], '[article-') === false) {
                                                    $title = ' - '.$content['title'];
                                                } else {
                                                    $title = '';
                                                }
                                                echo "<option value='".$content['id']."'>".ucfirst($content['type']).' '.trim($icca_new_obj->htmlallentities($title))."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group" id="paragraph_blk" style="display:none">
                                    <label>Paragraph:</label> 
                                    <select class="form-control select2 select2-hidden-accessible" name="sel_paragraph" id="sel_paragraph" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value=''>- Select -</option>
                                    </select>
                                </div>
                                <div class="form-group" id="add_sent_block" style="display:none">
                                    <label>Sentence:</label>
                                    <textarea class="form-control" rows="3" name="new_sentence_add" id="new_sentence_add" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group mb-0">
                            <button class="btn btn-success" type="submit" id="addSentence" name="addSentence">Add</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Add sentence modal -->
    
    <!-- Edit sentence modal -->
    <div class="modal fade" id="editModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form role="form" method="post" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="assignModalTitle">Edit Sentence</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="article_id" id="article_id" value="<?= $_GET['aid'] ?>">
                                <input type="hidden" name="sent_id" id="sent_id">
                                <input type="hidden" name="orig_sentence2" id="orig_sentence2">
                                <div class="form-group">
                                    <label>
                                        Sentence:&nbsp;&nbsp;
                                        <small id="n_count" class="form-text text-muted" style="display: inline;font-weight: 500;"></small>
                                    </label> 
                                    <textarea class="form-control" rows="3" name="new_sentence" id="new_sentence" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group mb-0">
                            <button class="btn btn-warning" type="submit" id="updateSentence" name="updateSentence">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="close_image_preview">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit sentence modal -->
</section>

<section class="content" style="min-height:fit-content">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="widget-content ">
                <div id="print" class="myDivToPrint">
                    <div class="box">
                        <div class="box-header">
                            <div class="row"> 
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <h3 class="box-title" style="font-weight: bold;">History</h3>
                                </div>
                            </div> 
                            
                            <hr style="margin-top: 5px;">
                        </div>
                        <div class="box-body table-responsive">
                            <table id="example2" class="table table-bordered table-striped table-hover">
                                <thead style="background:#2b415e; color:#fff;">
                                    <tr>
                                        <th class="mwidth-10">#</th>
                                        <th class="mwidth-250">Log</th>
                                        <th class="mwidth-130">Created At</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php 
                                        $count = 0;
                                        $logs = $icca_new_obj->getSentLogs($_GET['aid']);
                                        
                                        foreach ($logs as $log):
                                    ?>
                                        <tr>
                                            <td><?= ++$count; ?></td>
                                            <td>
                                                <?php
                                                    echo trim($log['action']);
                                                    if($log['from_']) {
                                                        if($log['to_']) {
                                                            echo " from: <br/><b>".$log['from_']."</b><br/>to:<br/><b>".$log['to_']."</b>";
                                                        } else {
                                                            echo ":<br/><b>".$log['from_']."</b>";
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td><?= date("F d, Y  h:iA", strtotime($log['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>   
                                </tbody>
                                
                                <tfoot>
                                    <tr>
                                        <th class="mwidth-10">#</th>
                                        <th class="mwidth-250">Log</th>
                                        <th class="mwidth-130">Created At</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function cleanString(str) {
        return str.replace(/\s+/g,' ').trim();
    }
    
    function charCount(str) {
        return str.length;
    }
    
    function wordCount(str) {
        return str.split(' ').length;
    }
    
    function updateCount(sent) {
        const n_word = wordCount(cleanString(sent));
        const n_char = charCount(cleanString(sent));
        $('#n_count').html(`(Word count: ${n_word}, Character count: ${n_char})`);
    }
    
    $('#new_sentence').on('input', function() {
        updateCount(this.value);
    });
    
    function parseHtmlEntities(str) {
        return str.replace(/&#([0-9]{1,4});/gi, function(match, numStr) {
            var num = parseInt(numStr, 10); // read num as normal number
            return String.fromCharCode(num);
        });
    }
    
    $('#sel_content').on('change', function() {
        jQuery.ajax({
            url :"./ajax/paragraph-list.php",
            type: "GET", 
            data: { 'id' : this.value },
            success: function(data) {
                $("#sel_paragraph").html(data);
                $("#paragraph_blk").show();
                $("#add_sent_block").show();
            },
            error: function(xhr, status, error) {  
                console.log(xhr)
                console.log(xhr.responseText, status, error);
                alert("Error occurred when fetching paragraph count. Please try again later.");
            }
        });
    });
    
    function showAddModal() {
        $('#addModal').modal('show');
    }
    
    function showModal(id, new_sent) {
        $('#sent_id').val(id);
        $('#orig_sentence2').val(parseHtmlEntities(new_sent));
        $('#new_sentence').val(parseHtmlEntities(new_sent));
        
        //word and character count
        const n_word = wordCount(cleanString(new_sent));
        const n_char = charCount(cleanString(new_sent));
        $('#n_count').html(`(Word count: ${n_word}, Character count: ${n_char})`);
        
        $('#editModal').modal('show');
    }
    
    function confirmDelete(id) {
        confirmed = confirm(`Are you sure you want to DELETE this sentence?\n(Note: This might take a minute)`);
        if (confirmed) {
            const user_id = '<?= $_SESSION['login_id'] ?>';
            jQuery.ajax({
                url :"./ajax/delete-sentence.php",
                type: "POST", 
                data: { 'id' : id, 'uid' : user_id },
                success: function(data) {
                    alert(`Sentence was deleted successfully!`);
                    window.location.href = window.location.href;
                },
                error: function(xhr, status, error){  
                    console.log(xhr)
                    console.log(xhr.responseText, status, error);
                    alert("Error occurred when deleting the sentence. Please try again later.");
                }
            });
        }
    }
</script>