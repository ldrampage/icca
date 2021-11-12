<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1); 
    $message = "";
    
    $isEditor = $icca_new_obj->isEditor($_SESSION['login_id']);
    $isAdmin = $icca_new_obj->isAdmin($_SESSION['login_id']);
    if(!$isEditor) {
        header('Location: http://icca.authoritativecontent.net/index.php'); 
    }
    
    $rewrite_status = (isset($_GET['status'])) ? $_GET['status'] : 'all';
    $user_id = (isset($_GET['uid'])) ? $_GET['uid'] : 'all';
    $editor_id = (isset($_GET['eid'])) ? $_GET['eid'] : 'all';
    $article_id = (isset($_GET['aid'])) ? $_GET['aid'] : 'all';
    $datepicker = (isset($_GET['date'])) ? date("m/d/Y",strtotime($_GET['date'])) : null;
    $datepicker2 = (isset($_GET['date'])) ? date("Y-m-d",strtotime($_GET['date'])) : null;
    
    //for employee and article filter dropdown
    $employeeIds = $icca_new_obj->getRAEmployeeIds(); 
    $articleIds = $icca_new_obj->getRAArticleIds();
    $editors = $icca_new_obj->getEditors();

    //updating a sentence
    if(isset($_POST['updateSentence'])) {
        $message = $icca_new_obj->updateRewrite($_POST);
        $success_message = 'Rewritten sentence updated successfully.';
        $error_message = 'Error occurred when updating the rewritten sentence. Please try again later.';
        // echo "<script>console.log(".json_encode($message).")</script>";
    }
    
    //rewriting a sentence
    if(isset($_POST['rewriteSentence'])) {
        $message = $icca_new_obj->rewriteSentence($_POST);
        $success_message = 'Sentence was rewritten successfully.';
        $error_message = 'Error occurred when rewriting the sentence. Please try again later.';
    }
?>

<section class="content" >
    <?php if(is_array($message)) { ?>
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Invalid!</h4>
                <ul>
                    <?php 
                        foreach($message as $msg) { 
                            echo "<li>".$msg.".</li>";    
                        } 
                    ?>
                </ul>
            </div>
        </div>
    <?php } else if($message != "") { ?>
        <div class="row">
            <?php if($message == true) { ?>
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        <?= $success_message ?>
                     </div>
                </div>
            <?php } else { ?>
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
                        <?= $error_message ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="widget-content ">
                <div id="print" class="myDivToPrint">
                    <div class="box">
                        <div class="box-header">
                            <div class="row"> 
                                <div class="col-md-4">
                                    <h3 class="box-title" style="font-weight: bold;">Rewritten Sentences</h3>
                                </div>
                            </div> 
                            
                            <hr style="margin-top: 5px;">
                            <div class="row"> 
                                <div class="col-md-4 col-sm-12" style="margin-bottom:10px">
                                    <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Article:</h3> 
                                    <select class="form-control select2" id='articleFilter'>
                                        <option value="">All</option>
                                        <?php foreach($articleIds as $article): $artiComp = $iccaFunc->fetchArticleByid($article); ?>
                                            <option value="<?php echo $artiComp[0]['id']; ?>"><?php echo $artiComp[0]['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-12" style="margin-bottom:10px">
                                    <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Status:</h3> 
                                    <select class="form-control select2" id='statusFilter'>
                                        <option value="" style="font-size:14px">All</option>
                                        <option value="0" <?php if(isset($_GET['status'])){ if($_GET['status'] == 0) { echo "selected"; }} ?> >Pending</option>
                                        <option value="1" <?php if(isset($_GET['status'])){ if($_GET['status'] == 1) { echo "selected"; }} ?> >Approved</option>
                                        <option value="2" <?php if(isset($_GET['status'])){ if($_GET['status'] == 2) { echo "selected"; }} ?> >Rejected</option>
                                        <option value="3" <?php if(isset($_GET['status'])){ if($_GET['status'] == 3) { echo "selected"; }} ?> >Rejected & Rewritten</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-12" style="margin-bottom:10px">
                                    <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Writer:</h3> 
                                    <select class="form-control select2" id='writerFilter'>
                                        <option value="">All</option>
                                        <?php foreach($employeeIds as $ids) { 
                                            $users = $iccaFunc->getUserById($ids); 
                                            if(intval($ids)) {
                                        ?>
                                        <option value="<?php echo $users['id']; ?>"><?php echo $users['fname']." ".$users['lname']; ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-12" style="margin-bottom:10px;">    
                                    <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Date (for writer):</h3> 
                                    <div class="form-group mb-0">
                                        <div class="input-group date">
                                          <div class="input-group-addon"><i class="fas fa-calendar-alt"></i></div>
                                          <input type="text" style="font-size:14px" class="form-control dpick" id="writerDateFilter" name="dateFilter">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12" style="margin-bottom:10px">
                                    <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Editor:</h3> 
                                    <select class="form-control select2" id='editorFilter'>
                                        <option value="">All</option>
                                        <?php foreach($editors as $ids): $users = $iccaFunc->getUserById($ids['user_id']); ?>
                                            <option value="<?php echo $users['id'] ?>"><?php echo $users['fname']." ".$users['lname']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-12 col-sm-12"></div>
                                <div class="col-md-2 col-sm-12" style="margin-bottom:10px;">    
                                    <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Date (for editor):</h3> 
                                    <div class="form-group mb-0">
                                        <div class="input-group date">
                                          <div class="input-group-addon"><i class="fas fa-calendar-alt"></i></div>
                                          <input type="text" style="font-size:14px" class="form-control dpick" id="editorDateFilter" name="dateFilter">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12" style="margin-bottom:10px">
                                    <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Category:</h3> 
                                    <select class="form-control select2" id="filterByCategory">
                                        <option value="">All</option>
                                        <?php $catLists = $iccaFunc->fetchCategoryList(); ?>
                                        <?php foreach ($catLists as $catList) : ?>
                                        <option value='<?php echo $catList['id']; ?>'><?php echo $catList['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-12" id="rewriteRejects_blk" style="margin-bottom:10px">
                                	<h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">From Rejects?</h3> 
                                	<select id="rewriteRejects" class="form-control">
                                		<option value="0">No</option>
                                		<option value="1">Yes</option>
                                	</select>
                                </div>
                            </div> 
                        </div>
                        <div class="box-body table-responsive">
                            <div class="col-lg-12 col-md-12" style="padding:0">
                                <div class="dataTables_filter">
                                    <label>
                                        Search:
                                        <input type="search" class="form-control input-sm" placeholder="" aria-controls="rewritesTable" id="rewritesTable_filter">
                                    </label>
                                </div>
                            </div>
                            <table id="rewritesTable" class="table table-bordered table-striped table-hover">
                                <thead style="background:#2b415e; color:#fff;">
                                    <tr>
                                        <th class="mwidth-180">Article</th>
                                        <th class="mwidth-250">Original Sentence</th>
                                        <th class="mwidth-250">New Sentence</th>
                                        <th class="mwidth-180">Rewritten By</th>
                                        <th class="mwidth-100">Status</th>
                                        <th class="mwidth-200">Category</th>
                                        <th class="mwidth-250">Actions</th>
                                        
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th class="mwidth-180">Article</th>
                                        <th class="mwidth-250">Original Sentence</th>
                                        <th class="mwidth-250">New Sentence</th>
                                        <th class="mwidth-180">Rewritten By</th>
                                        <th class="mwidth-100">Status</th>
                                        <th class="mwidth-200">Category</th>
                                        <th class="mwidth-250">Actions</th>
                                        
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit rewritten sentence modal -->
    <div class="modal fade" id="editModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form role="form" method="post" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="assignModalTitle">Edit Rewritten Sentence</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="rewrite_id" id="rewrite_id">
                                <input type="hidden" name="orig_sentence2" id="orig_sentence2">
                                <input type="hidden" name="old_status" id="old_status">
                                <input type="hidden" name="err_msg" id="err_msg">
                                <div class="form-group">
                                    <label>
                                        Original Sentence:&nbsp;&nbsp;
                                        <small id="o_count" class="form-text text-muted" style="display: inline;font-weight: 500;"></small>
                                    </label> 
                                    <p id="orig_sentence"></p>
                                </div>
                                <div class="form-group">
                                    <label>
                                        New Sentence:&nbsp;&nbsp;
                                        <small id="n_count" class="form-text text-muted" style="display: inline;font-weight: 500;"></small>
                                    </label> 
                                    <textarea class="form-control" rows="3" name="new_sentence" id="new_sentence" required></textarea>
                                    <div class="pull-right mtop-6">
                                        <button class="btn btn-xs btn-primary" id="wordAI">
                                            <img id="loadingSpin" src="images/loading.gif" height="20" width="26" style="display:none;" >
                                            <span id="spinText">Spin</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Status:</label> 
                                    <select id="update_status" name="update_status" class="form-control">
                                        <option value="0">Pending</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Rejected</option>
                                        <option value="3">Rejected & Rewritten</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Rules:</label> 
                                    <ul class="list-unstyled">
                                        <li><i id="cond_1" class="fa fa-check" style="color:#00a65a"></i>&nbsp;&nbsp;The new sentence should not be an exact copy of the previous sentence</li>
                                        <li><i id="cond_2" class="fa fa-check" style="color:#00a65a"></i>&nbsp;&nbsp;Word count of new sentence should at least be 70% of previous sentence</li>
                                        <li><i id="cond_3" class="fa fa-check" style="color:#00a65a"></i>&nbsp;&nbsp;Character count of new sentence should at least be 60% of previous sentence</li>
                                    </ul>
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
    <!-- Edit rewritten sentence modal -->
    
    <!-- Rewrite sentence modal -->
    <div class="modal fade" id="rewriteModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form role="form" method="post" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="rewriteModalTitle">Rewrite Sentence</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="r_rewrite_id" id="r_rewrite_id">
                                <input type="hidden" name="r_orig_sentence2" id="r_orig_sentence2">
                                <input type="hidden" name="r_err_msg" id="r_err_msg">
                                <div class="form-group">
                                    <label>
                                        Original Sentence:&nbsp;&nbsp;
                                        <small id="r_o_count" class="form-text text-muted" style="display: inline;font-weight: 500;"></small>
                                    </label> 
                                    <p id="r_orig_sentence"></p>
                                </div>
                                <div class="form-group">
                                    <label>
                                        New Sentence:&nbsp;&nbsp;
                                        <small id="r_n_count" class="form-text text-muted" style="display: inline;font-weight: 500;"></small>
                                    </label> 
                                    <textarea class="form-control" rows="3" name="r_new_sentence" id="r_new_sentence" required></textarea>
                                    <div class="pull-right mtop-6">
                                        <button class="btn btn-xs btn-primary" id="r_wordAI">
                                            <img id="r_loadingSpin" src="images/loading.gif" height="20" width="26" style="display:none;" >
                                            <span id="r_spinText">Spin</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Rules:</label> 
                                    <ul class="list-unstyled">
                                        <li><i id="r_cond_1" class="fa fa-check" style="color:#00a65a"></i>&nbsp;&nbsp;The new sentence should not be an exact copy of the previous sentence</li>
                                        <li><i id="r_cond_2" class="fa fa-times" style="color:#dd4b39"></i>&nbsp;&nbsp;Word count of new sentence should at least be 70% of previous sentence</li>
                                        <li><i id="r_cond_3" class="fa fa-times" style="color:#dd4b39"></i>&nbsp;&nbsp;Character count of new sentence should at least be 60% of previous sentence</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group mb-0">
                            <button class="btn bg-orange" type="submit" id="rewriteSentence" name="rewriteSentence">Rewrite</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="close_rewrite_modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Rewrite sentence modal -->
</section>

<script>
    $(document).ready(function() {
        $("#rewriteRejects_blk").hide();
        
        $("input[name=dateFilter]").datepicker({
            format: "yyyy-mm-dd",
            endDate: new Date(),
            autoclose: true,
        })
        
        const rewritesTable = $("#rewritesTable").DataTable({
            "processing" : true,
            "serverSide" : true,
            "ajax" : "rewritesTableData2.php",
            "order":[[ 9, "desc" ]],
            "columnDefs": [
                {
                    "targets": [7,8,9,10],
                    "visible": false
                }],
            "sDom": 'lrtip',
        
        });
        
        $("#rewritesTable_filter").on("keyup", function() {
            rewritesTable.search(this.value).draw() ;
        });
        
        <?php if(isset($_GET['status'])): ?>
            <?php if($_GET['status'] == 0){ ?>
                $("#rewriteRejects_blk").show();
                rewritesTable.column(4).search(0).draw();
            <?php } elseif($_GET['status'] == 1){ ?>
                rewritesTable.column(4).search(1).draw();
            <?php } elseif($_GET['status'] == 2){ ?>
                rewritesTable.column(4).search(2).draw();
            <?php } else { ?>
                rewritesTable.column(4).search(3).draw();
            <?php } ?>
        <?php endif; ?>
        
        // rewritesTable.on( 'order.dt search.dt', function () {
        //     rewritesTable.column(0, { search: 'applied', order: 'applied' }).nodes().each( function(cell, i) {
        //         cell.innerHTML = i+1;
        //     })
        // }).draw();
        
        
        $("#rewritesTable").on("init.dt", function() {
            <?php  if(!$isAdmin): ?>
                $(".deleteAction").hide();
            <?php endif; ?>
        });
        
        $("#articleFilter").on("change", function() {
            rewritesTable.column(0).search(this.value).draw();
        });
        
        $("#statusFilter").on("change", function() {
            let isRejectRewrite = '';
            if(parseInt(this.value) == 0) { //status is pending, immediately filter pending rewrites when is_reject_rewrite = 0
                $("#rewriteRejects").val(0);
                $("#rewriteRejects_blk").show();
                isRejectRewrite = 0;
            } else {
                $("#rewriteRejects_blk").hide();
            }
            rewritesTable.column(4).search(this.value).column(7).search(isRejectRewrite).draw();   
        });
        
        $("#writerFilter").on("change", function(){
            rewritesTable.column(3).search(this.value).draw();
        });
        
        $("#writerDateFilter").on("changeDate", function() {
            rewritesTable.column(9).search(this.value).draw();
        });
        
        $("#editorFilter").on("change", function() {
            rewritesTable.column(7).search(this.value).draw();
        });
        
        $("#editorDateFilter").on("changeDate", function() {
            rewritesTable.column(10).search(this.value).draw();
        });
        
        $("#rewriteRejects").on("change", function() {
            rewritesTable.column(8).search(this.value).draw();
        });
        $("#filterByCategory").on("change", function() {
            // rewritesTable.column(5).search(this.value).draw();
           
                var key = this.value;
                $.ajax({
                        url: 'categ_.php',
                        type: 'post',
                        data: { "key": key},
                        success: function(response) { 
                            // alert(response);  
                            
                            $('tbody').remove();
                            $('#rewritesTable').append(response);
                            
                        }
                    });
        });
    })
</script>

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
    
    function updateCount(sent, count_class) {
        const n_word = wordCount(cleanString(sent));
        const n_char = charCount(cleanString(sent));
        $('#'+count_class).html(`(Word count: ${n_word}, Character count: ${n_char})`);
    }
    
    function compareSentences(sent1, sent2, cond1, cond2, cond3, err_msg_id) {
        const old_sent = cleanString(sent1.toLowerCase());
        const new_sent = cleanString(sent2.toLowerCase());
        let err_msg = '';
        
        //if two strings are equal
        if(old_sent == new_sent) {
            err_msg += 'The new sentence should not be an exact copy of the previous sentence.';
            $('#'+cond1).attr('class','fa fa-times');
            $('#'+cond1).css('color','#dd4b39');
        } else {
            err_msg = err_msg.replace('The new sentence should not be an exact copy of the previous sentence.', '');
            $('#'+cond1).attr('class','fa fa-check');
            $('#'+cond1).css('color','#00a65a');
        }
        
        //if word count of new sentence is not at least be 70% of previous sentence
        if(wordCount(cleanString(new_sent)) < 0.7 * wordCount(cleanString(old_sent))) {
            err_msg += 'Word count of new sentence should at least be 70% of previous sentence.';
            $('#'+cond2).attr('class','fa fa-times');
            $('#'+cond2).css('color','#dd4b39');
        } else {
            err_msg = err_msg.replace('Word count of new sentence should at least be 70% of previous sentence.', '');
            $('#'+cond2).attr('class','fa fa-check');
            $('#'+cond2).css('color','#00a65a');
        }
        
        //if character count of new sentence is not at least 60% of previous sentence
        if(charCount(cleanString(new_sent)) < 0.6 * charCount(cleanString(old_sent))) {
            err_msg += 'Character count of new sentence should at least be 60% of previous sentence.';
            $('#'+cond3).attr('class','fa fa-times');
            $('#'+cond3).css('color','#dd4b39');
        } else {
            err_msg = err_msg.replace('Character count of new sentence should at least be 60% of previous sentence.', '');
            $('#'+cond3).attr('class','fa fa-check');
            $('#'+cond3).css('color','#00a65a');
        }
        
        $('#'+err_msg_id).val(err_msg);
    }
    
    function parseHtmlEntities(str) {
        return str.replace(/&#([0-9]{1,4});/gi, function(match, numStr) {
            var num = parseInt(numStr, 10); // read num as normal number
            return String.fromCharCode(num);
        });
    }
    
    let intervalId = null;
    function showModal(id, status, orig_sent, new_sent) {
        $('#rewrite_id').val(id);
        $('#update_status').val(status);
        $('#old_status').val(status);
        $('#orig_sentence').html(orig_sent);
        $('#orig_sentence2').val(orig_sent);
        $('#new_sentence').val(new_sent);
        
        //word and character count
        const o_word = wordCount(cleanString(orig_sent));
        const o_char = charCount(cleanString(orig_sent));
        $('#o_count').html(`(Word count: ${o_word}, Character count: ${o_char})`);
        
        const n_word = wordCount(cleanString(new_sent));
        const n_char = charCount(cleanString(new_sent));
        $('#n_count').html(`(Word count: ${n_word}, Character count: ${n_char})`);
        
        $('#edit_'+ id).prop('disabled', true);
                    
        jQuery.ajax({
            url :"./ajax/is-rewrite-open.php",
            type: "POST", 
            data: { 'id' : id },
            success: function(data) {
                const data1 = JSON.parse(data);
                if(parseInt(data1['is_open'])) {
                    $('#editModal').modal('show');
                    updateEditTime(id);
                    intervalId = setInterval(function() { updateEditTime(id) }, 10000); //updates time every 10s
                } else {
                    const diffInMilliseconds = Math.abs(new Date() - new Date(data1['last_edit_time']));
                    if(diffInMilliseconds >= 60000) { //at least 1 minute has passed and no changes have been made
                        $('#editModal').modal('show');
                        updateEditTime(id);
                        intervalId = setInterval(function() { updateEditTime(id) }, 10000); //updates time every 10s
                    } else {
                        alert('Someone is currently editing this rewrite!');
                    }
                }
                $('#edit_'+ id).prop('disabled', false);
            },
            error: function(xhr, status, error) {  
                console.log(xhr)
                console.log(xhr.responseText, status, error);
                alert("Error occurred when setting rewrite to open. Please try again later.");
                $('#edit_'+ id).prop('disabled', false);
            }
        });
    }
    
    function showRewriteModal(id, orig_sent) {
        $('#r_rewrite_id').val(id);
        $('#r_orig_sentence').html(orig_sent);
        $('#r_orig_sentence2').val(orig_sent);
        
        //word and character count
        const o_word = wordCount(cleanString(orig_sent));
        const o_char = charCount(cleanString(orig_sent));
        $('#r_o_count').html(`(Word count: ${o_word}, Character count: ${o_char})`);
        $('#r_n_count').html(`(Word count: 0, Character count: 0)`);
        
        $('#rewriteModal').modal('show');
    }
    
    function updateEditTime(id) {
        jQuery.ajax({
            url :"./ajax/rewrite-open.php",
            type: "POST", 
            data: { 'id' : id, 'open' : 0 }
        });
    }
    
    $("#editModal").on("hide.bs.modal", function () {
        clearInterval(intervalId);
        const id = $('#rewrite_id').val();
        jQuery.ajax({
            url :"./ajax/rewrite-open.php",
            type: "POST", 
            data: { 'id' : id, 'open' : 1 },
            success: function(data) {
                console.log(data);
            },
            error: function(xhr, status, error){  
                console.log(xhr)
                console.log(xhr.responseText, status, error);
                alert("Error occurred when setting rewrite to open. Please try again later.");
            }
        });
    });
    
    $('#new_sentence').on('input', function() {
        updateCount(this.value, 'n_count');
        
        const old_sent = $('#orig_sentence2').val();
        compareSentences(old_sent, this.value, 'cond_1', 'cond_2', 'cond_3','err_msg');
    });
    
    $('#r_new_sentence').on('input', function() {
        updateCount(this.value, 'r_n_count');
        
        const old_sent = $('#r_orig_sentence2').val();
        compareSentences(old_sent, this.value, 'r_cond_1', 'r_cond_2', 'r_cond_3','r_err_msg');
    });
    
    $("#wordAI").on('click', function() {
        wordAI('new_sentence', 'loadingSpin', 'spinText', 'wordAI');
    });
    
    $("#r_wordAI").on('click', function() {
        wordAI('r_new_sentence', 'r_loadingSpin', 'r_spinText', 'r_wordAI');
    });
    
    function wordAI(sent_class, load_spin, spin_text, word_ai) {
        let sentence = $("textarea[name=" + sent_class + "]").val();
        sentence = cleanString(sentence);

        if(sentence != ""){
            var data = { wordAI: "ready", sentence: sentence, user_id: <?= $_SESSION['login_id']; ?> }
            $.ajax({
                type: "POST",
                url: "getThis.php",
                data: data,
                beforeSend: function(){
                    document.getElementById(load_spin).style.display="block";
                    document.getElementById(spin_text).innerText = "";
                    document.getElementById(word_ai).disabled = true;
                    
                },
                complete: function(){
                    document.getElementById(spin_text).innerText = "Spin";
                    document.getElementById(load_spin).style.display="none";
                    document.getElementById(word_ai).disabled = false;
                },
                success: function(response){
                    const obj = JSON.parse(response);
                    console.log(obj);
                    $("textarea[name=" + sent_class + "]").val(decodeURI(obj.text));
                },
                error: function(xhr, status, error){  
                    console.log(xhr)
                    console.log(xhr.responseText, status, error);
                    alert("Error occurred when spinning the rewrite. Please try again later.");
                }
                
            })  
        }
    }
    
    function confirmDelete(id) {
        confirmed = confirm(`Are you sure you want to DELETE this rewrite?`);
        if (confirmed) {
            jQuery.ajax({
                url :"./ajax/delete-rewrite.php",
                type: "POST", 
                data: { 'id' : id },
                success: function(data) {
                    alert(`Rewritten sentence was deleted successfully!`);
                    window.location.href = window.location.href;
                },
                error: function(xhr, status, error){  
                    console.log(xhr)
                    console.log(xhr.responseText, status, error);
                    alert("Error occurred when deleting the rewrite. Please try again later.");
                }
            });
        }
    }
    
    function approveRewrite(id, name, article, old_status) {
        const reviewer = <?php echo $_SESSION['login_id']; ?>;
        confirmed = confirm(`You are about to APPROVE the rewritten sentence for article "${article}" by ${name}. Click OK to proceed`);
        if (confirmed) {
            jQuery.ajax({
                url :"./ajax/approve-rewrite.php",
                type: "POST", 
                data: { 'id' : id, 'reviewer' : reviewer, 'old_status' : old_status },
                success: function(data) {
                    if(data == "true") {
                        alert(`Rewritten sentence for article "${article}" was approved successfully!`);
                        window.location.href = window.location.href;
                    } else {
                        alert('Someone is currently editing this rewrite!');
                    }
                },
                error: function(xhr, status, error){  
                    console.log(xhr)
                    console.log(xhr.responseText, status, error);
                    alert("Error occurred when approving the rewrite. Please try again later.");
                }
            });
        }
    }
    
    function rejectRewrite(id, name, article, old_status) {
        const reviewer = <?php echo $_SESSION['login_id']; ?>;
        confirmed = confirm(`You are about to REJECT the rewritten sentence for article "${article}" by ${name}. Click OK to proceed`);
        if (confirmed) {
            jQuery.ajax({
                url :"./ajax/reject-rewrite.php",
                type: "POST", 
                data: { 'id' : id, 'reviewer' : reviewer, 'old_status' : old_status },
                success: function(data) {
                    if(data == "true") {
                        alert(`Rewritten sentence for article "${article}" was rejected successfully!`);
                        window.location.href = window.location.href;
                    } else {
                        alert('Someone is currently editing this rewrite!');
                    }
                },
                error: function(xhr, status, error){  
                    console.log(xhr)
                    console.log(xhr.responseText, status, error);
                    alert("Error occurred when rejecting the rewrite. Please try again later.");
                }
            });
        }
    }
</script>

