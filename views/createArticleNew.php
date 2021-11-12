<?php 

// check if user is allowed
if(!$iccaFunc->isAllowed($_SESSION['login_id'], "createArticle")){
    echo "<script> alert('User not allowed'); location.href='index.php' </script>";
}

if(isset($_POST['createArt'])){
    $message = $iccaFunc->addArticle($_POST);
}

?>
<section class="content">
    <?php if(isset($message)): ?>
    <div class="row">
        <?php 
            if(is_numeric($message)){
                echo "<script> location.href='?page=createArticleNew&articleId=$message'; </script>";
            }
        ?>
        <?php if($message == false) : ?>
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
                We encountered a problem with the previous submission. Please try again...
            </div>
        </div>
        <?php endif; ?>
        
        <?php if($message == "invalid_user") : ?>
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Warning!</h4>
                The account you are using does not have any permission to create a new article!
            </div>
        </div>
        <?php endif; ?>
        
        <?php if($message == "ArticleExist") : ?>
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Warning!</h4>
                Article Title Already Exist!
            </div>
        </div>
        <?php endif; ?>
        
    </div>
    <?php endif; ?>
    
    <?php if(!isset($_GET['articleId'])):  ?>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <form method="post">
                        <input type="hidden" name="creator" value="<?php echo $_SESSION['login_id']; ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Create Article</h4>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                <label>Article Title</label> 
                                <input class="form-control" type="text" name="title" placeholder="Enter..." required/>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="form-group">
                                    <button class="btn btn-success" type="submit" name="createArt">Create</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Articles</h3>
                    
                    <hr style="margin-top: 5px;">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <label style="font-weight:bold;margin-top:3px;font-size:14px;">Article:</label>
                            <select id="articleTitleFilter" class="form-control select2">
                                <option value="">All</option>
                                <?php
                                    $articles = $icca_new_obj->getArticles(' WHERE status = 1 ORDER BY title');
                                    foreach($articles as $article){
                                ?>
                                <option value="<?php echo $article['id']; ?>"><?php echo $article['title']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label style="font-weight:bold;margin-top:3px;font-size:14px;">Category:</label>
                                <select class="form-control select2" id="filterByCategory">
                                    <option value="">All</option>
                                    <?php $categories = $iccaFunc->fetchAllCategory(); ?>
                                    <?php foreach ($categories as $category) : ?>
                                    <option value='<?php echo $category['id']; ?>'><?php echo $category['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-weight:bold;margin-top:3px;font-size:14px;">Content Writer:</label>
                                <select class="form-control select2" id="filterByCreator">
                                    <option value="">All</option>
                                    <?php $editors = $iccaFunc->fetchArticleCreator(); ?>
                                    <?php foreach ($editors as $editor) : ?>
                                    <option value='<?php echo $editor['user_id']; ?>'><?php echo $editor['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        
                        
                        <div class="col-md-2 col-sm-12">    
                            <label style="font-weight:bold;margin-top:3px;font-size:14px;">Date:</label>
                            <div class="form-group">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fas fa-calendar-alt"></i></div>
                                    <input type="text" style="font-size:14px" class="form-control dpick" id="datepicker" name="dateFilter">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-2 col-sm-12">    
                            <div class="form-group">
                                <label style="font-weight:bold;margin-top:3px;font-size:14px;">Export Status:</label>
                                <select class="form-control select2" id="filterByExportStatus">
                                    <option value="">All</option>
                                    <option value="1" <?= (isset($_GET['export']) && $_GET['export'] == 1) ? 'selected' : '' ?>>Ready</option>
                                    <option value="0" <?= (isset($_GET['export']) && $_GET['export'] == 0) ? 'selected' : '' ?>>Not Ready</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php if(in_array($_SESSION['login_id'], [4,43,44])) {  ?>
                    <div class="row">
                        <div class="col-md-2 col-sm-12">    
                            <div class="form-group">
                                <label style="font-weight:bold;margin-top:3px;font-size:14px;">Status:</label>
                                <select class="form-control select2" id="filterByStatus">
                                    <option value="">All</option>
                                    <option value="1">Ready</option>
                                    <option value="0">Not Ready</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="box-body table-responsive">
                    <div class="col-lg-12 col-md-12" style="padding:0">
                        <div class="dataTables_filter">
                            <label>
                                Search:
                                <input type="search" class="form-control input-sm" placeholder="" aria-controls="articlesTable" id="articlesTable_filter">
                            </label>
                        </div>
                    </div>
                    <table id="articleList" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="mwidth-200" bgcolor="#2b415e" style="color: white;">id</th>
                                <th class="mwidth-200" bgcolor="#2b415e" style="color: white;">Title</th>
                                <th class="mwidth-120" bgcolor="#2b415e" style="color: white;">Created By</th>
                                <th class="mwidth-130" bgcolor="#2b415e" style="color: white;">Date Created</th>
                                <th class="mwidth-100" bgcolor="#2b415e" style="color: white;">Rewrite Stats</th>
                                <th class="mwidth-200" bgcolor="#2b415e" style="color: white;">Action</th>
                                <th class="mwidth-80" bgcolor="#2b415e" style="color: white;">Status</th>
                                <th class="mwidth-80" bgcolor="#2b415e" style="color: white;">Export Status</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        
                        <tfoot>
                            <tr>
                                <th class="mwidth-200">id</th>
                                <th class="mwidth-200">Title</th>
                                <th class="mwidth-120">Created By</th>
                                <th class="mwidth-130">Date Created</th>
                                <th class="mwidth-100">Rewrite Stats</th>
                                <th class="mwidth-200">Action</th>
                                <th class="mwidth-80">Status</th>
                                <th class="mwidth-80">Export Status</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div>
                <div class="box-footer">
                </div>
            </div>        
        </div>
    </div>
    
    
    <?php endif; ?>
    
    
    <?php if(isset($_GET['articleId']) && $iccaFunc->articleExist($_GET['articleId'])): $article = $iccaFunc->getArticle($_GET['articleId']); //print_r($article); ?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <form method="post">
                        <input type="hidden" name="creator" value="<?php echo $_SESSION['login_id']; ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Create Article</h4>
                                
                            </div>
                            <div class="modal-body">
                    
                                <div class="form-group">
                                    <label>Article Title</label> 
                                    <input class="form-control" type="text" placeholder="Enter..." value= "<?php echo $article[0]['title']; ?>" required/>
                                </div>
                                <?php 
                                    $intros = $iccaFunc->fetchArticleIntroduction($_GET['articleId']);
                                    //print_r($intros);
                                    $wholeIntroduction = "";
                            
                                    foreach($intros as $intro){
                                        if($intro['type'] ==  "introduction"){
                                            if($intro['title'] != "none"){ $wholeIntroduction .= $intro['title'] . "\n\n"; }
                                            $sentences = $iccaFunc->fetchSentences($intro['id']);
                                            //print_r($sentences);
                                            $parNo = 1;
                                            foreach($sentences as $sentence){
                                                if($parNo == $sentence['paragraph_no']){
                                                    $wholeIntroduction .= $sentence['sentence'] . " ";
                                                } else{
                                                    $parNo++;
                                                    $wholeIntroduction .= "\n\n" . $sentence['sentence'] . " "; 
                                                }
                                            }
                                            $wholeIntroduction .= "\n\n";
                                        }
                                    }
                                ?>
                                <?php if(!empty($intros)){?>
                                <div class="form-group">
                                    <label>Article Introduction</label> 
                                    <textarea class="form-control" rows="10" readonly><?php if(htmlentities($wholeIntroduction)=="") echo $wholeIntroduction; else echo htmlentities($wholeIntroduction); ?></textarea>  
                                </div>
                                <?php } else {?>
                                
                                <div class="form-group">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success " data-toggle="modal" data-target="#addIntro">Add Introduction</button>
                                    </div>
                                    <label>Article Introduction</label>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h4><i class="icon fa fa-ban"></i> This article does not have any article introduction!</h4>
                                        Click the add introduction button above to add an article introduction...
                                    </div>
                                </div>
                                
                                
                                <?php } ?>
                                
                                <?php 
                                    $subheadings = $iccaFunc->fetchSubheadingTitle($_GET['articleId']);
                                    $wholeSubH = "";
                                    //print_r($subheadings);
                                    
                                    foreach($subheadings as $subH){
                                        
                                        if($subH['type'] ==  "subheading"){
                                            $wholeSubH .= $subH['title'] . "\n\n";
                                            $sentences = $iccaFunc->fetchSentences($subH['id']);
                                            $parNo = 1;
                                            foreach($sentences as $sentence){
                                                //echo $sentence['sentence'] . "<br>";
                                                if($parNo == $sentence['paragraph_no']){
                                                    $wholeSubH .= $sentence['sentence'] . " ";
                                                } else{
                                                    $parNo++;
                                                    $wholeSubH .= "\n\n" . $sentence['sentence'] . " "; 
                                                } 
                                            }
                                            $wholeSubH .= "\n\n";
                                        }
                                    } 
                                
                                ?>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#addSubh">Add Items</button>
                                </div>
                                <?php if(!empty($subheadings)){?>
                                <div class="form-group">
                                    <label>Article Items</label>
                                    <textarea class="form-control" rows="15" readonly><?php if(htmlentities($wholeSubH)=="") echo $wholeSubH; else  echo htmlentities($wholeSubH); ?></textarea>  
                                </div>
                                <?php } else {?>
                                 <div class="form-group">
                                    <label>Article Items</label>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h4><i class="icon fa fa-ban"></i> This article does not have any items!</h4>
                                        Click the add item button above to add item...
                                    </div>
                                </div>
                                <?php } ?>
                                
                                <?php 
                                    $conclusion = $iccaFunc->fetchConclusion($_GET['articleId']);
                                    //print_r($conclusion);
                                    $wholeConclu = "";
                                    
                                    foreach($conclusion as $con){
                                        
                                        if($con['type'] ==  "conclusion"){
                                            if(trim($con['title']) != "none") { $wholeConclu .= $con['title'] . "\n\n"; }
                                            $sentences = $iccaFunc->fetchSentences($con['id']);
                                            $parNo = 1;
                                            foreach($sentences as $sentence){
                                                //echo $sentence['sentence'] . "<br>";
                                                if($parNo == $sentence['paragraph_no']){
                                                    $wholeConclu .= $sentence['sentence'] . " ";
                                                } else{
                                                    $parNo++;
                                                    $wholeConclu .= "\n\n" . $sentence['sentence'] . " "; 
                                                } 
                                            }
                                            $wholeConclu .= "\n\n";
                                        }
                                    } 
                                ?>
                                <?php if(!empty($conclusion)){?>
                                <div class="form-group">
                                    <label>Article Conclusion</label>
                                    <textarea class="form-control"  rows="15" readonly ><?php if(htmlentities($wholeConclu)=="") echo $wholeConclu; else echo htmlentities($wholeConclu); ?></textarea>  
                                </div>
                                <?php } else {?>
                                 <div class="form-group">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addConclusion">Add Conclusion</button>
                                    </div>
                                    <label>Article Conclusion</label>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h4><i class="icon fa fa-ban"></i> This article does not have any conclusion!</h4>
                                        Click the add conclusion button above to add...
                                    </div>
                                </div>
                                <?php } ?>
                                
                            </div>
                            <div class="modal-footer">
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    
    <!-- Modal -->
    <div class="modal fade" id="addIntro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Article Main Heading Introduction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Article</label>
                        <input class="form-control" type="text" disabled value="<?php echo $article[0]['title']; ?>">
                    </div>
                    <div class="form-group">
                        <label>
                            <input name="showIntroTitle" type="checkbox">
                            Do you need a title?
                        </label>
                    </div>
                    <div class="form-group">
                        <label>How many paragraphs does this article (main heading) need?</label>
                        <select class="form-control" id="paragCount">
                            <option value=""></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">6</option>
                            <option value="5">7</option>
                            <option value="5">8</option>
                            <option value="5">9</option>
                            <option value="5">10</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <p>Format Paragraph(Paste then Copy)</p>
                        <textarea class="form-control" id="articleIntroFormat" oninput="formatParagraph(this.value,this.id)" rows="5" style="border: solid 2px #6a0dad;"></textarea>
                    </div>
                    <form id="formP" method="POST">
                        <div class="form-group" id="introTitle" style="display:none;">
                            <label>Introduction Title</label>
                            <p id='introTitleChar'></p>
                            <input class="form-control" type="text" value="" name="introduction_title" oninput='introTitleCount(this.value)'>
                        </div>
                        <input type="hidden" value="<?php echo $_GET['articleId']; ?>" name="article_id">
                        <input type="hidden" value="introduction" name="type">
                        <input type="hidden" value="formP" name="formP">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                    </form> 
                </div>
            </div>
        </div>
    </div>
    
    
    <!-- Modal add Subheading -->
    <div class="modal fade" id="addSubh" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Article Items</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>How many paragraphs does this item need?</label>
                        <select class="form-control" id="subhParagraphCount">
                            <option value=""></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <p>Format Paragraph(Paste then Copy)</p>
                        <textarea class="form-control" id="articleItemsFormat" oninput="formatParagraph(this.value,this.id)" rows="5" style="border: solid 2px #6a0dad;"></textarea>
                    </div>
                    <form id="formS">
                        <input type="hidden" value="<?php echo $_GET['articleId']; ?>" name="article_id">
                        <input type="hidden" value="formS" name="formS"/>
                        <input type="hidden" value="subheading" name="type">
                        <div class="form-group" id="subHT">
                            <label>Item Title</label>
                            <p id='itemTitleChar'></p>
                            <input type="text" class="form-control" oninput='countItemTitleChar(this.value)' name="subheading_title" placeholder="Enter..." required >
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                    </form> 
                </div>
            </div>
        </div>
    </div>
    
    
    <!-- Modal add Subheading -->
    <div class="modal fade" id="addConclusion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Article Conclusion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            <input name="showConcluTitle" type="checkbox">
                            Do you need a title?
                        </label>
                    </div>
                    <div class="form-group">
                        <label>How many paragraphs does this conclusion need?</label>
                        <select class="form-control" id="conParagraphCount">
                            <option value=""></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <p>Format Paragraph(Paste then Copy)</p>
                        <textarea class="form-control" id="articleConcluFormat" oninput="formatParagraph(this.value,this.id)" rows="5" style="border: solid 2px #6a0dad;"></textarea>
                    </div>
                    <form id="formG">
                        <div class="form-group" id="concluTitle" style="display:none;">
                            <label>Conclusion Title</label>
                            <p id='concluTitleChar'></p>
                            <input class="form-control" type="text" value="" name="conclusion_title" oninput='concluCharCount(this.value)'>
                        </div>
                        <input type="hidden" value="<?php echo $_GET['articleId']; ?>" name="article_id">
                        <input type="hidden" value="formG" name="formG"/>
                        <input type="hidden" value="conclusion" name="type">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                    </form> 
                </div>
            </div>
        </div>
    </div>
    
</section>
<script>
$(document).ready(function(){
    
    $("input[name=dateFilter]").datepicker({
        format: "yyyy-mm-dd",
        endDate: new Date(),
        autoclose: true,
    })
    
    const articleListTb = $("#articleList").DataTable({
            "processing" : true,
            "serverSide" : true,
            "ajax" : {
                "url": "articleListDataTable.php",
                "data": function (d) {
                    d.userLogin = <?php echo $_SESSION['login_id']; ?>
                }
            },
            "order":[[ 3, "desc" ]],
            
            "columnDefs": [
                {
                    "targets": [0],
                    "visible": false
                },
                {
                    "targets":[8],
                    "visible": false,
                },
                {
                    "targets":[9],
                    "visible": false,
                }],
            "sDom": 'lrtip'
            
                /*
            initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
                    $( 'input', this.footer() ).on( 'keyup change clear', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                        }
                    });
                });
            } */
        
        });
        
    $("#articlesTable_filter").on("keyup", function() {
        articleListTb.search(this.value).draw() ;
    });
    
    <?php if(isset($_GET['export'])): ?>
        <?php if($_GET['export'] == 0){ ?>
            articleListTb.column(7).search(0).draw();
        <?php } else { ?>
            articleListTb.column(7).search(1).draw();
        <?php } ?>
    <?php endif; ?>
    
    $('#filterByStatus').on("change", function(){
        articleListTb.column(6).search(this.value).draw();
    })
    
    $('#filterByExportStatus').on("change", function(){
        articleListTb.column(7).search(this.value).draw();
    })
    
    $('#filterByCreator').on("change", function(){
        articleListTb.column(2).search(this.value).draw();
    })
    
    $("#filterByCategory").on("change", function(){
        articleListTb.column(0).search('').column(9).search(this.value).draw();
            
        //filter article selection by selected category
        $('#articleTitleFilter').html('<option value="">Filtering...</option>');

        //article dropdown
        jQuery.ajax({
            url :"./ajax/article-dropdown1.php",
            type: "GET", 
            data: { 'cid' : this.value },
            success: function(data) {
                $('#articleTitleFilter').html(data);
            },
            error: function(xhr, status, error){  
                console.log(xhr)
                console.log(xhr.responseText, status, error);
                alert("Error occurred when filtering articles via selected category. Please try again later.");
            }
        }); 
    })
        
        
    $("#articleTitleFilter").on("change", function(){
        articleListTb.column(0).search(this.value).draw();
    })
    
    $("input[name=dateFilter]").on("changeDate", function() {
        console.log(this.value);
        articleListTb.search(this.value.trim()).draw();
    });
    
    
    $("#addConclusion").on("hide.bs.modal",function(e){
        $(".textareas").remove();
        $("#conParagraphCount").val("");
        $("#articleConcluFormat").val("");
        document.getElementById("concluTitleChar").innerHTML = "";
    });

    $("#conParagraphCount").change(function(){
        $(".textareas").remove();
        var paragraphs = $("#conParagraphCount").val();
        for(var i = 1; i <= paragraphs; i++) {
            $("#formG").append("<div class='textareas form-group'><label>Paragraph "+i+"</label><p id='wordCharCount"+i+"'></p><p id='noti"+i+"'></p><textarea id='"+i+"' oninput='checkInput(this.value,this.id)' name='par"+i+"' class='form-control conCluText' rows='6' placeholder='Enter...' required></textarea></div>");
        }
        $("input[name=conclusion_title]").focus();
    });
    
    /*
    $("#formG").on('change paste keyup','.conCluText', function(e){
        var id = e.target.id;
        var val = $(this).val();
        function countWords(s){ s = s.replace(/(^\s*)|(\s*$)/gi,""); s = s.replace(/[ ]{2,}/gi," "); s = s.replace(/\n /,"\n"); return s.split(' ').length; }
        var par = "wordCharCount"+id;
        document.getElementById(par).innerHTML = "Words: <strong>"+countWords(val)+"</strong> Characters: <strong>"+val.length+"</strong>";
    }); */
    
    $("input[name=showConcluTitle]").click(function(){
        //console.log("aslkdjfs");
        if($(this).is(":checked")){
            document.getElementById("concluTitle").style.display="block";
            $("input[name=conclusion_title]").val("");
            $("input[name=conclusion_title]").focus();
        } else{
            $("input[name=conclusion_title]").val("");
            document.getElementById("concluTitle").style.display="none";
            document.getElementById("concluTitleChar").innerHTML = "";
        }
    });
    
    
    $("#formG").submit(function(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        var flagCount = true;
        //console.log(data);
        if(data.length < 5){
            // do not submit
        } else{
            
            console.log(data);
            for(i=4; i < data.length; i++){
                if((data[i].value.indexOf("[sentence-end]")) != -1) {
                    //console.log("exist");
                    var noti = "noti"+(i-3);
                    document.getElementById(noti).innerHTML = "";
                } else{
                    //console.log("does not");
                    var noti = "noti"+(i-3);
                    document.getElementById(noti).innerHTML = "<strong style='color:red;'>Incorrect Format!</strong>";
                    flagCount = false;
                }
            }
            
            if(flagCount) {
                $.ajax({
                    type: "POST",
                    url: "insertThis2.php",
                    data: data,
                    success: function(response){
                        //console.log(response);
                        if(response == "1"){
                            alert("Conclusion has been saved successfully.");
                            location.href = window.location.href
                        } else{
                            alert("Something went wrong. The application will refresh");
                            location.href = window.location.href
                        }
                    }
                });
            }
        }
    });

    /* Article Introduction */
    $("#addIntro").on("hide.bs.modal",function(e){
        $(".textareas").remove();
        $("#paragCount").val("");
        $("#articleIntroFormat").val("");
        document.getElementById("introTitleChar").innerHTML = "";
    });
    
    $("#paragCount").change(function(){
    $(".textareas").remove();
    
        var paragraphs = $("#paragCount").val();
        for(var i = 1; i <= paragraphs; i++) {
            $("#formP").append("<div class='textareas form-group'><label>Paragraph "+i+"</label><p id='wordCharCount"+i+"'></p><p id='noti"+i+"'></p><textarea id='"+i+"' oninput='checkInput(this.value,this.id)' name='par"+i+"' class='form-control' rows='6' placeholder='Enter...' required></textarea></div>");
        }
       
        $("input[name='article_title']").focus();
    });
    
    $("input[name=showIntroTitle]").click(function(){
        //console.log("kljsdfs");
        if($(this).is(":checked")){
            document.getElementById("introTitle").style.display="block";
            $("input[name=introduction_title]").val("");
        } else{
            $("input[name=introduction_title]").val("");
            document.getElementById("introTitle").style.display="none";
            document.getElementById("introTitleChar").innerHTML = "";
        }
    });
    
    $("#formP").submit(function(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        var flagCount = true;
        //console.log(data);
        if(data.length < 5){
            // do not submit
        } else{
            
            console.log(data);
            for(i=4; i < data.length; i++){
                if((data[i].value.indexOf("[sentence-end]")) != -1) {
                    //console.log("exist");
                    var noti = "noti"+(i-3);
                    document.getElementById(noti).innerHTML = "";
                } else{
                    //console.log("does not");
                    var noti = "noti"+(i-3);
                    document.getElementById(noti).innerHTML = "<strong style='color:red;'>Incorrect Format!</strong>";
                    flagCount = false;
                }
            }
            if(flagCount) {
                console.log("works");
                $.ajax({
                    type: "POST",
                    url: "insertThis2.php",
                    data: data,
                    success: function(response){
                        console.log(response);
                        if(response == "1"){
                            alert("Introduction has been saved successfully.");
                            location.href = window.location.href
                        } else{
                            alert("Something went wrong. The application will refresh");
                            location.href = window.location.href
                        }
                    }
                });
            }
        }
        
    }); 
    
    /***** Add Subheading ********/
    
    $("#addSubh").on("hide.bs.modal", function(e){
        $(".addedParagraphSubH").remove();
        $("#subhParagraphCount").val("");
        $('input[name ="subheading_title"]').val("");
        $("#articleItemsFormat").val("");
        document.getElementById("itemTitleChar").innerHTML = "";
    });

    $("#subhParagraphCount").change(function(){
        $(".addedParagraphSubH").remove();
        var paragraphs = $("#subhParagraphCount").val();
        for(var i = paragraphs; i > 0; i--) {
            $("<div class='form-group addedParagraphSubH'><label>Paragraph "+i+"</label><p id='wordCharCount"+i+"'></p><p id='noti"+i+"'></p><textarea id='"+i+"' oninput='checkInput(this.value,this.id)' name='par"+i+"' class='form-control' rows='6' placeholder='Enter... " +i+ "' required></textarea>").insertAfter("#subHT");
        }
        $("input[name='subheading_title']").focus();
    });
    
    $("#formS").submit(function(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        var flagCount = true;
        //console.log(data);
        if(data.length < 5) {
            // do not submit
        } else{
            console.log(data);
            for(i=4; i < data.length; i++){
                if((data[i].value.indexOf("[sentence-end]")) != -1) {
                    //console.log("exist");
                    var noti = "noti"+(i-3);
                    document.getElementById(noti).innerHTML = "";
                } else{
                    //console.log("does not");
                    var noti = "noti"+(i-3);
                    document.getElementById(noti).innerHTML = "<strong style='color:red;'>Incorrect Format!</strong>";
                    flagCount = false;
                }
            }
            
            if(flagCount){
                $.ajax({
                    type: "POST",
                    url: "insertThis2.php",
                    data: data,
                    success: function(response){
                        //console.log(response);
                        if(response == "1"){
                            alert("Item has been saved successfully");
                            location.href = window.location.href
                        } else{
                            alert("Something went wrong. The application will refresh.");
                            location.href = window.location.href
                        }
                    }
                }) 
            }
        }
        
    });
    
    $("#example10").DataTable({
        "order":[[2, "desc"]]
    });
    
   
    /*
    $(".deleteButton").click(function(e){
        if(typeof e.target.value !== "undefined"){
            //console.log(e.target.value);
            var data = e.target.value.split(":::");
            
            var obj = {article_id:data[0],user_id:data[1],deleteArticle:data[2]};
            var res  = window.confirm("Are you sure you?");
            if(res){
                $.ajax({
                    type: "POST",
                    url: "insertThis2.php",
                    data: obj,
                    success: function(response){
                        console.log(response);
                        if(response == "1"){
                            alert("Article successfully deleted");
                            location.href = window.location.href
                        } else{
                            //alert("Something went wrong. Please try again");
                        }
                    }
                })
            }
            
        }
    });
    */
});

function deleteArt(id){
    var user = <?php echo $_SESSION['login_id']; ?>;
    var obj = {article_id: id, user_id:user, deleteArticle: "delete"};
    console.log(obj);
    var res  = window.confirm("Are you sure you?");
    if(res){
        $.ajax({
            type: "POST",
            url: "insertThis2.php",
            data: obj,
            success: function(response){
                console.log(response);
                if(response == "1"){
                    alert("Article successfully deleted");
                    location.href = window.location.href
                } else{
                    //alert("Something went wrong. Please try again");
                }
            }
        })
    }
}

function countWords(s){ s = s.replace(/(^\s*)|(\s*$)/gi,""); s = s.replace(/[ ]{2,}/gi," "); s = s.replace(/\n /,"\n"); return s.split(' ').length; }
function checkInput(x,id){
    var par = "wordCharCount"+id;
    var sentenceCount = (x.match(/\[sentence\-end\]/g) || []).length;
    m = x.replace(/\[sentence\-end\]/g,'');
    //console.log(m);
    
    document.getElementById(par).innerHTML = "Words: <strong>"+countWords(m)+"</strong> Characters: <strong>"+m.length+"</strong> Sentence: <strong>"+sentenceCount+"</strong>";
}

function ready(art_id){
    var user_id = <?php echo $_SESSION['login_id']; ?>;
    var data = {article_id:art_id, user_id: user_id, ready: "1"};
    console.log(data);
    $.ajax({
        type: "POST",
        url: "insertThis2.php",
        data: data,
        success: function(response){
            if(response == "1"){
                //location.href = "?page=createArticleNew";
                $("#articleList").DataTable().ajax.reload();
            }
        }
    }) 
}

function saveAsDraft(art_id){
    var user_id = <?php echo $_SESSION['login_id']; ?>;
    var data = { article_id: art_id, user_id: user_id, ready: "0" }
    console.log(data);
    $.ajax({
        type: "POST",
        url: "insertThis2.php",
        data: data,
        success: function(response){
            if(response == "1"){
                //location.href = "?page=createArticleNew";
                $("#articleList").DataTable().ajax.reload();
            }
        }
    }) 
}

function formatParagraph(x,id){
    //console.log(x);
    id = "#"+id;
    var data = {value:x,trigger:"this"}
    $.ajax({
        type: "POST",
        url: "insertThis2.php",
        data: data,
        success: function(response){
            console.log(response);
            $(id).val(response);
        }
    });
}

function countItemTitleChar(val){
    document.getElementById("itemTitleChar").innerHTML = "Characters: <strong>"+val.length+"</strong>";
}

function introTitleCount(val){
    document.getElementById("introTitleChar").innerHTML = "Characters: <strong>"+val.length+"</strong>";
}

function concluCharCount(val){
    document.getElementById("concluTitleChar").innerHTML = "Characters: <strong>"+val.length+"</strong>";
}


    
</script>










