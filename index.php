<?php
//exit();
    session_start();
    error_reporting(E_ALL); ini_set('display_errors', 1);
    
    include 'core/iccaFunctions2.php';
    $icca_obj = new iccaFunc2();
    
    include 'core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    include 'core/iccaFunctionsNew.php';
    $iccaFunc = new iccaFunc();
    
    date_default_timezone_set('Asia/Manila');
    
    if(isset($_GET['logout'])){
        session_destroy();
        echo "<script>window.location = 'index.php?page=login'; </script>";
    }
        
    header('Content-Type: text/html; charset=ISO-8859-1');
    include 'core/core.php'; 
    $app = new mckirby();
    
    $page = (isset($_GET['page'])) ? $_GET['page'] : "home";
    if(!isset($_SESSION['login_id'])) {
        if($page!="login") {
            echo "<script>location.href='index.php?page=login';</script>";
        }
    } else {
        //check if user has granted access to portal
        if(!$icca_new_obj->canAccessPortal($_SESSION['login_id'])) {
            session_destroy();
            echo "<script>window.location = 'index.php?page=login'; </script>";
        }
        
        $_SESSION['acl'] = $app->ACLfeaturesL($_SESSION['login_id']);
        $useri['photo'] = $_SESSION['login_photo'];
        $useri['username'] = $_SESSION['login_user'];
    }
    
    $cloudfront_switch = true;
    if($cloudfront_switch){
        $cld = "http://d5cypwv7975xc.cloudfront.net/";
    } else{
        $cld = "";
    }
?>

<!DOCTYPE html>
<html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
    
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> Internal Content Creation App | Authoritative Content LLC</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?php echo $cld; ?>bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/fontawesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
        <!--<link rel="stylesheet" href="libs/font-awesome-4.6.3/css/font-awesome.min.css">-->
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo $cld; ?>libs/ionicons-2.0.1/css/ionicons.min.css">
        <!-- jvectormap -->
        <link rel="stylesheet" href="<?php echo $cld; ?>plugins/jvectormap/jquery-jvectormap-1.2.2.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo $cld; ?>plugins/datepicker/datepicker3.css">
        <link rel="stylesheet" href="<?php echo $cld; ?>plugins/daterangepicker/daterangepicker.css">
        <link rel="stylesheet" href="<?php echo $cld; ?>plugins/select2/select2.min.css">
        <link rel="stylesheet" href="<?php echo $cld; ?>plugins/iCheck/all.css">
        <link rel="stylesheet" href="<?php echo $cld; ?>plugins/colorpicker/bootstrap-colorpicker.min.css">
        <link rel="stylesheet" href="<?php echo $cld; ?>plugins/timepicker/bootstrap-timepicker.min.css">
        <link rel="stylesheet" href="<?php echo $cld; ?>plugins/datatables/dataTables.bootstrap.css">
        
        <link rel="stylesheet" href="<?php echo $cld; ?>dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="<?php echo $cld; ?>dist/css/skins/_all-skins.min.css">
        <link rel="stylesheet" href="<?php echo $cld; ?>libs/css/style.css">
        <!--<link rel="stylesheet" href="<?php echo $cld; ?>libs/css/bootstrap3-wysihtml5.min.css"> -->
        <link rel="stylesheet" href="<?php echo $cld; ?>libs/css/jquery.highlight-within-textarea.css">
        
        <style>
        .no-side-bar {
            margin-left: 0px;
        }
        .full button span {
            background-color: limegreen;
            border-radius: 32px;
            color: black;
        }
        .partially button span {
            background-color: orange;
            border-radius: 32px;
            color: black;
        }
        .skin-blue .main-header .navbar {
          background-color: #4dc7bc;
          background-color: #4dc4d1;
        }
        .skin-blue .main-header .logo {
          background-color: #22b7aa;
          background-color: #48b4c0;
          color: #fff;
          border-bottom: 0 solid transparent;
        }
        .skin-blue .main-header .logo:hover {
          background-color: #7df0e6;
          background-color: #50cedc;
          color: #222d32;
        }
        .skin-blue .main-header .navbar .sidebar-toggle:hover {
          color: #fff;
          background-color: #45b3a9;
          background-color: #42aab5;
        }
        .e-callout{
            border-radius: 3px;
            margin: 0 0 10px 0;
            padding: 5px 50px 5px 5px;
            text-align: center;
        }
        .skin-blue .main-header li.user-header {
            background-color: #0c9a59;
        }
        
        .main-header {
            position: fixed;
            max-height: 100px;
            z-index: 1030;
            left: 0;
            top: 0;
            right: 0;
        }
        
        .input-sm {
            height: 25px;
        }
        .profile-user-img{
            max-height: 100px;
        }
        .content {
            min-height: 650px;
            background:#eee;
        }
        .user-panel>.image>img {
            max-width: 45px;
            max-height: 45px;
        }
        
        .info-box-number {
            display: block;
            font-weight: bold;
            font-size: 14px;
        }
        
        .content-wrapper {
            background:#eee!important;
        }
        
        .content-header {
            padding:15px;
            box-shadow: 0 1px 4px 0 rgba(0,0,0,.14);
        }
    
        .main-footer {
            background:#222222;
            color:#fff;
            border-top: 2px solid #d2d6de;
        }
        
        .skin-blue .sidebar-menu>li.active>a {
            background-color:#f36b41;
            border-left:transparent;
        }
         
        .skin-blue .sidebar-menu>li:hover>a {
            border-left:transparent;
        }
        
        .mwidth-10 {
            min-width: 10px !important;
        }
        
        .mwidth-80 {
            min-width: 80px !important;
        }
        
        .mwidth-100 {
            min-width: 100px !important;
        }
        
        .mwidth-120 {
            min-width: 120px !important;
        }
        
        .mwidth-130 {
            min-width: 130px !important;
        }
        
        .mwidth-180 {
            min-width: 180px !important;
        }
        
        .mwidth-200 {
            min-width: 200px !important;
        }
        
        .mwidth-250 {
            min-width: 250px !important;
        }
        
        .mtop-6 {
            margin-top: 6px !important;
        }
        
        .mb-0 {
            margin-bottom: 0 !important;
        }
        
        .center {
            text-align: center;
        }
        
        body {
            font-size: 16px;
        }
    </style>
 
        <script src="<?php echo $cld; ?>libs/js/sha256.js" defer></script>
        <script src="<?php echo $cld; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="<?php echo $cld; ?>bootstrap/js/bootstrap.min.js" ></script>
        <!-- Select2 -->
        <script src="<?php echo $cld; ?>plugins/select2/select2.full.min.js" defer></script>
        <script src="<?php echo $cld; ?>plugins/momentjs/moment.min.js" defer></script>
        <!-- FastClick -->
        <script src="<?php echo $cld; ?>plugins/fastclick/fastclick.min.js" defer></script>
        <!-- AdminLTE App -->
        <script src="<?php echo $cld; ?>dist/js/app.min.js" defer></script>
        <!-- Sparkline -->
        <script src="<?php echo $cld; ?>plugins/sparkline/jquery.sparkline.min.js" defer></script>
        <!-- jvectormap -->
        <script src="<?php echo $cld; ?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" defer></script>
        <script src="<?php echo $cld; ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js" defer></script>
        <!-- SlimScroll 1.3.0 -->
        <script src="<?php echo $cld; ?>plugins/slimScroll/jquery.slimscroll.min.js" defer></script>
        <!-- ChartJS 1.0.1 -->
        <script src="<?php echo $cld; ?>plugins/chartjs/Chart.min.js"></script>
        <script src="<?php echo $cld; ?>plugins/select2/select2.full.min.js" defer></script>
        <script src="<?php echo $cld; ?>plugins/input-mask/jquery.inputmask-min.js" defer></script>
        <script src="<?php echo $cld; ?>plugins/input-mask/jquery.inputmask.date.extensions-min.js" defer></script>
        <script src="<?php echo $cld; ?>plugins/input-mask/jquery.inputmask.extensions-min.js" defer></script>  
        <script src="<?php echo $cld; ?>plugins/slimScroll/jquery.slimscroll.min.js" defer></script>
        <script src="<?php echo $cld; ?>plugins/iCheck/icheck.min.js" defer></script>
        <!-- DataTables -->
        <script src="<?php echo $cld; ?>plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo $cld; ?>plugins/datatables/dataTables.bootstrap.min.js" defer></script>
        <!-- FastClick -->
        <script src="<?php echo $cld; ?>plugins/fastclick/fastclick.js" defer></script>
        <script src="<?php echo $cld; ?>plugins/datepicker/bootstrap-datepicker-min.js" defer></script>
        <script src="<?php echo $cld; ?>plugins/colorpicker/bootstrap-colorpicker.min.js" defer></script>
        <script src="<?php echo $cld; ?>plugins/daterangepicker/moment-mins.js" defer></script>
        <script src="<?php echo $cld; ?>plugins/daterangepicker/daterangepicker-min.js" defer></script>
        <script src="<?php echo $cld; ?>plugins/colorpicker/bootstrap-colorpicker.min.js" defer></script>
        <script src="<?php echo $cld; ?>plugins/timepicker/bootstrap-timepicker.min.js" defer></script>
        <!-- XML TO JSON -->
        <!--<script src="libs/js/xmltojson.js"></script>-->
        <!--<script src="<?php echo $cld; ?>libs/js/bootstrap3-wysihtml5.all.min.js" defer></script>-->
        <script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js" defer></script>
        <script src="<?php echo $cld; ?>libs/js/he.js" defer></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="<?php echo $cld; ?>plugins/slimScroll/jquery.slimscroll.min.js" defer></script>
        <script src="<?php echo $cld; ?>dist/js/demo.js" defer></script>
        <!-- calculating costs (create, update, view HITs) -->
        <!--<script src="libs/js/mturk-cost-calculations.js" defer></script> -->
        <script src="<?php echo $cld; ?>libs/js/jquery.highlight-within-textarea.js" defer></script>
        <script>
            function makeUser() {
                var text = "";
                var possible = "123456789";
                
                for (var i = 0; i < 4; i++)
                  text += possible.charAt(Math.floor(Math.random() * possible.length));
                
                return text;
            }
            
            function makeRandom() {
                var text = "";
                var possible = "ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789!&#@+";
                
                for (var i = 0; i < 8; i++)
                  text += possible.charAt(Math.floor(Math.random() * possible.length));
                
                return text;
            }
      
            function makeCode() {
                var text = "";
                var possible = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
                
                for (var i = 0; i < 4; i++)
                  text += possible.charAt(Math.floor(Math.random() * possible.length));
                
                return text;
            }
        </script>
    </head>

    <body class="hold-transition skin-blue sidebar-mini fixed">
        <div class="wrapper">
            <?php $page = (isset($_GET['page'])) ? $_GET['page'] : "home"; ?>
            <?php if($page!="login"): require_once('header.php'); endif; ?>
            <?php if($page!="login"): require_once('sidebar-left.php'); endif; ?>
            <?php 
                switch ($page) {
                    case 'login':
                        include 'views/login/login.php';
                    break;
                    default:
                        include('body.php');
                    break;
                }
            ?>
        </div>
    
        <?php if($page!="login"): require_once('footer.php'); endif; ?>

        <script>
            $(function () {
                $("#example1").DataTable();
                $("#example2").DataTable();
                $("#example3").DataTable();
                $("#articleList").DataTable();
                
                var table1 = $("#articleList").DataTable();
                function changeSearch(){
                    table1.search("").draw();
              
                 }
            });
        
            function submitSession(){ 
                $.ajax({
                    url: "updateSession.php",
                    type: "post",
                    data: {"isd": <?php echo $_SESSION['login_id']; ?>},
                    success: function (response) {}
                });
            }
        
            <?php if($page=="homez"): ?>
                $.ajax({
                    url: "checkElapse.php",
                    type: "post",
                    success: function (response) {
                        var parsed = JSON.parse(response);
                        
                        var arr = [];
                        for(var x in parsed) {
                            if(parsed[x]['elapse']=="Online") {
                                $("#"+parsed[x]['form']).html('<img src="images/online.png" style="margin-top: -2px;width: 10px;">&nbsp;'+parsed[x]['elapse']);
                            } else {
                                $("#"+parsed[x]['form']).html('<img src="images/offline.png" style="margin-top: -2px;width: 10px;">&nbsp;'+parsed[x]['elapse']);
                            }
                        }            
                    }
                });
            <?php endif; ?>
        
            submitSession();  
            setInterval(function(){ 
                submitSession();  
            }, 10000);
        </script>
        <script>
            var products = [];
            
            function PrintElem(elem, title) {
                var mywindow = window.open('', 'PRINT', 'height=400,width=600');
                mywindow.document.write('<html><head><title>' + document.title  + '</title>');
                
                mywindow.document.write('</head><body >');
                mywindow.document.write('<h3>' + title  + '</h3>');
                mywindow.document.write(document.getElementById(elem).innerHTML);
                mywindow.document.write('</body></html>');
                
                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10*/
                
                mywindow.print();
                mywindow.close();
    
                return true;
            }
            
            $(function () {
                //Initialize Select2 Elements
                $(".select2").select2();
                
                //Datemask dd/mm/yyyy
                $("#datemask").inputmask("yyyy/mm/dd", {"placeholder": "yyyy/mm/dd"});
                //Datemask2 mm/dd/yyyy
                $("#datemask2").inputmask("yyyy/mm/dd", {"placeholder": "yyyy/mm/dd"});
                //Money Euro
                $("[data-mask]").inputmask();
                
                //Date range picker
                $('#reservation').daterangepicker({format: 'YYYY/MM/DD'});
                //Date range picker with time picker
                $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'YYYY/MM/DD h:mm A'});
                //Date range as a button
                $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }, startDate: moment().subtract(29, 'days'), endDate: moment() }, function (start, end) {
                    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                });
                
                <?php 
                    $newDate = date('m/d/Y', time());
                    $newDate2 =$newDate;
                    if($page=="payroll-create"): 
                        if(isset($_GET['id'])) {
                            $rqdata = array("model"=>"payroll", "condition"=>" WHERE id = '".$_GET['id']."'");
                            $department = $app->getRecord2($rqdata);
                            $rvalue = $department['data'][0];
                            $originalDate = $rvalue['datefrom'];
                            $newDate = date("m/d/Y", strtotime($originalDate)); 
                            $originalDate2 = $rvalue['dateto'];
                            $newDate2 = date("m/d/Y", strtotime($originalDate2));   
                        }
                ?>
                    $("#reservation").data('daterangepicker').setStartDate("<?php echo $newDate; ?>");
                    $("#reservation").data('daterangepicker').setEndDate("<?php echo $newDate2; ?>");
                <?php endif; ?>
                
                //Date picker
                $('#datepicker').datepicker({
                  autoclose: true
                });
                $('#datepicker2').datepicker({
                  autoclose: true
                });
                $('#datepicker3').datepicker({
                  autoclose: true
                });
                $('#datepicker4').datepicker({
                  autoclose: true
                });
                $('#datepicker5').datepicker({
                  autoclose: true
                });
                $('#datepicker6').datepicker({
                  autoclose: true
                });
                $('#datepicker7').datepicker({
                  autoclose: true
                });
                $('#datepicker8').datepicker({
                  autoclose: true
                });
                $('#datepicker9').datepicker({
                  autoclose: true
                });
                $('#datepicker10').datepicker({
                  autoclose: true
                });
                $(".timepicker").timepicker({
                  showInputs: false
                });

                //iCheck for checkbox and radio inputs
                $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                  checkboxClass: 'icheckbox_minimal-blue',
                  radioClass: 'iradio_minimal-blue'
                });
                //Red color scheme for iCheck
                $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                  checkboxClass: 'icheckbox_minimal-red',
                  radioClass: 'iradio_minimal-red'
                });
                //Flat red color scheme for iCheck
                $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                  checkboxClass: 'icheckbox_flat-green',
                  radioClass: 'iradio_flat-green'
                });
                
                //Colorpicker
                $(".my-colorpicker1").colorpicker();
                //color picker with addon
                $(".my-colorpicker2").colorpicker();
            });
    
            function setDateRange(from,to){
                $("#reservation").data('daterangepicker').setStartDate(from);
                $("#reservation").data('daterangepicker').setEndDate(to);
            }
            
        </script>
    </body>
</html>
