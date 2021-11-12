<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <label>Choose Tool:</label>
                    <select class="form-control select2" onchange="switchTool(this.value)">
                        <option value="0" <?php if(isset($_GET['tool'])) { if($_GET['tool'] == 0) echo "selected";   } ?>>Format Paragraph</option>
                        <option value="1" <?php if(isset($_GET['tool'])) { if($_GET['tool'] == 1) echo "selected";   } ?>>Format Article</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <?php if(isset($_GET['tool'])) : if($_GET['tool'] == 0){ ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h6 class="box-title">Note:</h6>
                    <pre>
1. This tool will help you place the delimeter <span style="color:red;">[sentence-end]</span> in each sentence of a the paragraph.
2. Auto placing of delimeter <span style="color:red;">[sentence-end]</span> is not 100% accurate.
3. The <span style="color:blue;">[sentence-end]</span> will <span style="color:red;">not occur</span> if a . ! ? is not followed by a (whitespace).
4. The <span style="color:blue;">[sentence-end]</span> will occur even if it's not end of sentence in cases like
        -- The . ! ? is between <span style="color:red;"> " " (double quote) or ' ' (single quote)</span>. Please watch out for this.
5. Cleanse the result if you see any issues with the new format.
                    </pre>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Article Item Title (Paste Title)</label>
                                <input type="text" class="form-control" id="artItemTitle" onPaste="placeTitleTag()">
                            </div> 
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Paste Paragraph</label>
                                <textarea oninput="formatParagraph(this.value)" class="form-control" rows="10" id="changePar"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-info btn-md pull-right" onclick="">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    
    <?php } endif; ?>
    
    <?php if(isset($_GET['tool'])) : if($_GET['tool'] == 1){ ?>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn btn-warning btn-md" onclick="clearAll()">Clear All</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Article Title</label>
                                <input type="text" class="form-control" id="artTitle" onpaste="pasteArtTitle()"/>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>Article Introduction</label>
                                <textarea class="form-control textarea" id="artIntro" rows="9" onpaste="pasteArtIntro()"></textarea>
                            </div>
                             <hr>
                            <div class="form-group">
                                <label>Article Items</label>
                                <textarea class="form-control" id="artItems" rows="9" onpaste="pasteArtItems()"></textarea>
                            </div>
                             <hr>
                            <div class="form-group">
                                <label>Article Conclusion</label>
                                <textarea class="form-control" id="artConclu" rows="9" onpaste="pasteArtConclu()"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-sm" onclick="combine()">Combine</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            <!--
                            <div class="form-group">
                                <button class="btn btn-success btn-sm pull-right" onclick="copyAll()">Copy</button>
                            </div> -->
                            <div class="form-group">
                                <button class="btn btn-success btn-sm" onclick="save()">Download</button>
                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#addCategory">Import</button>
                                <!--<button class="btn btn-info btn-sm" onclick="imports()">Import</button> -->
                            </div>
                            <div class="form-group">
                                <label>Article</label> <span style="display:none; font-size: 18px;" id="copySign5">&nbsp;(Copied)</span>
                                <textarea class="form-control" rows="50" id="wholeArticle"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Category</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php 
                                $categories = $iccaFunc->fetchAllCategory();
                            ?>
                            <div class="form-group">
                                <select id="category" class="form-control select">
                                    <?php 
                                        foreach($categories as $category){
                                    ?>
                                    <option value="<?php echo $category['id'] ?>"><?php echo $category['name']; ?></option>
                                    
                                    <?php }  ?>
                                </select>
                            </div>
                            <button class="btn btn-info btn-sm" onclick="imports()">Done</button>
                        </div>
                    </div>
                </div> 
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    
    <?php } endif; ?>
</section>
<?php if(isset($_GET['tool'])): if($_GET['tool'] == 1){ ?>
<script>
    $(document).ready(function(){
        $("#artTitle").on("keyup", function(e){
            if(e.keyCode === 13){
                setTimeout(function(){
                    var x = $("#artTitle").val();
                    var data = {value:x,trigger:"artTitle"}
                    //console.log(data);
                    $.ajax({
                        type: "POST",
                        url: "insertThis2.php",
                        data: data,
                        success: function(response){
                            //console.log(response);
                            $("#artTitle").val(response);
                        }
                    }); 
                },100)
            }
        })
        
        
        $('#wholeArticle').highlightWithinTextarea({
            highlight: [
                {
                    highlight: '[sentence-end]',
                    className: 'vio-higlight'
                },
                {
                    highlight: "[article-title]",
                    className: "red-highlight"
                },
                {
                    highlight: "[article-intro]",
                    className: "yellow-highlight"
                },
                {
                    highlight: "[article-items]",
                    className: "green-highlight"
                },
                {
                    highlight: "[article-conclusion]",
                    className: "blue-highlight"
                },
                {
                    highlight: "[title]",
                    className: "orange-highlight"
                }
            
            ]
        });
    })
    
    function imports(){
        var article = $("#wholeArticle").val();
        var user = <?php echo $_SESSION['login_id']; ?>;
        var category = $("#addCategory").find("#category").val();
        
        if(article != ""){
            var data = {"importArticle": article, "user_id": user, "category": category };
            //console.log(data);
            $.ajax({
                type: "POST",
                url: "insertThis2.php",
                data: data,
                success: function(response){
                    //console.log(response);
                    const obj = JSON.parse(response);
                    if(obj.type == "false"){
                        alert(obj.message);
                    }else{
                        var action = confirm(obj.message);
                        if(action){
                            location.href='?page=createArticleNew';
                        }
                    }
                }
            }); 
        } else{
            alert("Article is empty!");
        }
        
        

    }
    
    function save(){
        
        var article = $("#wholeArticle").val();
        var title = article.match(/\[article-title\](.*?)\[article-title\]/i);
        //console.log(title[1])
        
        if(article != ""){
            if(title != null){
                download(title[1],article);
            } else{
                alert("Article title missing");
            }
            
        } else{
            alert("Article is empty!");
            
        }
    }
    
    function download(filename, text) {
      var element = document.createElement('a');
      element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
      element.setAttribute('download', filename);
    
      element.style.display = 'none';
      document.body.appendChild(element);
    
      element.click();
    
      document.body.removeChild(element);
    }
    
    function clearAll(){
        $("#artTitle").val("");
        $("#artIntro").val("");
        $("#artItems").val("");
        $("#artConclu").val("");
        $("#artTitleResult").val("");
        $("#artIntroResult").val("");
        $("#artItemsResult").val("");
        $("#artConcluResult").val("");
        $("#wholeArticle").val("");
        $("#wholeArticle").trigger("input");
        
    }
    
    function copyAll(){
        var val = document.getElementById("wholeArticle");
        val.select();
        val.setSelectionRange(0,99999);
        document.execCommand("copy");
        $("#copySign5").show().delay(2000).fadeOut(function(){
            $(this).hide();
        })
    }
    
    function combine(){
        
        var artTitle = $("#artTitle").val();
        var artIntro = $("#artIntro").val();
        var artItems = $("#artItems").val();
        var artConclu = $("#artConclu").val();
        
        var whole = artTitle + "\n\n" + artIntro + "\n\n" + artItems + "\n\n" + artConclu + "\n";
        
        $("#wholeArticle").val(whole);
        $("#wholeArticle").trigger("input");
        
        
    }
    
    function copyConcluR(){
        var val = document.getElementById("artConcluResult");
        val.select();
        val.setSelectionRange(0,99999);
        document.execCommand("copy");
        $("#copySign4").show().delay(2000).fadeOut(function(){
            $(this).hide();
        })
    }
    
    function copyItemsR(){
        var val = document.getElementById("artItemsResult");
        val.select();
        val.setSelectionRange(0,99999);
        document.execCommand("copy");
        $("#copySign3").show().delay(2000).fadeOut(function(){
            $(this).hide();
        })
    }
    
    function copyAIR(){
        var val = document.getElementById("artIntroResult");
        val.select();
        val.setSelectionRange(0,99999);
        document.execCommand("copy");
        $("#copySign2").show().delay(2000).fadeOut(function(){
            $(this).hide();
        })
        
    }
    
    function copyATR(){
        var val = document.getElementById("artTitleResult");
        val.select();
        val.setSelectionRange(0,99999);
        document.execCommand("copy");
        $("#copySign1").show().delay(2000).fadeOut(function(){
            $(this).hide();
        })
    }
    
    function pasteArtConclu(){
        setTimeout(function(){
            var x = $("#artConclu").val();
            var data = {value:x,trigger:"artConclu"}
            console.log(data);
            $.ajax({
                type: "POST",
                url: "insertThis2.php",
                data: data,
                success: function(response){
                    //console.log(response);
                    $("#artConclu").val(response);
                }
            }); 
        },100)
    }
    
    function pasteArtItems(){
        setTimeout(function(){
            var x = $("#artItems").val();
            var data = {value:x,trigger:"artItems"}
            console.log(data);
            $.ajax({
                type: "POST",
                url: "insertThis2.php",
                data: data,
                success: function(response){
                    //console.log(response);
                    $("#artItems").val(response);
                }
            }); 
        },100)
        
    }
    
    function pasteArtIntro(){
        setTimeout(function(){
            var x = $("#artIntro").val();
            var data = {value:x,trigger:"artIntro"}
            console.log(data);
            $.ajax({
                type: "POST",
                url: "insertThis2.php",
                data: data,
                success: function(response){
                    //console.log(response);
                    $("#artIntro").val(response);
                }
            }); 
        },100)
    }
    
    
    function pasteArtTitle(){
        setTimeout(function(){
            var x = $("#artTitle").val();
            var data = {value:x,trigger:"artTitle"}
            //console.log(data);
            $.ajax({
                type: "POST",
                url: "insertThis2.php",
                data: data,
                success: function(response){
                    //console.log(response);
                    $("#artTitle").val(response);
                }
            }); 
        },100)
    }
</script>


<?php } endif; ?>



<?php if(isset($_GET['tool'])): if($_GET['tool'] == 0){ ?>
<script>
    $(document).ready(function(){
        $("#copySign2").hide();
        $("#copySign1").hide();
        
        $("#artItemTitle").on("keyup", function(e){
            if(e.keyCode === 13){
                setTimeout(function(){
                    var x = $("#artItemTitle").val();
                    var data = {value:x,trigger:"this3"}
                    //console.log(data);
                    $.ajax({
                        type: "POST",
                        url: "insertThis2.php",
                        data: data,
                        success: function(response){
                            //console.log(response);
                            $("#artItemTitle").val(response);
                        }
                    }); 
                },100)
            }
        })
        
    })

    function formatParagraph(x){
        var data = {value:x,trigger:"this2"}
        $.ajax({
            type: "POST",
            url: "insertThis2.php",
            data: data,
            success: function(response){
                //console.log(response);
                
            }
        }); 
    }
    
    function placeTitleTag(){
        setTimeout(function(){
            var x = $("#artItemTitle").val();
            var data = {value:x,trigger:"this3"}
            //console.log(data);
            $.ajax({
                type: "POST",
                url: "insertThis2.php",
                data: data,
                success: function(response){
                    //console.log(response);
                    $("#artItemTitle").val(response);
                }
            }); 
            
        },100)
        
    }
</script>
<?php } endif; ?>
<script>
    function switchTool(d){
            location.href = "?page=text-tool&tool="+d;
        }
  
</script>