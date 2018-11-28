<?php
/*This code ends the session and logs the user out */
session_start();
session_destroy();
unset($_SESSION['sesh_user']);  
echo "<meta http-equiv=refresh content=\"0; URL=tijnmainlogin.php\">";
?>