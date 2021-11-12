<?php 

// check if user is allowed
if(!$iccaFunc->isAllowed($_SESSION['login_id'], "rewriteArticle")){
    echo "<script> location.href='index.php' </script>";
}

?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <strong>Articles Ready for Rewrite</strong>
                    </h3>
                    
                    <hr style="margin-top: 5px;">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="font-weight:bold;margin-top:3px;font-size:14px;">Article:</label>
                                <select class="form-control select2" id="articleFilter">
                                    <option value="">All</option>
                                    <?php 
                                    
                                    $articles = $icca_new_obj->getArticles(' WHERE status = 1 AND ready_status = 1 AND ready_for_export = 0 ORDER BY title');
                                    foreach($articles as $article){
                                    
                                    ?>
                                    <option value="<?php echo $article['id']; ?>"><?php echo $article['title']; ?></option>
                                    
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label style="font-weight:bold;margin-top:3px;font-size:14px;">Category:</label>
                                <select class="form-control select2" id="filterByCategory">
                                    <option value="">All</option>
                                    <?php $catLists = $iccaFunc->fetchCategoryList(); ?>
                                    <?php foreach ($catLists as $catList) : ?>
                                    <option value='<?php echo $catList['id']; ?>'><?php echo $catList['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="font-weight:bold;margin-top:3px;font-size:14px;">Content Writer:</label>
                                <select class="form-control select2" id="filterByEditor">
                                    <option value="">All</option>
                                    <?php $editors = $iccaFunc->fetchArticleCreator(); ?>
                                    <?php foreach ($editors as $editor) : ?>
                                    <option value='<?php echo $editor['user_id']; ?>'><?php echo $editor['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="dataTables_filter">
                                <label>
                                    Search:
                                    <input type="search" class="form-control input-sm" placeholder="" aria-controls="articlesTable" id="articlesTable_filter">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table id="readyForRewrite" class="table table-bordered table-striped">
                                <thead style="background:#2b415e; color:#fff;">
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Created by</th>
                                        <th>Rewrite Status</th>
                                        <th>Date Created</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                        
                                    </tr>
                                </thead> 
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Created by</th>
                                        <th>Rewrite Status</th>
                                        <th>Date Created</th>
                                        <th>Category</th>
                                        <th>Action</th>
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
    $(document).ready(function(){
        const readyForRewrite = $("#readyForRewrite").DataTable({
           "processing" : true,
           "serverSide" : true,
           "ajax" : { 
                url: "readyForRewriteData.php",
                dataSrc: function(json){
                    //console.log(json.data);
                var articles = [];
                return json.data.filter(function(item) {
                    if (!~articles.indexOf(item.title)) {
                        articles.push(item.title);
                        return item;
                        }
                    })
                }
            },
           "order" : [],
           "columns" : [
                { data: 'num' },
                { data: 'title' },    
                { data: 'creator'},        
                { data: 'rewrite_stats' },
                { data: 'date_created'},
                { data: 'category_id'},
                { data: 'action'}
                
               ],
               "columnDefs": [
                {
                    "targets": [0],
                    "visible": false
                }],
           
           "sDom": 'lrtip'
        });
        
        // readyForRewrite.on( 'order.dt search.dt', function () {
        //     readyForRewrite.column(0, { search: 'applied', order: 'applied' }).nodes().each( function(cell, i) {
        //         cell.innerHTML = i+1;
        //     })
        // }).draw(); 
        
        $("#filterByEditor").on("change", function(){
            readyForRewrite.column(2).search(this.value).draw();
        })
        
        $("#articleFilter").on("change", function(){
            readyForRewrite.column(0).search(this.value).draw();
        })
        
        $("#articlesTable_filter").on("keyup", function() {
            readyForRewrite.search(this.value).draw() ;
        });
        $("#filterByCategory").on("change", function(){
            readyForRewrite.column(0).search('').column(5).search(this.value).draw();
            
            //filter article selection by selected category
            $('#articleFilter').html('<option value="">Filtering...</option>');
    
            //article dropdown
            jQuery.ajax({
                url :"./ajax/article-dropdown1.php",
                type: "GET", 
                data: { 'cid' : this.value, 'export' : true },
                success: function(data) {
                    $('#articleFilter').html(data);
                },
                error: function(xhr, status, error){  
                    console.log(xhr)
                    console.log(xhr.responseText, status, error);
                    alert("Error occurred when filtering articles via selected category. Please try again later.");
                }
            }); 
        })
    });
    
    
</script>