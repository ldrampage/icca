<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1); 
    $message = "";
    
    $rewrite_status = (isset($_GET['status'])) ? $_GET['status'] : 'all';
    $user_id = (isset($_GET['uid'])) ? $_GET['uid'] : 'all';
    $article_id = (isset($_GET['aid'])) ? $_GET['aid'] : 'all';
    
    $editorIds = array();
    $editors =  $icca_obj->getEditors();
    foreach($editors as $editor) {
        $editorIds[] = $editor['id'];
    }
    $editorIds[] = '44'; //for testing, to be removed
    
    //for employee and article filter dropdown
    $flag = !in_array($_SESSION['login_id'], $editorIds) ? 1 : 0;
    $employeeIds = $icca_obj->getRAEmployeeIds($flag); 
    $articleIds = $icca_obj->getRAArticleIds($flag);
?>

<section class="content" >
    <div class="row">
        <div class="col-md-12">
            <div class="widget-content ">
                <div id="print" class="myDivToPrint">
                    <div class="box">
                        <div class="box-header">
                            <div class="row"> 
                                <div class="col-md-4">
                                    <h3 class="box-title" style="font-weight: bold;">Rewritten Articles</h3>
                                </div>
                            </div> 
                            
                            <hr style="margin-top: 5px;">
                            <div class="row"> 
                                <?php if(in_array($_SESSION['login_id'], $editorIds)) { ?>
                                    <div class="col-md-3">
                                        <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Article Status:</h3> 
                                        <select id="rewriteStatus" class="form-control">
                                            <?php 
                                                $type = null;
                                                if(!isset($_GET['status'])) {
                                                    $type = 'all';
                                                } else if(isset($_GET['status'])) {
                                                    $type = $_GET['status'];
                                                }
                                            ?>
                                            <option value="all" <?= ($type == 'all') ? 'selected' : ''; ?>>All</option>
                                            <option value="0" <?= ($type == '0') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="1" <?= ($type == '1') ? 'selected' : ''; ?>>Approved</option>
                                            <option value="2" <?= ($type == '2') ? 'selected' : ''; ?>>Rejected</option>
                                        </select>
                                    </div>
                                <?php } ?>
                                
                                <div class="col-md-3">
                                    <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Employee:</h3> 
                                    <select class="form-control select2 select2-hidden-accessible" name="rewriteEmployee" id="rewriteEmployee" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <?php 
                                            $sel_user = null;
                                            if(!isset($_GET['uid'])) {
                                                $sel_user = 'all';
                                            } else if(isset($_GET['uid'])) {
                                                $sel_user = $_GET['uid'];
                                            }
                                        ?>
                                        <option value="all" <?= ($sel_user == 'all') ? 'selected' : ''; ?>>All</option>
                                        <?php 
                                            foreach($employeeIds as $id) {
                                                $curr_user = $icca_obj->getUserById($id);
                                                $is_selected = ($sel_user == $id) ? 'selected' : '';
                                                
                                                echo "<option value='".$id."' ".$is_selected.">".$curr_user['fname'].' '.$curr_user['lname']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Article:</h3> 
                                    <select class="form-control select2 select2-hidden-accessible" name="rewriteArticle" id="rewriteArticle" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <?php 
                                            $sel_art = null;
                                            if(!isset($_GET['aid'])) {
                                                $sel_art = 'all';
                                            } else if(isset($_GET['aid'])) {
                                                $sel_art = $_GET['aid'];
                                            }
                                        ?>
                                        <option value="all" <?= ($sel_art == 'all') ? 'selected' : ''; ?>>All</option>
                                        <?php 
                                            foreach($articleIds as $id) {
                                                $curr_art = $icca_obj->getArticleById($id);
                                                $is_selected = ($sel_art == $id) ? 'selected' : '';
                                                
                                                echo "<option value='".$id."' ".$is_selected.">".$curr_art['title']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="box-body table-responsive">
                            <table id="example1" class="table table-bordered table-striped table-hover">
                                <thead style="background:#2b415e; color:#fff;">
                                    <tr>
                                        <th class="mwidth-10">#</th>
                                        <th class="mwidth-250">Original Article</th>
                                        <th class="mwidth-180">Rewritten By</th>
                                        <th class="mwidth-130">Created At</th>
                                        <th class="mwidth-130">Last Edited At</th>
                                        <th class="mwidth-100">Status</th>
                                        <th class="mwidth-180">Reviewed By</th>
                                        <th class="mwidth-250">Actions</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php 
                                        $count = 0;
                                        $rewrites = null;
                                        $andFlag = false;
                                        $sql_stmt = '';
                                        
                                        //filtering
                                        if($rewrite_status != 'all') {
                                            $sql_stmt = 'WHERE status = '.$rewrite_status;
                                            $andFlag = true;
                                        }
                                        
                                        if($user_id != 'all') {
                                            if($andFlag) {
                                                $sql_stmt = $sql_stmt.' AND user_id = '.$user_id;
                                            } else {
                                                $sql_stmt = 'WHERE user_id = '.$user_id;
                                                $andFlag = true;
                                            }
                                        }
                                        
                                        if($article_id != 'all') {
                                            if($andFlag) {
                                                $sql_stmt = $sql_stmt.' AND article_id = '.$article_id;
                                            } else {
                                                $sql_stmt = 'WHERE article_id = '.$article_id;
                                                $andFlag = true;
                                            }
                                        }
                                        
                                        if(!in_array($_SESSION['login_id'], $editorIds)) { //if user is not editor, he can only see approved rewrites + his own
                                            if($andFlag) {
                                                if(strpos($sql_stmt, 'user_id = '.$_SESSION['login_id']) !== false) {
                                                    $sql_stmt = $sql_stmt.' OR status = 1';
                                                } else {
                                                    $sql_stmt = $sql_stmt.' AND (status = 1 OR user_id = '.$_SESSION['login_id'].')';
                                                }
                                            } else {
                                                $sql_stmt = 'WHERE user_id = '.$_SESSION['login_id'].' OR status = 1';
                                                $andFlag = true;
                                            }
                                        }
                                        //filtering
                                        
                                        $rewrites = $icca_obj->getRewrittenArticles($sql_stmt);
                                        //print_r($rewrites);
                                        foreach ($rewrites as $rewrite):
                                            $count++;
                                    ?>
                                        <tr>
                                            <td><?= $count; ?></td>
                                            <td>
                                                <?php 
                                                    $article = $icca_obj->getArticleById($rewrite['article_id']);
                                                    echo $article['title'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $user = $icca_obj->getUserById($rewrite['user_id']);
                                                    $fullName = $user['fname'].' '.$user['lname'];
                                                    echo $fullName;
                                                ?>
                                            </td>
                                            <td><?= date("F d, Y  h:iA", strtotime($rewrite['created_at'])); ?></td>
                                            <td><?= date("F d, Y  h:iA", strtotime($rewrite['last_edited_at'])); ?></td>
                                            <td>
                                                <?php 
                                                    if($rewrite['status'] == 0) { //pending
                                                        echo '<b><p class="text-light-blue">PENDING</p></b>';
                                                    } else if($rewrite['status'] == 1) { //approved
                                                        echo '<b><p class="text-green">APPROVED</p></b>';
                                                    } else { //rejected
                                                        echo '<b><p class="text-red">REJECTED</p></b>';
                                                    }  
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if($rewrite['reviewed_by']) {
                                                        $user = $icca_obj->getUserById($rewrite['reviewed_by']);
                                                        if($user) echo $user['fname'].' '.$user['lname'];
                                                        else echo "None";
                                                    } else {
                                                        echo "None";
                                                    }
                                                ?>
                                            </td>
                                            <td class="center">
                                                <a href="?page=rewritten-article&id=<?= $rewrite['id'] ?>">
                                                    <button  class="btn btn-primary btn-xs mtop-6">
                                                        <i class="fa fa-eye"></i>&nbsp;&nbsp;View
                                                    </button>
                                                </a>
                                                <?php if($_SESSION['login_id'] == $rewrite['user_id']) { ?>
                                                    <a href="#">
                                                        <button  class="btn btn-warning btn-xs mtop-6">
                                                            <i class="fa fa-pencil-alt"></i>&nbsp;&nbsp;Edit
                                                        </button>
                                                    </a>
                                                    <!--<a href="#">-->
                                                    <!--    <button  class="btn btn-danger btn-xs mtop-6">-->
                                                    <!--        <i class="fa fa-times"></i>&nbsp;&nbsp;Delete-->
                                                    <!--    </button>-->
                                                    <!--</a>-->
                                                <?php } ?>
                                                <?php if(in_array($_SESSION['login_id'], $editorIds) && $rewrite['status'] == 0) { ?>
                                                    <a href="javascript:approveRewrite('<?= $rewrite['id'] ?>', '<?= preg_replace('/\s+/', ' ', $fullName) ?>', '<?= addslashes($article['title']) ?>', '<?= $_SESSION['login_id'] ?>')">
                                                        <button class="btn btn-success btn-xs mtop-6">
                                                            <i class="fa fa-check"></i>&nbsp;&nbsp;Approve
                                                        </button>
                                                    </a>
                                                    <a href="javascript:rejectRewrite('<?= $rewrite['id'] ?>', '<?= preg_replace('/\s+/', ' ', $fullName) ?>', '<?= addslashes($article['title']) ?>', '<?= $_SESSION['login_id'] ?>')">
                                                        <button class="btn btn-danger btn-xs mtop-6">
                                                            <i class="fa fa-times"></i>&nbsp;&nbsp;Reject
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
                                        <th class="mwidth-250">Original Article</th>
                                        <th class="mwidth-180">Rewritten By</th>
                                        <th class="mwidth-130">Created At</th>
                                        <th class="mwidth-130">Last Edited At</th>
                                        <th class="mwidth-100">Status</th>
                                        <th class="mwidth-180">Reviewed By</th>
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
</section>

<script>
    const status = document.querySelector('#rewriteStatus');
    const user = document.querySelector('#rewriteEmployee');
    const article = document.querySelector('#rewriteArticle');
    
    function updateURL(nstatus, nuser, narticle) {
        let new_url = 'http://icca.authoritativecontent.net/index.php?page=rewritten-articles';
        
        if(nstatus && nstatus != 'all') {
            new_url = new_url + '&status=' + nstatus;
        }
        if(nuser && nuser != 'all') {
            new_url = new_url + '&uid=' + nuser;
        }
        if(narticle && narticle != 'all') {
            new_url = new_url + '&aid=' + narticle;
        }
        return new_url;
    }

    $('#rewriteStatus').on('change', function() {
        location.href = updateURL(this.value, user.value, article.value);
    });
    
    $('#rewriteEmployee').on('change', function() {
        if(status) {
            location.href = updateURL(status.value, this.value, article.value);   
        } else {
            location.href = updateURL(null, this.value, article.value);
        }
    });
    
    $('#rewriteArticle').on('change', function() {
        if(status) {
            location.href = updateURL(status.value, user.value, this.value);
        } else {
            location.href = updateURL(null, user.value, this.value);
        }
    });
    
    function approveRewrite(id, name, article, reviewer) {
        confirmed = confirm(`You are about to APPROVE the rewritten version of "${article}" by ${name}. Click OK to proceed`);
        if (confirmed) {
            jQuery.ajax({
                url :"./ajax/approve-rewrite.php",
                type: "POST", 
                data: { 'id' : id, 'reviewer' : reviewer },
                success: function(data) {
                    alert(`Rewritten version of "${article}" was approved successfully!`);
                    location.reload();
                },
                error: function(xhr, status, error){  
                    console.log(xhr)
                    console.log(xhr.responseText, status, error);
                    alert("Error occurred when approving the rewrite. Please try again later.");
                }
            });
        }
    }
    
    function rejectRewrite(id, name, article, reviewer) {
        confirmed = confirm(`You are about to REJECT the rewritten version of "${article}" by ${name}. Click OK to proceed`);
        if (confirmed) {
            jQuery.ajax({
                url :"./ajax/reject-rewrite.php",
                type: "POST", 
                data: { 'id' : id, 'reviewer' : reviewer },
                success: function(data) {
                    alert(`Rewritten version of "${article}" was rejected successfully!`);
                    location.reload();
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

