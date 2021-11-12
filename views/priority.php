<?php 
// check if user is allowed
if(!$iccaFunc->isAllowed($_SESSION['login_id'], "rewriteArticle")){
    echo "<script> alert('User not allowed'); location.href='index.php' </script>";
}

if(isset($_GET['articleId'])){
    $noApproveRewrite = $icca_new_obj->getSentenceWithoutApproveRewrite($_GET['articleId']);
    //print_r($noApproveRewrite);
}



?>
<section class="content">
     <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">
                                <b>Article</b> - 
                                <?php 
                                    $article = $icca_new_obj->getArticleById($_GET['articleId']);
                                    echo trim($article['title']);
                                ?>
                            </h4>
                            <div class="box-tools pull-right">
                                <a href='?page=article-view&aid=<?= $_GET['articleId'] ?>' class='btn btn-danger btn-xs' target='_blank'><i class='fa fa-eye'></i>&nbsp;View article</a>
                                <a href="?page=rewriteArticleNew" class="btn btn-xs btn-primary">Go back</a>
                                
                                <!--<button class="btn btn-box-tool" type="button" data-widget="collapse">-->
                                <!--<i class="fa fa-plus"></i>-->
                                <!--</button>-->
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box-body table-responsive">
                                <table id="articleIntroduction" class="table table-bordered table-striped table-hover">
                                    <thead style="background:#2b415e; color:#fff;">
                                        <tr>
                                            <th class="mwidth-250">Sentence</th>
                                            <th class="mwidth-180">Type and Title</th>
                                            <th class="mwidth-180">Paragraph and Order</th>
                                            <th class="mwidth-180">Action</th>
                                        </tr>
                                    </thead>  
                                    <tbody>
                                        <?php 
                                            foreach($noApproveRewrite as $item): 
                                                $sentence = $icca_new_obj->getSentenceById($item); 
                                                if(htmlentities($sentence['sentence'])== "") 
                                                    $sent = $sentence['sentence']; 
                                                else 
                                                    $sent = htmlentities($sentence['sentence']); 
                                                    
                                                $sent1 = htmlspecialchars(json_encode($icca_new_obj->convert_smart_quotes($icca_new_obj->htmlallentities($sentence['sentence']))));
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $sent; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $content = $icca_new_obj->getContentById($sentence['content_id']);
                                                    
                                                    if($content['title'] && $content['title'] != 'none' && $content['title'] != '' && strpos($content['title'], '[article-') === false) {
                                                        $content_title = $content['title'];
                                                    } else {
                                                        $content_title = 'none provided';
                                                    }
                                                    
                                                    echo "<b>".ucfirst($content['type'])."</b> - ".trim($icca_new_obj->htmlallentities($content_title));
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    echo $icca_new_obj->addOrdinalNumberSuffix($sentence['paragraph_no'])." paragraph, ";
                                                    echo $icca_new_obj->addOrdinalNumberSuffix($sentence['order_no'])." sentence";
                                                ?>
                                            </td>
                                            <td>
                                                <center>
                                                    <a href="javascript:showModal(<?= $sentence['id'] ?>, <?= $sent1 ?>, <?= $sentence['paragraph_no'] ?>, <?= $sentence['order_no'] ?>)">
                                                        <button id="rewrite_sent_<?= $sentence['id'] ?>" class="btn btn-success btn-xs">
                                                            <i class="fa fa-magic"></i>&nbsp;&nbsp;Rewrite
                                                        </button>
                                                    </a>
                                                    <!--<button class="btn btn-success btn-xs" value='<?php echo $sentence['id'].":::::".$sent1.":::::".$sentence['paragraph_no'].":::::".$sentence['order_no']; ?>' -->
                                                    <!--    data-toggle="modal" data-target="#prioritySentence" onclick='showSentence(this.value)'>-->
                                                    <!--    <i class="fa fa-magic"></i>&nbsp;Rewrite-->
                                                    <!--</button>-->
                                                </center>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <th class="mwidth-250">Sentence</th>
                                        <th class="mwidth-180">Type and Title</th>
                                        <th class="mwidth-180">Paragraph and Order</th>
                                        <th class="mwidth-180">Action</th>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- Modal -->
    <div class="modal fade" id="prioritySentence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Rewrite Sentence</h4>
                </div>
                <div class="modal-body">
                        <div class="">
                            <div class="box-body">
                                <form method="post" id="form1">
                                    <p class="text-muted" id="sentenceWordCharCount"></p>
                                    <p id='tobeEditedSentence' style='font-size: 18px; background-color:#337ab7; border-radius: 5px; padding: 5px; color:white;'></p>
                                    <label>Rewrite Sentence</label>
                                    <input type='hidden' name='orig_sentence'/>
                                    <input type='hidden' name='sentenceId' />
                                    <input type='hidden' name='sentenceOrderNo'/>
                                    <input type='hidden' name='sentenceParNo'/>
                                    <input type='hidden' id='wordOrigCount'/>
                                    
                                    <div id='error' style='display:none; color:red;'></div>
                                    <p class="text-muted" id="textareasentenceWordCharCount"></p>
                                    <textarea name='sentence' oninput='CheckInputText(this.value)' rows='8' class='form-control' required autofocus></textarea>
                                    <p class="text-muted" style="color:green;" id="showWordCharCountRealTime"/></p>
                            </div>
                            <div class="box-footer" style="border:none">
                                <div class="form-group">
                                    <input type="hidden" name="article_id" value="<?php echo $_GET['articleId']; ?>">
                                    <input type="hidden" name="editor" value="<?php echo $_SESSION['login_id']; ?>">
                                    <input class="btn btn-success" type="submit" id='saveSentence' name="saveSentence" value="save">
                                </form> 
                                    <button class="btn btn-info btn-small" id="wordAI"><img id="loadingSpin" src="images/loading.gif" height="20" width="26" style="display:none;" ><span id="spinText">Spin</span></button>
                                    <button onclick="perfectTense()" class="btn btn-warning btn-small" id="perfectTense"><img id="loadingPerfectTense" src="images/loading.gif" height="20" width="26" style="display:none;"><span id="ptText">PerfectTense</span></button>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
$(document).ready(function(){
    $("#priorityTable").DataTable();
    
    $('#prioritySentence').on('shown.bs.modal', function () {
        $('textarea[name=sentence]').trigger('focus');
    })
    
    $("#form1").submit(function(e){
    
        var data = $(this).serializeArray();
        console.log(data);
        if(data.length == 7) {
            var sentenceNotDuplicate = true;
            if(data[0].value.trim().toLowerCase() == (data[4].value.trim().toLowerCase())){
                sentenceNotDuplicate = false;
                document.getElementById("error").innerHTML = "Original sentence and Rewritten sentence is the same!";
                document.getElementById("error").style.display="block";
            }
            if(sentenceNotDuplicate) {
                $.ajax({
                    type: "POST",
                    url: "insertThis2.php",
                    data: data,
                    success: function(response){
                        if(response){
                            alert("Rewrite successfully inserted!");
                            $("#prioritySentence").modal('hide');
                        } else{
                            alert("Something went wrong. Try again later.");
                            $("#prioritySentence").modal('hide');
                        }
                    }
                })  
            } 
        }
        return false;
    });
    
    
    $("#wordAI").click(function(){
            var sentence = $("textarea[name=sentence]").val();
            sentence.trim();

            if(sentence != ""){
                var data = {wordAI:"ready", sentence: sentence, user_id: <?php echo $_SESSION['login_id']; ?> }
                //console.log(data);
                
                $.ajax({
                    type: "POST",
                    url: "getThis.php",
                    data: data,
                    beforeSend: function(){
                        document.getElementById("loadingSpin").style.display="block";
                        document.getElementById("spinText").innerText = "";
                        document.getElementById("wordAI").disabled = true;
                        
                    },
                    complete: function(){
                        document.getElementById("spinText").innerText = "Spin";
                        document.getElementById("loadingSpin").style.display="none";
                        document.getElementById("wordAI").disabled = false;
                    },
                    success: function(response){
                        //console.log(response);
                        const obj = JSON.parse(response);
                        //console.log(obj);
                        $("textarea[name=sentence]").val(obj.text);
                    }
                    
                })  
                    
            }
        });
    
});

function parseHtmlEntities(str) {
    return str.replace(/&#([0-9]{1,4});/gi, function(match, numStr) {
        var num = parseInt(numStr, 10); // read num as normal number
        return String.fromCharCode(num);
    });
}

function showModal(sentence_id, sentence, parNo, orderNo) {
    var sentence = parseHtmlEntities(sentence);
    
    document.getElementById("tobeEditedSentence").innerHTML = sentence;
    document.getElementById("sentenceWordCharCount").innerHTML = "Words: <strong>"+countWords(sentence)+ "</strong> Characters: <strong>" + sentence.length + "</strong>";
    $("input[name=orig_sentence]").val(sentence);
    $("input[name=sentenceId]").val(sentence_id);
    $("input[name=sentenceOrderNo]").val(orderNo);
    $("input[name=sentenceParNo]").val(parNo);
    $("#wordOrigCount").val(countWords(sentence));
    $("textarea[name=sentence]").val(sentence);
    
    $('#prioritySentence').modal('show');
}

function showSentence(value){
    var param = value.split(":::::");
    var sentence_id = param[0];
    var sentence = parseHtmlEntities(param[1]);
    var parNo = param[2];
    var orderNo = param[3];
    document.getElementById("tobeEditedSentence").innerHTML = sentence;
    document.getElementById("sentenceWordCharCount").innerHTML = "Words: <strong>"+countWords(sentence)+ "</strong> Characters: <strong>" + sentence.length + "</strong>";
    $("input[name=orig_sentence]").val(sentence);
    $("input[name=sentenceId]").val(sentence_id);
    $("input[name=sentenceOrderNo]").val(orderNo);
    $("input[name=sentenceParNo]").val(parNo);
    $("#wordOrigCount").val(countWords(sentence));
    $("textarea[name=sentence]").val(param[1]);
    
}

function countWords(s){ s = s.replace(/(^\s*)|(\s*$)/gi,""); s = s.replace(/[ ]{2,}/gi," "); s = s.replace(/\n /,"\n"); return s.split(' ').length;}

function CheckInputText(x){ 
  
    document.getElementById("error").style.display="none";
    var f = $("#wordOrigCount").val();
    wordLim = f*.7;
    if(countWords(x)<wordLim){ 
        document.getElementById("wordAI").disabled = true;
        document.getElementById("saveSentence").disabled = true;
        document.getElementById("textareasentenceWordCharCount").innerHTML = "Words: <strong style='color: red'>"+countWords(x)+ "</strong> Characters: <strong>" + x.length + "</strong> (<strong style='color: red'> Word count must be at least 70% of original sentence</strong> )"; 
    }else{
        document.getElementById("wordAI").disabled = false;
        document.getElementById("textareasentenceWordCharCount").innerHTML = "Words: <strong>"+countWords(x)+ "</strong> Characters: <strong>" + x.length + "</strong>"; 
        document.getElementById("saveSentence").disabled = false;
    }
}


function perfectTense(){
        var sentence = $("textarea[name=sentence]").val();
        if(sentence != ""){
            var data = {"text":sentence, "perfectTense":"set"}
            $.ajax({
                type: "POST",
                url: "insertThis2.php",
                data: data,
                beforeSend: function(){
                    document.getElementById("loadingPerfectTense").style.display="block";
                    document.getElementById("ptText").innerText = "";
                    document.getElementById("perfectTense").disabled = true;
                    
                },
                complete: function(){
                    document.getElementById("ptText").innerText = "PerfectTense";
                    document.getElementById("loadingPerfectTense").style.display="none";
                    document.getElementById("perfectTense").disabled = false;
                },
                success: function(response){
                    //console.log(response);
                    const obj = JSON.parse(response);
                    //console.log(obj);
                    $("textarea[name=sentence]").val(obj.corrected);
                }
            })
        } 
    }


    

</script>
