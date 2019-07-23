# Simple-Login-Portal
This is Simple Secure Login Portal with User Registration, Login &amp; Reset Password Functionality
All forms are with CSRF Protection.

## Setup the Script
1. Download the Script
2. Extract it into web directory
3. Create a Database with the name of **login-portal** & import the SQL file into the database

4. Configure the Database Credentials in **connect.php** file located under **includes/connect.php**. You will find this code on line number 2 & 3.
```php	
	$dsn = 'mysql:host=localhost;dbname=login-portal';
	$db = new PDO($dsn, 'username', 'password');
```

5. If you are using any other name for the main directory, then update the path in **reset.php** file located under in root directory. You will find this statement on line number 13.
```php
$url = "http://localhost/Simple-Login-Portal/";
```

## After the above steps, your code should work as expected.

## For Sending Emails Follow these steps

Update these lines with your SMTP login credentials in **smtp.php** file located under **includes/smtp.php** on line numbers 2 & 4.
```php
	$smtphost = 'smtp.sendgrid.net'; // SMTP Host Name
	$smtpuser = ''; // SMTP User Name
	$smtppass = ''; // SMTP Password
```
Note : If you don't have an account, register a free sendgrid account or Amazon SES (paid) account to use SMTP.

# For Complete text Article and Video Tutorials checkout these links
<a href="https://codingcyber.org/simple-user-registration-script-in-php-and-mysql-84/">Simple Secure User Registration Script in PHP and MySql</a>

<a href="https://codingcyber.org/simple-login-script-php-and-mysql-64/">Simple Secure Login Script in PHP and MySql</a>

<a href="https://codingcyber.org/send-forgotten-password-by-mail-using-php-and-mysql-35/">Reset Password By Mail Using PHP And MySql</a>

<a href="https://www.udemy.com/php-user-login-registration-system/?couponCode=GITHUB">Complete User Admin Portal Course on Udemy</a>
