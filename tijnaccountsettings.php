<?php
session_start();
require("tijndatabaseconnect.php");
if (!isset($_SESSION['sesh_user']) || $_SESSION['sesh_user'] === '') { 
    ?>
<script text="text/javascript">
window.location.href = "tijnmainlogin.php";
</script>
<?php
}
else{
     $usrID= $_SESSION['sesh_user'];

?>

<link rel="stylesheet" href= "css/bootstrap.min.css">
<link rel="stylesheet" href="tijn.css">

<html>
    <body>
        <div id= "mydiv" name="mydiv" class="inline">
     <ul>
  <li><a href=tijnmainmenu.php>Back to Main Menu</a></li>


</ul>
     </div> 
        <h2 class="panel-title">Account Details</h2>
    </body>
</html>
    <?php
    
    /*Query and display all the user's user account details in a table */
$showTableQuery="SELECT * FROM User_Account WHERE User_Account.username='$usrID'";
$showTableResult= mysqli_query($link, $showTableQuery) 
    or trigger_error($db->error); 
?>


        <br>
 <div style=" margin-left:220px; margin-right:120px;">   
<TABLE class="table">
<TR>
<TH>SSN</TH>
<TH>Name</TH>
<TH>Balance</TH>
<TH>Primary Bank Account Number</TH>
<TH>Username</TH>
<TH>Password</TH>
<TH>Primary BankID</TH>
</TR>
<?php
$userdataarray=array();
$array = array('ssn', 'Name', 'Balance','BankAccountNumber','username','password','BankID');
 while($row = mysqli_fetch_array($showTableResult)) {

    echo "<TR>";
    foreach($array as $field) { 
        echo "<TD>".$row[$field]."</TD>";
        array_push($userdataarray,$row[$field]);
    }
    echo "</TR>";
}
//stores user data for 
$ssn = $userdataarray[0];
$Name = $userdataarray[1];
$Balance = $userdataarray[2];
$BankAccountNumber = $userdataarray[3];
$username = $userdataarray[4];
$password = $userdataarray[5];
$BankID = $userdataarray[6];
    
/*Query and display all the user's identifier details in a table */
    
$showSecondTableQuery="SELECT * FROM Electronic_Address WHERE Electronic_Address.ssn='$ssn'";
$showSecondTableResult = mysqli_query($link, $showSecondTableQuery) 
    or trigger_error($db->error);
    
 ?>
        </TABLE>
     <br>
 <div style="  margin-left:300px; margin-right:300px;">   
<TABLE class="table">
<TR>
<TH>Your Identifiers</TH>
</TR>
    <?php
$identifierArray = array('Identifier');
 while($identifierRow = mysqli_fetch_array($showSecondTableResult)) {

    echo "<TR>";
    foreach($identifierArray as $identifierField) { 
        echo "<TD>".$identifierRow[$identifierField]."</TD>";    }
    echo "</TR>";
}?>
            </TABLE>
     </div>
    
<?php
    
/*Query and display all the user's additional bank account
details in a table */
    
$showThirdTableQuery="SELECT * FROM Has_Additional WHERE Has_Additional.ssn='$ssn'";
$showThirdTableResult= mysqli_query($link, $showThirdTableQuery) 
    or trigger_error($db->error); 
?>
     
 <div style="  margin-left:300px; margin-right:300px;">   
<TABLE class="table">
<TR>
<TH>Additional Bank Account Numbers</TH>
<TH>Additional Bank ID's</TH>
</TR>
    <?php
$bankInfoArray = array('BankID','BankAccountNumber');
 while($bankInfoRow = mysqli_fetch_array($showThirdTableResult)) {

    echo "<TR>";
    foreach($bankInfoArray as $bankField) { 
        echo "<TD>".$bankInfoRow[$bankField]."</TD>";    }
    echo "</TR>";
}?>
            </TABLE>
     </div>


         <!--form for changing name -->
<form method="post" action="">
  Edit Name:<br>
  <input type="text" name="Name" value=""placeholder="'Name'"><br><br>
<input type="submit" name = "submitName" value="Submit" class="btn btn-lg btn-success btn-block"  style="width:300px; margin:auto; height:50px;"/> <br> <br>
</form>
     
    <!--form for changing username -->
<form method="post" action="">
  Edit Username:<br>
  <input type="text" name="Username" value=""placeholder="Username"><br><br>
<input type="submit" name = "submitUsername" value="Submit" class="btn btn-lg btn-success btn-block"  style="width:300px; margin:auto; height:50px;"/> <br> <br>
</form>
     
     <!--form for changing password -->
<form method="post" action="">
  Edit Password:<br>
  <input type="text" name="Password" value=""placeholder="Password"><br><br>
<input type="submit" name = "submitPassword" value="Submit" class="btn btn-lg btn-success btn-block"  style="width:300px; margin:auto; height:50px;"/> <br> <br>
</form>
      <!--form for changing Bank Account Number -->    
<form method="post" action="">
  Edit Primary Bank Account Number:<br>
  <input type="text" name="BankAccountNumber" value=""placeholder="Bank Account Number"><br>
    Edit Primary BankID:<br>
  <input type="text" name="BankID" value=""placeholder="Bank ID"><br><br>
<input type="submit" name = "submitBankInfo" value="Submit" class="btn btn-lg btn-success btn-block"  style="width:300px; margin:auto; height:50px;"/> <br> <br>
</form>
     
         <!--form for adding/removing Bank Account Number -->    
<form method="post" action="">
  Edit Additional Bank Account Number:<br>
  <input type="text" name="additionalBankAccountNumber" value=""placeholder="Bank Account Number"><br>
    Edit Additional BankID:<br>
  <input type="text" name="additionalBankID" value=""placeholder="Bank ID"><br><br>
<input type="submit" name = "addAdditionalBankInfo" value="Add" class="btn btn-lg btn-success btn-block"  style="width:300px; margin:auto; height:50px;"/>  <br>
    <input type="submit" name = "removeAdditionalBankInfo" value="Remove" class="btn btn-lg btn-success btn-block"  style="width:300px; margin:auto; height:50px;"/> <br>
</form>
     
    
     
     <!--form for changing email -->    
<form method="post" action="">
  Edit Email Address:<br>
  <input type="text" name="Email" value=""placeholder="Email"><br><br>
<input type="submit" name = "addEmail" value="Add" class="btn btn-lg btn-success btn-block"  style="width:300px; margin:auto; height:50px;"/> <br> 
<input type="submit" name = "removeEmail" value="Remove" class="btn btn-lg btn-success btn-block"  style="width:300px; margin:auto; height:50px;"/><br>
</form>
     
        <!--form for changing phone -->    
<form method="post" action="">
  Edit Phone Number:<br>
  <input type="text" name="Phone" value=""placeholder="Phone"><br><br>
<input type="submit" name = "addPhone" value="Add" class="btn btn-lg btn-success btn-block"  style="width:300px; margin:auto; height:50px;"/> <br> 
<input type="submit" name = "removePhone" value="Remove" class="btn btn-lg btn-success btn-block"  style="width:300px; margin:auto; height:50px;"/><br><br>
</form>
    </div>

<?php
//code to change user's name
$nameToChange = $_POST['Name'];
if(isset($_POST['submitName'])){
     $nameChangeQuery = "UPDATE `User_Account` SET `User_Account`.Name='$nameToChange' WHERE `User_Account`.ssn=$ssn";
    $nameChangeResult = mysqli_query($link, $nameChangeQuery) 
    or trigger_error($db->error);
       if ($nameChangeResult) {
    echo "Your Account has been Updated!";
    echo "<meta http-equiv=refresh content=\"0; URL=tijnaccountsettings.php\">";

} else {
    echo "Error updating record: " . mysqli_error($conn);
}
            }

//code to change user's password
$passwordToChange = $_POST['Password'];
if(isset($_POST['submitPassword'])){
     $passwordChangeQuery = "UPDATE `User_Account` SET `User_Account`.password='$passwordToChange' WHERE `User_Account`.ssn=$ssn";
    $passwordChangeResult = mysqli_query($link, $passwordChangeQuery) 
    or trigger_error($db->error);
       if ($passwordChangeResult) {
    echo "Your Account has been Updated!";
    echo "<meta http-equiv=refresh content=\"0; URL=tijnaccountsettings.php\">";

} else {
    echo "Error updating record: " . mysqli_error($conn);
}
            }



//code to change user's username
$usernameToChange = $_POST['Username'];
if(isset($_POST['submitUsername'])){
     $usernameChangeQuery = "UPDATE `User_Account` SET `User_Account`.username='$usernameToChange' WHERE `User_Account`.ssn=$ssn";
    $usernameChangeResult = mysqli_query($link, $usernameChangeQuery) 
    or trigger_error($db->error);
       if ($usernameChangeResult) {
    echo "Your Account has been Updated!";
    $_SESSION['sesh_user']=$usernameToChange;
    echo "<meta http-equiv=refresh content=\"0; URL=tijnaccountsettings.php\">";

} else {
    echo "Please choose another username: " . mysqli_error($conn);
}
            }



//code to change user's email address
$emailToChange = $_POST['Email'];
if(isset($_POST['addEmail'])){
     $emailAddDropQuery = "INSERT INTO `Electronic_Address`(`Identifier`, `Type`,`Verified`,`ssn`) VALUES ('$emailToChange',1,1,'$ssn');";
    $emailAddDropResult = mysqli_query($link, $emailAddDropQuery) 
    or trigger_error($db->error);
    if ($emailAddDropResult){
        echo "yay2";
    }
        echo "<meta http-equiv=refresh content=\"0; URL=tijnaccountsettings.php\">";


    }
elseif(isset($_POST['removeEmail'])){
     $emailAddDropQuery = "DELETE FROM `Electronic_Address` WHERE Identifier= '$emailToChange' AND ssn= '$ssn';";
    $emailAddDropResult = mysqli_query($link, $emailAddDropQuery) 
    or trigger_error($db->error);
    if ($emailAddDropResult){
        echo "yay2";
    }
        echo "<meta http-equiv=refresh content=\"0; URL=tijnaccountsettings.php\">";


    }

//code to change user's phone number
$phoneToChange = $_POST['Phone'];
if(isset($_POST['addPhone'])){
     $phoneAddDropQuery = "INSERT INTO `Electronic_Address`(`Identifier`, `Type`,`Verified`,`ssn`) VALUES ('$phoneToChange',0,1,'$ssn');";
    $phoneAddDropResult = mysqli_query($link, $phoneAddDropQuery) 
    or trigger_error($db->error);
    if ($phoneAddDropResult){
        echo "yay2";
    }
        echo "<meta http-equiv=refresh content=\"0; URL=tijnaccountsettings.php\">";


    }
elseif(isset($_POST['removePhone'])){
     $phoneAddDropQuery = "DELETE FROM `Electronic_Address` WHERE Identifier= '$phoneToChange' AND ssn= '$ssn';";
    $phoneAddDropResult = mysqli_query($link, $phoneAddDropQuery) 
    or trigger_error($db->error);
    if ($phoneAddDropResult){
        echo "yay2";
    }
        echo "<meta http-equiv=refresh content=\"0; URL=tijnaccountsettings.php\">";


    }

//code to change user's Primary Bank Account Number
$BANtoChange = $_POST['BankAccountNumber'];
$bankIDToChange = $_POST['BankID'];
if(isset($_POST['submitBankInfo'])){
     $BANChangeQuery = "UPDATE `Bank_Account` SET `Bank_Account`.BankAccountNumber='$BANtoChange',`Bank_Account`.BankID='$bankIDToChange' WHERE `Bank_Account`.BankID='$BankID' AND `Bank_Account`.BankAccountNumber='$BankAccountNumber'";
    $BANChangeResult = mysqli_query($link, $BANChangeQuery) 
    or trigger_error($db->error);
       if ($BANChangeResult) {
    echo "Your Account has been Updated!";
    echo "<meta http-equiv=refresh content=\"0; URL=tijnaccountsettings.php\">";

} else {
    echo "Error updating bank Account " . mysqli_error($conn);
}
            }

//code to add/remove user's additional Bank Account Numbers
$BANtoAddOrRemove = $_POST['additionalBankAccountNumber'];
$bankIDToAddOrRemove = $_POST['additionalBankID'];
if(isset($_POST['addAdditionalBankInfo'])){
     $bankAddOrDropQuery = "INSERT INTO `Bank_Account`(`BankAccountNumber`, `BankID`) VALUES ('$BANtoAddOrRemove','$bankIDToAddOrRemove');";
    $updateHasAdditionalQuery="INSERT INTO `Has_Additional`(`ssn`,`BankID`,`BankAccountNumber`,`Verified`) VALUES ('$ssn','$bankIDToAddOrRemove','$BANtoAddOrRemove',1);";
    $bankAddOrDropResult = mysqli_query($link, $bankAddOrDropQuery) 
    or trigger_error($db->error);
    $updateHasAdditionalResult = mysqli_query($link, $updateHasAdditionalQuery) 
    or trigger_error($db->error);
    if($bankAddOrDropResult){
        echo "yay1";
    }
    if ($updateHasAdditionalResult){
        echo "yay2";
    }
        echo "<meta http-equiv=refresh content=\"0; URL=tijnaccountsettings.php\">";


    }
elseif(isset($_POST['removeAdditionalBankInfo'])){
     $bankAddOrDropQuery = "DELETE FROM `Bank_Account` WHERE BankAccountNumber= '$BANtoAddOrRemove' AND BankID= '$bankIDToAddOrRemove';";
    $updateHasAdditionalQuery="DELETE FROM `Has_Additional` WHERE BankID = '$bankIDToAddOrRemove' AND BankAccountNumber='$BANtoAddOrRemove' AND ssn=$ssn;";
    $bankAddOrDropResult = mysqli_query($link, $bankAddOrDropQuery) 
    or trigger_error($db->error);
    $updateHasAdditionalResult = mysqli_query($link, $updateHasAdditionalQuery) 
    or trigger_error($db->error);
    if($bankAddOrDropResult){
        echo "yay1";
    }
    if ($updateHasAdditionalResult){
        echo "yay2";
    }
        echo "<meta http-equiv=refresh content=\"0; URL=tijnaccountsettings.php\">";


    }}
            
?>


