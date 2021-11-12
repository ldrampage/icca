<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1); 
    $message = "";
    $action = "";
    
    $isEditor = $icca_new_obj->isEditor($_SESSION['login_id']);
    $isAdmin = $icca_new_obj->isAdmin($_SESSION['login_id']);
    if(!$isEditor) {
        header('Location: http://icca.authoritativecontent.net/index.php'); 
    }
    
    $rewrite = $icca_new_obj->getRewrittenById($_GET['id']);
    if(empty($rewrite)) {
        echo "<script>window.location = 'index.php?page=rewritten-sentences'; </script>";
    }
    
    //adding a note
    if(isset($_POST['addNote'])) {
        $message = $icca_new_obj->addNote($_POST);
        $action = $message ? "added" : "adding";
    }
    
    //edit a note
    if(isset($_POST['editNote'])) {
        $message = $icca_new_obj->editNote($_POST);
        $action = $message ? "updated" : "updating";
    }
?>

<section class="content" style="min-height:fit-content">
    <?php if($message != ""): ?>
        <div class="row">
            <?php if($message) { ?>
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        Note was <?= $action ?> successfully.
                     </div>
                </div>
            <?php } else { ?>
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
                        Error occurred when <?= $action ?> your note. Please try again later.
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Rewritten Sentence Details</h4>
                    <div class="pull-right" style="margin-top: -25px;">
                        <a href="?page=rewritten-sentences"><label class="btn btn-xs btn-primary">Go Back</label></a> 
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="state" class="control-label">Article:</label>
                                <p>
                                    <?php 
                                        $article = $icca_new_obj->getArticleById($rewrite['article_id']);
                                        echo trim($article['title']);
                                    ?>
                                </p>
                            </div>
                            <div class="form-group">
                                <label for="state" class="control-label">Type and Title:</label>
                                <p>
                                    <?php 
                                        $sentence = $icca_new_obj->getSentenceById($rewrite['sentence_id']);
                                        $content = $icca_new_obj->getContentById($sentence['content_id']);
                                        
                                        if($content['title'] && $content['title'] != 'none' && $content['title'] != '' && strpos($content['title'], '[article-') === false) {
                                            $content_title = $content['title'];
                                        } else {
                                            $content_title = 'none provided';
                                        }
                                        // $content_title = $content['title'] ? $content['title'] : 'none provided';
                                        
                                        echo "<b>".ucfirst($content['type'])."</b> - ".trim($icca_new_obj->htmlallentities($content_title));
                                    ?>
                                </p>
                            </div>
                            <div class="form-group">
                                <label for="state" class="control-label">Paragraph and Order:</label>
                                <p>
                                    <?php 
                                        echo $icca_new_obj->addOrdinalNumberSuffix($rewrite['paragraph_no'])." paragraph, ";
                                        echo $icca_new_obj->addOrdinalNumberSuffix($rewrite['order_no'])." sentence";
                                    ?>
                                </p>
                            </div>
                            <div class="form-group">
                                <label for="state" class="control-label">
                                    Original Sentence:&nbsp;&nbsp;
                                    <small id="o_count" class="form-text text-muted" style="display: inline;font-weight: 500;">
                                        <?php
                                            $o_sentence = $icca_new_obj->convert_smart_quotes($sentence['sentence']);
                                            $o_w_count = str_word_count(utf8_decode(trim(preg_replace('!\s+!', ' ', $o_sentence))));
                                            $o_c_count = strlen(utf8_decode(trim(preg_replace('!\s+!', ' ', $o_sentence))));
                                            echo "(Word count: ".$o_w_count.", Character count: ".$o_c_count.")";
                                        ?>
                                    </small>
                                </label>
                                <p><?= trim($icca_new_obj->htmlallentities($sentence['sentence'])) ?></p>
                            </div>
                            <div class="form-group">
                                <label for="state" class="control-label">
                                    Rewritten Sentence:&nbsp;&nbsp;
                                    <small id="n_count" class="form-text text-muted" style="display: inline;font-weight: 500;">
                                        <?php
                                            $n_sentence = $icca_new_obj->convert_smart_quotes($rewrite['sentence']);
                                            $n_w_count = str_word_count(utf8_decode(trim(preg_replace('!\s+!', ' ', $n_sentence))));
                                            $n_c_count = strlen(utf8_decode(trim(preg_replace('!\s+!', ' ', $n_sentence))));
                                            echo "(Word count: ".$n_w_count.", Character count: ".$n_c_count.")";
                                        ?>
                                    </small>
                                </label>
                                <p><?= trim($icca_new_obj->htmlallentities($rewrite['sentence'])) ?></p>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="state" class="control-label">Rewritten By:</label>
                                <p>
                                    <?php 
                                        $user = $icca_new_obj->getUserById($rewrite['user_id']);
                                        if(!empty($user)){
                                            echo $user['fname'].' '.$user['lname'];
                                        } else{
                                            echo "Mturk Worker ID: ".$rewrite['user_id'];
                                        }
                                        
                                    ?>
                                </p>
                            </div>
                            
                            <div class="form-group">
                                <label for="state" class="control-label">Created At:</label>
                                <p><?= date("F d, Y  h:iA", strtotime($rewrite['created_at'])); ?></p>
                            </div>
                            
                            <div class="form-group">
                                <label for="state" class="control-label">Updated At:</label>
                                <p><?= date("F d, Y  h:iA", strtotime($rewrite['updated_at'])); ?></p>
                            </div>
                            
                            <div class="form-group">
                                <label for="state" class="control-label">Status:</label>
                                <?php 
                                    if($rewrite['status'] == 0) { //pending
                                        echo '<b><p class="text-light-blue">PENDING</p></b>';
                                    } else if($rewrite['status'] == 1) { //approved
                                        echo '<b><p class="text-green">APPROVED</p></b>';
                                    } else if($rewrite['status'] == 2) { //rejected
                                        echo '<b><p class="text-red">REJECTED</p></b>';
                                    }  else {
                                        echo '<b><p class="text-orange">REJECTED & REWRITTEN</p></b>';
                                    }
                                ?>
                            </div>
                            
                            <?php if($rewrite['reviewed_by']) { ?>
                                <div class="form-group">
                                    <label for="state" class="control-label">Reviewed By:</label>
                                    <p>
                                        <?php 
                                            $user = $icca_new_obj->getUserById($rewrite['reviewed_by']);
                                            if($user) echo $user['fname'].' '.$user['lname'];
                                            else echo "None";
                                        ?>
                                    </p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content" style="min-height:fit-content">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="widget-content ">
                <div id="print" class="myDivToPrint">
                    <div class="box mb-0">
                        <div class="box-header">
                            <div class="row"> 
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <h3 class="box-title" style="font-weight: bold;">Notes</h3>
                                    <div class="pull-right" style="margin-bottom:5px;">
                                        <a href="javascript:showAddModal(<?= $_GET['id'] ?>)">
                                            <label class="btn btn-xs btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add note</label>
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
                                        <th class="mwidth-250">Note</th>
                                        <th class="mwidth-180">Created By</th>
                                        <th class="mwidth-130">Created At</th>
                                        <th class="mwidth-130">Updated At</th>
                                        <th class="mwidth-250">Actions</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php 
                                        $count = 0;
                                        $notes = $icca_new_obj->getNotes($_GET['id']);
                                        
                                        foreach ($notes as $note):
                                    ?>
                                        <tr>
                                            <td><?= ++$count; ?></td>
                                            <td><?= nl2br($note['note']) ?></td>
                                            <td>
                                                <?php 
                                                    $user = $icca_new_obj->getUserById($note['noted_by']);
                                                    $fullName = $user['fname'].' '.$user['lname'];
                                                    echo $fullName;
                                                ?>
                                            </td>
                                            <td><?php $cDate = date("F d, Y  h:iA", strtotime($note['created_at'])); echo $cDate; ?></td>
                                            <td><?php $uDate = date("F d, Y  h:iA", strtotime($note['updated_at'])); echo $uDate; ?></td>
                                            <td class="center">
                                                <a href="javascript:showViewModal('<?= $fullName ?>', <?= htmlspecialchars(json_encode(nl2br($icca_new_obj->convert_smart_quotes($note['note'])))) ?>, '<?= $cDate ?>', '<?= $uDate ?>')">
                                                    <button  class="btn btn-primary btn-xs ">
                                                        <i class="fa fa-eye"></i>&nbsp;&nbsp;View
                                                    </button>
                                                </a>
                                                <?php if($_SESSION['login_id'] == $note['noted_by']) { ?>
                                                    <a href="javascript:showEditModal(<?= $note['id'] ?>, <?= htmlspecialchars(json_encode(nl2br($icca_new_obj->convert_smart_quotes($note['note'])))) ?>, <?= $note['rewrite_id'] ?>)">
                                                        <button  class="btn btn-warning btn-xs ">
                                                            <i class="fa fa-pencil-alt"></i>&nbsp;&nbsp;Edit
                                                        </button>
                                                    </a>
                                                <?php } ?>
                                                <?php if($isAdmin) { ?>
                                                    <a href="javascript:confirmDelete(<?= $note['id'] ?>, <?= htmlspecialchars(json_encode(nl2br($icca_new_obj->convert_smart_quotes($note['note'])))) ?>, <?= $_SESSION['login_id'] ?>, <?= $note['rewrite_id'] ?>)">
                                                        <button class="btn btn-danger btn-xs ">
                                                            <i class="fa fa-times"></i>&nbsp;&nbsp;Delete
                                                        </button>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                
                                <tfoot>
                                    <tr>
                                        <th class="mwidth-10">#</th>
                                        <th class="mwidth-250">Note</th>
                                        <th class="mwidth-180">Created By</th>
                                        <th class="mwidth-130">Created At</th>
                                        <th class="mwidth-130">Updated At</th>
                                        <th class="mwidth-250">Actions</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View note modal -->
    <div class="modal fade" id="viewNoteModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="viewNoteModalTitle">Note Details</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-5 col-md-12">
                            <div class="form-group">
                                <label>Created By:</label> 
                                <p id="v_created_by"></p>
                            </div>
                            
                            <div class="form-group mb-0">
                                <label>Created At:</label>&nbsp;&nbsp;
                                <span id="v_created">hehe</span>
                            </div>
                            
                            <div class="form-group">
                                <label>Updated At:</label>&nbsp;&nbsp;
                                <span id="v_updated">hehe</span>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-12">
                            <div class="form-group">
                                <label>Note:</label> 
                                <p id="v_note"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group mb-0">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- View note modal -->
    
    <!-- Add note modal -->
    <div class="modal fade" id="addNoteModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form role="form" method="post" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="addNoteModalTitle">Add Note</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="rewrite_id" id="rewrite_id">
                                <input type="hidden" name="user_id" id="user_id" value="<?= $_SESSION['login_id'] ?>">
                                <div class="form-group">
                                    <label>Note:</label> 
                                    <textarea class="form-control" rows="3" name="new_note" id="new_note" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group mb-0">
                            <button class="btn btn-success" type="submit" id="addNote" name="addNote">Add</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Add note modal -->
    
    <!-- Update note modal -->
    <div class="modal fade" id="editNoteModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form role="form" method="post" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="editNoteModalTitle">Update Note</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="u_rewrite_id" id="u_rewrite_id">
                                <input type="hidden" name="note_id" id="note_id">
                                <input type="hidden" name="u_old_note" id="u_old_note">
                                <input type="hidden" name="u_user_id" id="u_user_id" value="<?= $_SESSION['login_id'] ?>">
                                <div class="form-group">
                                    <label>Note:</label> 
                                    <textarea class="form-control" rows="3" name="u_new_note" id="u_new_note" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group mb-0">
                            <button class="btn btn-warning" type="submit" id="editNote" name="editNote">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Update note modal -->
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
                                        $logs = $icca_new_obj->getLogs($_GET['id']);
                                        
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
    function showViewModal(name, note, cdate, udate) {
        $('#v_created_by').html(name);
        $('#v_note').html(note);
        $('#v_created').html(cdate);
        $('#v_updated').html(udate);
        $('#viewNoteModal').modal('show');
    }

    function showAddModal(id) {
        $('#rewrite_id').val(id);
        $('#addNoteModal').modal('show');
    }
    
    function showEditModal(id, note, rewrite_id) {
        $('#note_id').val(id);
        $('#u_old_note').val(note.replace(/<br\s*[\/]?>/gi, ""));
        $('#u_new_note').val(note.replace(/<br\s*[\/]?>/gi, ""));
        $('#u_rewrite_id').val(rewrite_id);
        $('#editNoteModal').modal('show');
    }
    
    function confirmDelete(id, note, uid, rid) {
        confirmed = confirm(`Are you sure you want to delete this note?`);
        if (confirmed) {
            console.log('heeey')
            jQuery.ajax({
                url :"./ajax/delete-note.php",
                type: "POST", 
                data: { 'id' : id, 'note' : note, 'uid': uid, 'rid' : rid },
                success: function(data) {
                    alert(`Note was deleted successfully!`);
                    window.location.href = window.location.href;
                },
                error: function(xhr, status, error){  
                    console.log(xhr)
                    console.log(xhr.responseText, status, error);
                    alert("Error occurred when deleting the note. Please try again later.");
                }
            });
        }
    }

</script>