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
    
//$icca_new_obj->setRewriteProgressAll();

?>

<section class="content">
    
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">Article Introduction</h4>
                            <div class="box-tools pull-right">
                                <a href="?page=rewriteArticleNew" class="btn btn-xs btn-primary">Go back</a>
                                <button class="btn btn-box-tool" type="button" data-widget="collapse">
                                <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <table id="articleIntroduction" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sentence</th>
                                            <th>Introduction</th>
                                            <th>Paragraph No.</th>
                                            <th>No. of approved rewrites</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <?php 
                                        $introduction = $iccaFunc->fetchArticleIntroduction($_GET['articleId']);
                                        foreach($introduction as $item){
                                            $sentences = $iccaFunc->fetchSentences($item['id']);
                                        }
                                        //print_r($sentences);
                                    ?>
                                    <tbody>
                                    <?php foreach($sentences as $sentence): ?>
                                        <tr>
                                            <td><?php echo $sentence['sentence']; ?></td>
                                            <td>Introduction</td>
                                            <td><?php echo $sentence['paragraph_no']; ?></td>
                                            <td><?php echo $iccaFunc->fetchApprovedRewrites($sentence['id']); ?></td>
                                            <td><a href='?page=theSentence&articleId=<?php echo $sentence['article_id']; ?>&contId=<?php echo $sentence['content_id']; ?>&parNo=<?php echo $sentence['paragraph_no']; ?>' class='btn btn-xs btn-success' target='blank'>
                                                <i class='fa fa-magic'></i>&nbsp;Rewrite
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Sentence</th>
                                            <th>Introduction</th>
                                            <th>Paragraph No.</th>
                                            <th>No. of approved rewrites</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">Article Items</h4>
                            <div class="box-tools pull-right">
                                <a href="?page=rewriteArticleNew" class="btn btn-xs btn-primary">Go back</a>
                                <button class="btn btn-box-tool" type="button" data-widget="collapse">
                                <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <table id="articleItem" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sentence</th>
                                            <th>Article Part</th>
                                            <th>Title</th>
                                            <th>Paragraph No.</th>
                                            <th>No. of approved rewrites</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <?php 
                                        $subHeadings = $iccaFunc->fetchSubheadingTitle($_GET['articleId']);
                                    ?>
                                    <tbody>
                                    <?php foreach($subHeadings as $item): 
                                            $sentences = $iccaFunc->fetchSentences($item['id'], "ORDER BY content_id ASC, paragraph_no ASC, order_no ASC");
                                            foreach($sentences as $sentence): ?>
                                                <tr>
                                                    <td><?php echo $sentence['sentence']; ?></td>
                                                    <td>Items</td>
                                                    <td><?php echo $item['title']; ?></td>
                                                    <td><?php echo $sentence['paragraph_no']; ?></td>
                                                    <td><?php echo $iccaFunc->fetchApprovedRewrites($sentence['id']); ?></td>
                                                    <td><a href='?page=theSentence&articleId=<?php echo $sentence['article_id']; ?>&contId=<?php echo $sentence['content_id']; ?>&parNo=<?php echo $sentence['paragraph_no']; ?>' class='btn btn-xs btn-success' target='blank'>
                                                        <i class='fa fa-magic'></i>&nbsp;Rewrite
                                                        </a>
                                                    </td>
                                                </tr>
                                    <?php
                                            endforeach;
                                        endforeach;
                                    ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Sentence</th>
                                            <th>Article Part</th>
                                            <th>Title</th>
                                            <th>Paragraph No.</th>
                                            <th>No. of approved rewrites</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">Article Conclusion</h4>
                            <div class="box-tools pull-right">
                                <a href="?page=rewriteArticleNew" class="btn btn-xs btn-primary">Go back</a>
                                <button class="btn btn-box-tool" type="button" data-widget="collapse">
                                <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <table id="articleConclusion" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sentence</th>
                                            <th>Article Part</th>
                                            <th>Paragraph No.</th>
                                            <th>No. of approved rewrites</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <?php 
                                        $conclusion = $iccaFunc->fetchConclusion($_GET['articleId']);
                                    ?>
                                    <tbody>
                                    <?php foreach($conclusion as $item): 
                                            $sentences = $iccaFunc->fetchSentences($item['id']);
                                            foreach($sentences as $sentence): ?>
                                                <tr>
                                                    <td><?php echo $sentence['sentence']; ?></td>
                                                    <td>Conclusion</td>
                                                    <td><?php echo $sentence['paragraph_no']; ?></td>
                                                    <td><?php echo $iccaFunc->fetchApprovedRewrites($sentence['id']); ?></td>
                                                    <td><a href='?page=theSentence&articleId=<?php echo $sentence['article_id']; ?>&contId=<?php echo $sentence['content_id']; ?>&parNo=<?php echo $sentence['paragraph_no']; ?>' class='btn btn-xs btn-success' target='blank'>
                                                        <i class='fa fa-magic'></i>&nbsp;Rewrite
                                                        </a>
                                                    </td>
                                                </tr>
                                    <?php
                                            endforeach;
                                        endforeach;
                                    ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Sentence</th>
                                            <th>Article Part</th>
                                            <th>Paragraph No.</th>
                                            <th>No. of approved rewrites</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</section>
<script>
$(document).ready(function() {
    $('#articleIntroduction').DataTable({
        "order": [[ 2, "asc" ]]
    });
    $('#articleItem').DataTable({
        "order": [[ 2, "asc" ]]
    });
    $('#articleConclusion').DataTable({
        "order": [[ 2, "asc" ]]
    });
} );
</script>