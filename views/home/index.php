<style>
    .content-wrapper {
        background:linear-gradient(rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.4)),url('../../acp.jpeg') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
    
    .content-header {
        padding:15px;
    }
    
    .skin-blue .content-header {
        background:#fff;
    }
</style>

<?php
    //fetching of HR users
    $users = $icca_new_obj->getAllUsers();
    $realUsers = [];
    $uCount = 0;
    foreach($users as $user) {
        if($user['status'] == 1 && $icca_new_obj->canAccessPortal($user['id'])) {
            $uCount++;
            $realUsers[] = $user;
        }
    }
?>

<section class="content">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box info-blue">
            <span class="info-box-icon bg-blue"><i class="fas fa-pencil-alt"></i></span>
            <div class="info-box-content">
              <a href="index.php?page=rewritten-sentences"><span class="info-box-text">All Rewrites</span></a>
              <span id="rewrites_all_count" class="info-box-number counting-animation">-</span>
            </div>
          </div>
        </div>
        
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box info-yellow">
            <span class="info-box-icon bg-yellow"><i class="fa fa-hourglass-start"></i></span>
            <div class="info-box-content">
              <a href="index.php?page=rewritten-sentences&status=0"><span class="info-box-text">Pending</span></a>
              <span id="rewrites_pending_count" class="info-box-number counting-animation">-</span>
            </div>
          </div>
        </div>
        
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box info-green">
            <span class="info-box-icon bg-green"><i class="fa fa-clipboard-check"></i></span>
            <div class="info-box-content">
              <a href="index.php?page=rewritten-sentences&status=1"><span class="info-box-text">Approved</span></a>
              <span id="rewrites_approved_count" class="info-box-number counting-animation">-</span>
            </div>
          </div>
        </div>
        
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box info-red">
            <span class="info-box-icon bg-red"><i class="fas fa-times-circle"></i></span>
            <div class="info-box-content">
              <a href="index.php?page=rewritten-sentences&status=2"><span class="info-box-text">Rejected</span></a>
              <span id="rewrites_rejected_count" class="info-box-number counting-animation">-</span>
            </div>
          </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box info-blue">
            <span class="info-box-icon bg-blue"><i class="fas fa-newspaper"></i></span>
            <div class="info-box-content">
              <a href="index.php?page=createArticleNew"><span class="info-box-text">All Articles</span></a>
              <span id="articles_all_count" class="info-box-number a-counting-animation">-</span>
            </div>
          </div>
        </div>
        
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box info-yellow">
            <span class="info-box-icon bg-yellow"><i class="fas fa-newspaper"></i></span>
            <div class="info-box-content">
              <a href="index.php?page=createArticleNew&export=0"><span class="info-box-text">Pending</span></a>
              <span id="articles_pending_count" class="info-box-number a-counting-animation">-</span>
            </div>
          </div>
        </div>
        
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box info-green">
            <span class="info-box-icon bg-green"><i class="fas fa-newspaper"></i></span>
            <div class="info-box-content">
              <a href="index.php?page=createArticleNew&export=1"><span class="info-box-text">Completed</span></a>
              <span id="articles_completed_count" class="info-box-number a-counting-animation">-</span>
            </div>
          </div>
        </div>
    </div>
    
    <div class="row">
        <!--
        <div class="col-md-12">
            <div class="box">
                
                <div class="box-header">
                    <h4 class="box-title"><strong>IMPORTANT! Please read.</strong></h4>
                </div>
                <div class="box-body">
                    
                        <div class="col-md-6">
                        <ul>
                            <li>
                                <strong>Adding Paragraphs</strong>
                                <pre>Steps
1. Paste the Paragraph to the Format Paragraph Textarea (1 paragraph at a time)
2. It will immediately return the new format.
3. Copy the format and paste it to the paragraph textarea
4. Check if delimeter is placed in each end of sentence
5. Remove any delimeter that is not placed in the end of sentence.
6. Repeat if you have multiple paragraphs.
7. Save

------------------------------------------------------
How it works
Delimeter is: [sentence-end]
1. In the reformatted paragraph if you see a delimeter placed in an area where it is not end of sentence then REMOVE IT.

2. If you don't see a delimeter in the area where it's end of sentence apply the [sentence-end] delimeter. (take a screenshot of it and send/notify the developer with the screenshot).

3. Delimeter [sentence-end] is how the application identify if it's a new sentence.

Note:
1. The delimeter is very important. It is how the application identify if it's a new sentence. 

2. The delimeter is ignored in the Word count and Character count.

3. This will resolve the issue where sentence1 supposed to be part of sentence2 and another scenerio where sentence1 should not be part of sentence2.</pre>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="col-md-6">
                        <ul>
                            <li>
                                <strong>ARTICLE STATUS</strong>
                                <ul>
                                    <li>
                                        Button Save as Draft / Ready is now available
                                        <ul>
                                            <li>The only person who can see this button is the creator of the article.</li>
                                        </ul>
                                    </li>
                                    <li>Status of article is now displayed in the table</li> 
                                    
                                </ul>
                            </li>
                            <p>Note:</p>
                            <p>The default status of article is NOT READY.</p>
                        </ul>
                        
                        <ul>
                            <li>
                                Rewrite
                                <ul>
                                    <li>Articles that are ready for rewrite will be displayed on the table.</li>
                                    <li>Articles that are not ready will not be displayed.</li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                </div>
                
            </div>
        </div>-->
    </div> 
    
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">Rewrite Statistics</h3>
                </div>
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-responsive">
                                <canvas id="pieChart" height="155" width="205" style="width: 205px; height: 155px;"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ul class="chart-legend clearfix">
                                <li><i class="fa fa-circle text-yellow"></i>&nbsp;&nbsp;Pending</li>
                                <li><i class="fa fa-circle text-green"></i>&nbsp;&nbsp;Approved</li>
                                <li><i class="fa fa-circle text-red"></i>&nbsp;&nbsp;Rejected</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="box-footer no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="index.php?page=rewritten-sentences&status=0">Pending Rewrites <span id="per_pending" class="pull-right text-yellow">-</span></a></li>
                        <li><a href="index.php?page=rewritten-sentences&status=1">Approved Rewrites <span id="per_approved" class="pull-right text-green">-</span></a></li>
                        <li><a href="index.php?page=rewritten-sentences&status=2">Rejected Rewrites <span id="per_rejected" class="pull-right text-red">-</span></a></li>
                    </ul>
                </div>
          </div>
        </div>
        
        <div class="col-md-6">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Users</h3>
                    <div class="box-tools pull-right"><span class="label label-danger"><?= $uCount; ?> Users</span></div>
                </div>
                <div class="box-body">
                    <ul class="users-list clearfix">
                        <?php foreach($realUsers as $uk => $v) { 
                            if($v['status'] == 1) { 
                        ?>  
                            <li>
                                <img src="<?= 'https://hr.backoffice-operations.com/'.$v['image']; ?>" style="max-width:30%;width:34px;height:34px" alt="User Image">
                                <a class="users-list-name" href="#"><?= $v['fname']; ?></a>
                                <span class="users-list-date" id="time-<?php echo $v['id']; ?>">
                                    <?php 
                                        $status = null;
                                        if(trim($app->time_elapsed_string($v['last_session'])) == "Online") {
                                            $status = 'images/online.png';
                                        } else {
                                            $status = 'images/offline.png';
                                        }
                                    ?>
                                    <img src="<?= $status ?>" style="margin-top: -2px;width: 10px;">
                                    &nbsp;<?= $app->time_elapsed_string($v['last_session']); ?>
                                </span>
                            </li>
                        <?php }} ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    var pieOptions = {
        segmentShowStroke: true,
        segmentStrokeColor: "#fff",
        segmentStrokeWidth: 1,
        animationSteps: 100,
        animationEasing: "easeOutBounce",
        animateRotate: true,
        animateScale: false,
        responsive: true,
        maintainAspectRatio: false,
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>; z-index: 300;\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
        tooltipTemplate: "<%=value %> <%=label%>"
    };

    jQuery.ajax({
        url :"./ajax/rewrite-status-counts.php",
        type: "GET", 
        success: function(data) {
            const data1 = JSON.parse(data);
            
            //pie chart
            var PieData = [
                {
                  value: data1['pending'],
                  color: "#f39c12",
                  highlight: "#f39c12",
                  label: "Pending"
                },
                {
                  value: data1['approved'],
                  color: "#00a65a",
                  highlight: "#00a65a",
                  label: "Approved"
                },
                {
                  value: data1['rejected'],
                  color: "#dd4b39",
                  highlight: "#dd4b39",
                  label: "Rejected"
                }
            ];
            pieChart.Doughnut(PieData, pieOptions);
            
            //rewrite stats
            $('#rewrites_all_count').html(data1['allCount']);
            $('#rewrites_pending_count').html(data1['pending']);
            $('#rewrites_approved_count').html(data1['approved']);
            $('#rewrites_rejected_count').html(data1['rejected']);
            $('#per_pending').html(data1['perPending']+'%');
            $('#per_approved').html(data1['perApproved']+'%');
            $('#per_rejected').html(data1['perRejected']+'%');
            
            //counting animation
            $('.counting-animation').each(function () {
                var $this = $(this);
                jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
                    duration: 1000,
                    easing: 'swing',
                    step: function () {
                        $this.text(Math.ceil(this.Counter));
                    }
                });
            });
        },
        error: function(xhr, status, error){  
            console.log(xhr)
            console.log(xhr.responseText, status, error);
            alert("Error occurred when fetching rewrite status counts. Please try again later.");
        }
    });
    
    jQuery.ajax({
        url :"./ajax/article-export-status-counts.php",
        type: "GET", 
        success: function(data) {
            const data1 = JSON.parse(data);
            
            //article stats
            $('#articles_all_count').html(data1['articlesCount']);
            $('#articles_pending_count').html(data1['articles_pending']);
            $('#articles_completed_count').html(data1['articles_completed']);
            
            //counting animation
            $('.a-counting-animation').each(function () {
                var $this = $(this);
                jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
                    duration: 1000,
                    easing: 'swing',
                    step: function () {
                        $this.text(Math.ceil(this.Counter));
                    }
                });
            });
        },
        error: function(xhr, status, error){  
            console.log(xhr)
            console.log(xhr.responseText, status, error);
            alert("Error occurred when fetching article export status counts. Please try again later.");
        }
    });
    

</script>
