<?php 
session_start();
require_once('includes/connect.php');
require_once('if-loggedin.php');
include('includes/header.php');
if(isset($_POST) & !empty($_POST)){
    // PHP Form Validations
    if(empty($_POST['email'])){ $errors[]="User Name / E-Mail field is Required"; }
    if(empty($_POST['password'])){ $errors[]="Password field is Required"; }
    // CSRF Token Validation
    if(isset($_POST['csrf_token'])){
        if($_POST['csrf_token'] === $_SESSION['csrf_token']){
        }else{
            $errors[] = "Problem with CSRF Token Validation";
        }
    }
    // CSRF Token Time Validation
    $max_time = 60*60*24; // in seconds
    if(isset($_SESSION['csrf_token_time'])){
        $token_time = $_SESSION['csrf_token_time'];
        if(($token_time + $max_time) >= time() ){
        }else{
            $errors[] = "CSRF Token Expired";
            unset($_SESSION['csrf_token']);
            unset($_SESSION['csrf_token_time']);
        }
    }

    if(empty($errors)){
        // Check the Login Credentials
        $sql = "SELECT * FROM users WHERE ";
        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $sql .= "email=?";
        }else{
            $sql .= "username=?";
        }
        $result = $db->prepare($sql);
        $result->execute(array($_POST['email']));
        $count = $result->rowCount();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        if($count == 1){
            // Compare the password with password hash
            if(password_verify($_POST['password'], $res['password'])){
                // regenerate session id
                session_regenerate_id();
                $_SESSION['login'] = true;
                $_SESSION['id'] = $res['id'];
                $_SESSION['last_login'] = time();

                // redirect the user to members area/dashboard page
                header("location: index.php");
            }else{
                $errors[] = "User Name / E-Mail & Password Combination not Working";
            }
        }else{
            $errors[] = "User Name / E-Mail not Valid";
        }
    }
} 
// 1. Create CSRF token
$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();
?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <?php
                            if(!empty($errors)){
                                echo "<div class='alert alert-danger'>";
                                foreach ($errors as $error) {
                                    echo "<span class='glyphicon glyphicon-remove'></span>&nbsp;".$error."<br>";
                                }
                                echo "</div>";
                            }
                        ?>
                        <form role="form" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="text" autofocus value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Login" />
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include('includes/footer.php'); ?>