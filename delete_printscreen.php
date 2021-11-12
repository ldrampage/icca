<?php 
    error_reporting(E_ALL); ini_set('display_errors', 1);
    include("core/iccaFunctionsNew.php");
    $iccaFunc = new iccaFunc();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <meta name="description" content="This is an example of a meta description.">
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <?php 
            /*
            $newDate= date("Y-m-d", strtotime("-2 months"));
            
            $begin = new DateTime('2020-01-10');
            $end = new DateTime($newDate);

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            
        
            foreach ($period as $dt) {
                echo $dt->format("Ymd"). "<br>";
            } */
            if(isset($_POST['submit'])){
                $path = "../images/samplefolder";
                if(!rmdir($path)){
                    echo "Could not remove $path";
                } else{
                    echo "Remove success";
                }
            }
            
            
        ?>
        
        <form method="POST">
            
            <p>
                Delete 2 month old images from tbl_printscreen
            </p>
            <input type="hidden" value="<?php echo $newDate." 00:00:00"; ?>" name="date">
            <input type="submit" value="submit" name="submit"/>
        </form>    
    </body>
</html
