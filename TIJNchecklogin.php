<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require("tijndatabaseconnect.php");

?>
<link rel="stylesheet" href= "css/bootstrap.min.css">
<link rel="stylesheet" href="tijn.css">

<?php
// username and password sent from form in main login 
$myusername=$_POST['myusername']; 
$myusername = preg_replace('/\s+/', '', $myusername);
$mypassword=$_POST['mypassword']; 
$mypassword = preg_replace('/\s+/', '', $mypassword);


if ($conn) {
    $usrPassSQL = mysqli_query($link, "SELECT username, password FROM `User_Account` WHERE `User_Account`.username = '$myusername'");
}
/* checks database for username and password associated with user 
and compares with the entered credentials */
$sqlResult = mysqli_fetch_assoc($usrPassSQL);
$usrPassword = $sqlResult['password'];
$usrUsername = $sqlResult['username'];


// Mysql_num_row is counting table row
//$count=mysqli_num_rows($sqlResult);
// If result matched $myusername and $mypassword, table row must be 1 row
$_SESSION['sesh_user'] = "";
if (($usrUsername === $myusername) && ($usrPassword === $mypassword)) {
    $_SESSION['sesh_user'] = $myusername;
    //var_dump($_SESSION['sesh_user']);
//    echo "<script type='text/javascript'> document.location = 'login_success.php'; </script>";
    ?>
    <script text="text/javascript">
    window.location.href = "./tijnmainmenu.php";
    </script>

<?php
    //exit();
} else {
    echo "Your Username or Password is incorrect";

}

//if($count==1){
//echo"it worked";
//// Register $myusername, $mypassword and redirect to file "login_success.php"
//session_register("myusername");
//session_register("mypassword"); 
//header("location:login_success.php");
//}

?>