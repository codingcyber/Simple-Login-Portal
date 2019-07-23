<?php 
session_start();
require_once('includes/connect.php');
require_once('if-loggedin.php');
include('includes/header.php');
if(isset($_POST) & !empty($_POST)){
    // PHP Form Validations
    if(empty($_POST['username'])){ $errors[]="User Name field is Required"; }else{
        // Check Username is Unique with DB query
        $sql = "SELECT * FROM users WHERE username=?";
        $result = $db->prepare($sql);
        $result->execute(array($_POST['username']));
        $count = $result->rowCount();
        if($count == 1){
            $errors[] = "User Name already exists in database";
        }
    }
    if(empty($_POST['email'])){ $errors[]="E-mail field is Required"; }else{
        // Check Email is Unique with DB Query
        $sql = "SELECT * FROM users WHERE email=?";
        $result = $db->prepare($sql);
        $result->execute(array($_POST['email']));
        $count = $result->rowCount();
        if($count == 1){
            $errors[] = "E-Mail already exists in database";
        }
    }
    if(empty($_POST['mobile'])){ $errors[]="Mobile field is Required"; }
    if(empty($_POST['password'])){ $errors[]="Password field is Required"; }else{
        // check the repeat password
        if(empty($_POST['passwordr'])){ $errors[]="Repeat Password field is Required"; }else{
            // compare both passwords, if they match. Generate the Password Hash
            if($_POST['password'] == $_POST['passwordr']){
                // create password hash
                $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }else{
                // Display Error Message
                $errors[] = "Both Passwords Should Match";
            }
        }
    }

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

    // If no Errors, Insert the Values into users table
    if(empty($errors)){
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $result = $db->prepare($sql);
        $values = array(':username'     => $_POST['username'],
                        ':email'        => $_POST['email'],
                        ':password'     => $pass_hash
                        );
        $res = $result->execute($values);
        if($res){
            $messages[] = "User Registered";
            // get the id from last insert query and insert a new record into user_info table with mobile number
            $userid = $db->lastInsertID();
            $uisql = "INSERT INTO user_info (uid, mobile) VALUES (:uid, :mobile)";
            $uiresult = $db->prepare($uisql);
            $values = array(':uid'          => $userid,
                            ':mobile'       => $_POST['mobile']
                            );
            $uires = $uiresult->execute($values) or die(print_r($result->errorInfo(), true));
            if($uires){
                $messages[] = "Added User Meta Information";
            }

        }
    }
}
// CSRF Protection
// 1. Create CSRF token
$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();

// 2. add CSRF token to form
// 3. check the CSRF token on form submission
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Please Register</h3>
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
                    <?php
                        if(!empty($messages)){
                            echo "<div class='alert alert-success'>";
                            foreach ($messages as $message) {
                                echo "<span class='glyphicon glyphicon-ok'></span>&nbsp;".$message."<br>";
                            }
                            echo "</div>";
                        }
                    ?>
                    <form role="form" method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="User Name" name="username" type="text" autofocus value="<?php if(isset($_POST['username'])){ echo $_POST['username']; } ?>">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="E-mail" name="email" type="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Mobile" name="mobile" type="text" value="<?php if(isset($_POST['mobile'])){ echo $_POST['mobile']; } ?>">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Repeat Password" name="passwordr" type="password" value="">
                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <input type="submit" class="btn btn-lg btn-success btn-block" value="Register" />
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>