<style>
    .skin-blue .wrapper {
        background:transparent;
    }
    
    body {
        background:linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)),url('http://d5cypwv7975xc.cloudfront.net/images/bg-app.jpg') center;
        height:100vh;
    }
    
    .login-box {
        margin:7% auto 2% auto;
    }
    
    .login-box-body {
        background:#18232e;
        border-radius: 5px;
        padding: 30px 50px 30px 50px;
        opacity:0.9;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        
    }
    
    img.logo-bottom {
        display:block;
        margin:30px auto 0 auto;
    }
    
    .login-box-msg {
        color:#94C0BB;
        font-size:15px;
        text-transform:uppercase;
        font-weight:600;
        padding: 0 0px 20px 0px;
    }
    
    input {
        background:#fff;
    }
    
    input:-webkit-autofill {
     -webkit-box-shadow: 0 0 0 30px white inset;
    }
    
    .checkbox label {
        color:#fff;
        
    }
    
    input.checkbox {
        border:none;
    }

    
    .log-submit .btn-outline {
        border:1px solid white;
    }
    
    .log-submit .btn-outline:hover, .log-submit .btn-outline:active, .log-submit .btn-outline:focus {
        background:#94C0BB;
        border:1px solid #94C0BB;
        color:#fff;
        
        -webkit-transition: all 1s ease;
    	-moz-transition: all 1s ease;
    	-o-transition: all 1s ease;
    	transition: all 1s ease;
    }
    
    
</style>

<div class="login-box">
    <div class="login-box-body">
        <center><img src="http://d5cypwv7975xc.cloudfront.net/images/mturk.png" width="100px;" alt="ICCA Panel"></center><br>

        <?php
            if(isset($_POST['btn_login'])){
                $data = array('method' => 'login',
                    'inputs' => array(
                        'uname'=>$_POST['login_user'],
                        'upass'=>$_POST['login_pass']
                    )
                );
                
                $data = $app->userLogin($data);
                if($data == 0) {
                    echo '<div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                        <h4><i class="icon fa fa-ban"></i> Access Denied!</h4>
                        Invalid Username Or Password!
                        </div>
                    ';
                } elseif($data == -1) {
                    echo '<div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                        <h4><i class="icon fa fa-ban"></i> Access Denied!</h4>
                        You have no access to this panel!
                      </div>
                    ';
                } elseif($data['status'] == 0) {
                    echo '<div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                        <h4><i class="icon fa fa-ban"></i> Access Denied!</h4>
                        Sorry your employment is inactive!
                      </div>
                    ';
                } else {
                    $dsp = $app->getRecordByIdL("department",$data['department_id']);
                
                    $_SESSION['login_user'] = $data['fname'];
                    $_SESSION['username'] = $data['un'];
                    $_SESSION['login_photo'] = $data['image'];
                    $_SESSION['login_designation'] = $data['position'];
                    $_SESSION['login_department'] = $dsp['data'][0]['name'];
                    $_SESSION['login_date'] = $data['last_session'];
                    $_SESSION['login_id'] = $data['id'];
                    $_SESSION['utype'] = $data['usertype'];
                    
                    if($data['etimezone']=="Asia/Manila" || $data['etimezone']==""){
                        $_SESSION['etimezone']="Asia/Manila";
                    } else {
                        $_SESSION['etimezone'] = $data['etimezone'];
                    }
                    
                    date_default_timezone_set($_SESSION['etimezone']);
                    
                    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                        $ip = $_SERVER['HTTP_CLIENT_IP'];
                    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    } else {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    }
                      
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'http://ip-api.com/json/' . $ip);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
                    $returnValue = curl_exec($ch);
                    curl_close($ch);
                    $result = json_decode($returnValue);
                    $emp_rdata = array("model"=>"panellogs",
                        "keys"=>"emp_id, public_ip, panel, isp, remote_address, http_x_forward_for, stamped",
                        "values"=>"'".$_SESSION['login_id']."', '".$ip."','AC Panel', '".$result->isp."','".$ip."', '".$ip."', '".date("Y-m-d H:i:s")."'"
                    );
                    
                    $app->createL($emp_rdata,1);
                    echo '<script>window.location = "index.php";</script>';
                    exit();
                }
            }
        ?>
        
        <p class="login-box-msg">ICCA Panel</p>

        <form  method="post" name="loginForm">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Username"  name="login_user" style="font-size: 14px;" required/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password"  name="login_pass" required/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label style="margin-left: 16px;">
                          <input type="checkbox" > Remember Me
                        </label>
                    </div>
                </div>
                <div class="col-xs-4 log-submit">
                    <input type="submit" class="btn btn-outline" value="Sign In" name="btn_login" />
                </div>
            </div>
        </form>
    </div>
</div>

<img src="http://d5cypwv7975xc.cloudfront.net/images/aclogo.png" class="img-responsive logo-bottom" width="200" alt="logo">


