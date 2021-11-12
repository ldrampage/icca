<?php
    $isAdmin = $icca_new_obj->isAdmin($_SESSION['login_id']);
    $isEditor = $icca_new_obj->isEditor($_SESSION['login_id']);
    $isContentAdmin = $icca_new_obj->isContentAdmin($_SESSION['login_id']);
?>

<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel" style="padding:12px 10px">
            <div class="pull-left image">
                <img src="<?php echo HR_URL.$useri['photo']; ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $_SESSION['login_user']; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        
        <div style="color:#fff">
            <div class="clock" title="Server Date and Time" style="text-align: center;border-top: 1px solid #36474e;padding: 15px 0;">
                <span id="servertime"><i>Fetching...</i></span>
            </div>
        </div>
      
        <!--<form action="#" method="get" class="sidebar-form">-->
        <!--    <div class="input-group">-->
        <!--        <input type="text" name="q" class="form-control" placeholder="Search...">-->
        <!--          <span class="input-group-btn">-->
        <!--            <button type="submit" name="search" id="search-btn" class="btn btn-flat">-->
        <!--                <i class="fa fa-search"></i>-->
        <!--            </button>-->
        <!--          </span>-->
        <!--    </div>-->
        <!--</form>-->
      
        <ul class="sidebar-menu" >
            <li class="header">MAIN NAVIGATION</li>
            
            <li class="<?= !isset($_GET['page']) ? 'active' : '' ?> treeview">
              <a href="index.php">
                <i class="fas fa-tachometer-alt"></i>&nbsp;&nbsp;<span>Dashboard</span>
              </a>
            </li>
            
            <li class="<?= (isset($_GET['page']) && ($_GET['page'] == 'createArticleNew' || $_GET['page'] == 'rewriteArticleNew' || $_GET['page'] == 'editArticle' || $_GET['page'] == 'importArticle' || $_GET['page'] == 'category')) ? 'active' : '' ?> treeview">
                <a href="#">
                    <i class="fas fa-tasks"></i>&nbsp;&nbsp;<span>Article</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu <?= (isset($_GET['page']) && ($_GET['page'] == 'createArticleNew' || $_GET['page'] == 'rewriteArticleNew' || $_GET['page'] == 'editArticle' || $_GET['page'] == 'importArticle' || $_GET['page'] == 'category')) ? 'menu-open' : '' ?>">
                    <?php 
                        $fc_create = (isset($_GET['page']) && ($_GET['page'] == 'createArticleNew' || $_GET['page'] == 'editArticle')) ? '#fff' : '#8aa4af';
                        $fc_rewrite = (isset($_GET['page']) && $_GET['page'] == 'rewriteArticleNew') ? '#fff' : '#8aa4af';
                        $fc_importArt = (isset($_GET['page']) && $_GET['page'] == 'importArticle') ? '#fff' : '#8aa4af';
                        $fc_category = (isset($_GET['page']) && $_GET['page'] == 'category') ? '#fff' : '#8aa4af';
                    ?>
                    
                    <?php if($iccaFunc->isAllowed($_SESSION['login_id'], "createArticle")): ?>
                    <li class="disable"><a href="?page=createArticleNew" style="color:<?= $fc_create ?>"><i class="far fa-circle"></i>&nbsp;&nbsp;Add / Edit Article</a></li>
                    <?php endif; ?>
                    
                    <?php if($iccaFunc->isAllowed($_SESSION['login_id'], "rewriteArticle")): ?>
                    <li class="disable"><a href="?page=rewriteArticleNew" style="color:<?= $fc_rewrite ?>"><i class="far fa-circle"></i>&nbsp;&nbsp;Rewrite</a></li>
                    <?php endif; ?>
                    <?php if($iccaFunc->isAllowed($_SESSION['login_id'], "createArticle")): ?>
                    <!--<li class="disable"><a href="?page=importArticle" style="color:<?= $fc_importArt ?>"><i class="far fa-circle"></i>&nbsp;&nbsp;Article Import</a></li> -->
                    <?php endif; ?>
                    <?php if($iccaFunc->isAllowed($_SESSION['login_id'], "createArticle")): ?>
                    <li class="disable"><a href="?page=category" style="color:<?= $fc_category ?>"><i class="far fa-circle"></i>&nbsp;&nbsp;Article Category</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            
            <?php if($isEditor) { ?>
                <li class="<?= isset($_GET['page']) && $_GET['page'] == 'rewritten-sentences' ? 'active' : '' ?> treeview">
                    <a href="#">
                        <i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;<span>Rewrites</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu <?= isset($_GET['page']) && $_GET['page'] == 'rewritten-sentences' ? 'menu-open' : '' ?>">
                        <?php 
                            $fc_all = (isset($_GET['page']) && $_GET['page'] == 'rewritten-sentences' && (!isset($_GET['status']) || (isset($_GET['status']) && $_GET['status'] == 'all'))) ? '#fff' : '#8aa4af';
                            $fc_pending = (isset($_GET['page']) && $_GET['page'] == 'rewritten-sentences' && isset($_GET['status']) && $_GET['status'] == 0) ? '#fff' : '#8aa4af';
                            $fc_approved = (isset($_GET['page']) && $_GET['page'] == 'rewritten-sentences' && isset($_GET['status']) && $_GET['status'] == 1) ? '#fff' : '#8aa4af';
                            $fc_rejected = (isset($_GET['page']) && $_GET['page'] == 'rewritten-sentences' && isset($_GET['status']) && $_GET['status'] == 2) ? '#fff' : '#8aa4af';
                            $fc_rejected_rewritten = (isset($_GET['page']) && $_GET['page'] == 'rewritten-sentences' && isset($_GET['status']) && $_GET['status'] == 3) ? '#fff' : '#8aa4af';
                        ?>
                        <li class="disable"><a href="?page=rewritten-sentences" style="color:<?= $fc_all ?>"><i class="far fa-circle"></i>&nbsp;&nbsp;All</a></li>
                        <li class="disable"><a href="?page=rewritten-sentences&status=0" style="color:<?= $fc_pending ?>"><i class="far fa-circle"></i>&nbsp;&nbsp;Pending</a></li>
                        <li class="disable"><a href="?page=rewritten-sentences&status=1" style="color:<?= $fc_approved ?>"><i class="far fa-circle"></i>&nbsp;&nbsp;Approved</a></li>
                        <li class="disable"><a href="?page=rewritten-sentences&status=2" style="color:<?= $fc_rejected ?>"><i class="far fa-circle"></i>&nbsp;&nbsp;Rejected</a></li>
                        <li class="disable"><a href="?page=rewritten-sentences&status=3" style="color:<?= $fc_rejected_rewritten ?>"><i class="far fa-circle"></i>&nbsp;&nbsp;Rejected & Rewritten</a></li>
                    </ul>
                </li>
            <?php } ?>
            
            <?php if($isAdmin) { ?>
                <li class="<?= isset($_GET['page']) && $_GET['page'] == 'assignments' ? 'active' : '' ?> treeview">
                  <a href="?page=assignments">
                    <i class="fas fa-users"></i>&nbsp;&nbsp;<span>Assignments</span>
                  </a>
                </li>
            <?php } ?>
            
            <?php if(in_array($_SESSION['login_id'], [4,43,44])) { ?>
                <li class="<?= isset($_GET['page']) && $_GET['page'] == 'credentials' ? 'active' : '' ?> treeview">
                  <a href="?page=credentials">
                    <i class="fas fa-key"></i>&nbsp;&nbsp;<span>API Credentials</span>
                  </a>
                </li>
            <?php } ?>
            
            <?php if($isContentAdmin) { ?>
                <li class="<?= isset($_GET['page']) && $_GET['page'] == 'export-article' ? 'active' : '' ?> treeview">
                  <a href="?page=export-article">
                    <i class="fas fa-file-export"></i>&nbsp;&nbsp;<span>Export</span>
                  </a>
                </li>
            <?php } ?>
            <?php if($isContentAdmin) { ?>
                <li class="<?= isset($_GET['page']) && $_GET['page'] == 'text-tool' ? 'active' : '' ?> treeview">
                  <a href="?page=text-tool&tool=1">
                    <i class="fas fa-wrench"></i>&nbsp;&nbsp;<span>Tool</span>
                  </a>
                </li>
            <?php } ?>
            <?php if($isAdmin /*in_array($_SESSION['login_id'], [4,43,44,57,3])*/) { ?>
                <li class="<?= isset($_GET['page']) && $_GET['page'] == 'performanceReport' ? 'active' : '' ?> treeview">
                  <a href="?page=performanceReport&mode=0">
                    <i class="fas fa-industry"></i>&nbsp;&nbsp;<span>Reports</span>
                  </a>
                </li>
            <?php } ?>
        </ul>
    </section>
</aside>

<script type="text/javascript">
    var currenttime = "<?= date("F d, Y h:i:s A"); ?>" //PHP method of getting server date
    
    var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
    var serverdate=new Date(currenttime)
    
    function padlength(what){
        var output=(what.toString().length==1)? "0"+what : what
        return output
    }
    
    function displaytime(){
        let hr_12_format = (serverdate.getHours()%12 || 12);
        let period = (serverdate.getHours() < 12) ? "AM" : "PM";
        
        serverdate.setSeconds(serverdate.getSeconds()+1)
        var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()
        var timestring=padlength(hr_12_format)+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())+" "+period
        document.getElementById("servertime").innerHTML=datestring+"<br>"+timestring
    }
    
    window.onload=function(){
        displaytime();
        setInterval("displaytime()", 1000)
    }
</script>