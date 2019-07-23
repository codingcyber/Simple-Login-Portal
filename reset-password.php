<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();
require_once('includes/connect.php');
include('includes/header.php');
require_once('includes/smtp.php');

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
//1 . Get the user details based on reset key and id, display them in the form
//2. After form submission, check the reset key and id. if exists update the password and delete the reset token from password_reset table, send confirmation email
if(isset($_POST) & !empty($_POST)){
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

    if(empty($errors)){
    	// update the password after submitting new password, before that check the reset token with user id
    	$sql = "SELECT * FROM password_reset WHERE reset_token=:reset_token AND uid=:uid";
		$result = $db->prepare($sql);
		$values = array(':reset_token'		=> $_POST['key'],
						':uid'				=> $_POST['id']
						);
		$result->execute($values);
		$count = $result->rowCount();
		if($count == 1){
			// update the password
			$updsql = "UPDATE users SET password=:password, updated=NOW() WHERE id=:id";
			$updresult = $db->prepare($updsql);
			$values = array(':password'		=> $pass_hash,
							':id'			=> $_POST['id']
							);
			$updres = $updresult->execute($values);

			$usersql = "SELECT * FROM users WHERE id=?";
	        $userresult = $db->prepare($usersql);
	        $userresult->execute(array($_POST['id']));
	        $user = $userresult->fetch(PDO::FETCH_ASSOC);
			if($updres){
				// delete the reset token from password_reset table & send email
				$delsql = "DELETE FROM password_reset WHERE reset_token=?";
				$delresult = $db->prepare($delsql);
				$delres = $delresult->execute(array($_POST['key']));
				if($delres){
					// send email
					$mail = new PHPMailer(true);

	                try {

	                    $mail->isSMTP();                                            // Set mailer to use SMTP
	                    $mail->Host       = $smtphost;  // Specify main and backup SMTP servers
	                    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
	                    $mail->Username   = $smtpuser;                     // SMTP username
	                    $mail->Password   = $smtppass;                               // SMTP password
	                    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
	                    $mail->Port       = 587;                                    // TCP port to connect to

	                    //Recipients
	                    $mail->setFrom('test@example.com', 'Vivek Vengala');
	                    $mail->addAddress($user['email'], $user['username']);     // Add a recipient

	                    // Content
	                    $mail->isHTML(true);                                  // Set email format to HTML
	                    $mail->Subject = 'Password Updated';
	                    $mail->Body    = "Your Account Password Updated, Login to Your Account";
	                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	                    $mail->send();
	                    //$messages[] = 'Password Updated, Confirmation Email Sent';
	                    header("location: login.php");
	                } catch (Exception $e) {
	                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	                }
				}
			}
		}
    }

}

// 1. Create CSRF token
$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();

// check the reset token is valid or not
$sql = "SELECT * FROM password_reset WHERE reset_token=:reset_token AND uid=:uid";
$result = $db->prepare($sql);
$values = array(':reset_token'		=> $_GET['key'],
				':uid'				=> $_GET['id']
				);
$result->execute($values);
$count = $result->rowCount();
if($count == 1){
	// select sql query to fetch user details from users table using user id
	$usersql = "SELECT * FROM users WHERE id=?";
	$userresult = $db->prepare($usersql);
	$userresult->execute(array($_GET['id']));
	$usercount = $userresult->rowCount();
	$userres = $userresult->fetch(PDO::FETCH_ASSOC);
}else{
	$errors[] = "There is some problem with Reset Token, Contact Site Admin!";
}
if(!isset($_GET['key']) || !isset($_GET['id'])){ header("location: login.php");}
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Update Password</h3>
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
                        <input type="hidden" name="key" value="<?php echo $_GET['key']; ?>">
                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="User Name" name="username" type="text" autofocus value="<?php if(isset($userres['username'])){ echo $userres['username']; } ?>" disabled>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="E-mail" name="email" type="email" value="<?php if(isset($userres['email'])){ echo $userres['email']; } ?>" disabled>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Repeat Password" name="passwordr" type="password" value="">
                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <input type="submit" class="btn btn-lg btn-success btn-block" value="Update Password" />
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>