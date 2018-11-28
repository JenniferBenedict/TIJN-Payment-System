<link rel="stylesheet" href= "css/bootstrap.min.css">
<link rel="stylesheet" href="tijn.css">
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
?>
<html>
<body>
     <div id= "mydiv" name="mydiv" class="inline">
     <ul>
  <li><a href=tijnmainmenu.php>Back to Main Menu</a></li>

<!--form for user to enter their send request details -->

</ul>
     </div> 
 <h1>Outgoing Payment Form</h1>
<form method="post" action=""style="margin-left:200px;">
  Recipient Email/Phone:<br>
  <input type="text" name="identifier" value=""placeholder="Email/Phone"><br>
  Amount:<br>
  <input type="text" name="amount" value="" placeholder="0.00"><br>
    Memo (Optional):<br>
<textarea rows="5" name="memo" cols="30" value="" placeholder="Example: 'Payment for Lunch'"></textarea><br><br>
<input type="submit" name = "submitrequest" value="Send Request" class="btn btn-lg btn-success btn-block"  style="width:200px; margin:auto;"/> 
</form>
   
    

<?php
/* obtain the details of the send request from the form */
    $usrID=$_SESSION['sesh_user'];
    $ssnquery = "SELECT User_Account.ssn FROM User_Account WHERE User_Account.username= '$usrID'";
    $ssnqueryresult = mysqli_query($link, $ssnquery) or trigger_error($db->error);

    $ssnrow = mysqli_fetch_array($ssnqueryresult);
    $ssn = $ssnrow['ssn'];
    $identifier = $_POST['identifier']; // this is the sender's Email address
    $amount = $_POST['amount'];
    $lastname = $_POST['lastname'];
    $memo = $_POST['memo'];
    
    /* if the user clicks submit, first enter the transaction details into the 
    request_transaction table */
if(isset($_POST['submitrequest'])){
    /*obtain the sender's current balance*/
    $myBalanceQuery= "SELECT User_Account.Balance FROM User_Account WHERE User_Account.username= '$usrID'";
    $myBalanceResult = mysqli_query($link, $myBalanceQuery) or trigger_error($db->error);
    $myBalanceRow = mysqli_fetch_array($myBalanceResult);
    $myBalance = $myBalanceRow['Balance'];
    if($myBalance < $amount){
        $amount = $myBalance;
        $alertText = "The amount you entered is higher than your balance, the recipient will receive your entire balance.";
    }
    $myNewBalance = $myBalance - $amount;
    
    /*update the sender's current balance based on their sending 
    amount */
    $updateMyBalanceQuery = "UPDATE `User_Account` SET `User_Account`.Balance='$myNewBalance' WHERE `User_Account`.ssn=$ssn";
    $updateMyBalanceResult = mysqli_query($link, $updateMyBalanceQuery) 
    or trigger_error($db->error);
    
    /*update the recipient's current balance */
    $recipientBalanceQuery= "SELECT User_Account.Balance FROM `Electronic_Address`,`User_Account`
WHERE `User_Account`.ssn=`Electronic_Address`.ssn AND `Electronic_Address`.`Identifier`='$identifier';";
    $recipientBalanceResult=mysqli_query($link, $recipientBalanceQuery) or trigger_error($db->error);
     $recipientBalanceRow = mysqli_fetch_array($recipientBalanceResult);
    $recipientBalance = $recipientBalanceRow['Balance'];
    $recipientNewBalance = $recipientBalance + $amount;
    
    /*update the recipient's current balance based on the amount being sent */
    $updateRecipientBalanceQuery = "UPDATE User_Account,Electronic_Address SET User_Account.Balance = '$recipientNewBalance' WHERE User_Account.ssn = Electronic_Address.ssn AND Electronic_Address.Identifier='$identifier';";
     $updateRecipientBalanceResult = mysqli_query($link, $updateRecipientBalanceQuery) 
    or trigger_error($db->error);
    
     $query = "INSERT INTO `Send_Transaction`(`Amount`, `Memo`, `Canceled`, `ssn`, `Identifier`, `username`) VALUES ('$amount','$memo','0','$ssn','$identifier','$usrID')";
    $result = mysqli_query($link, $query) 
    or trigger_error($db->error);
    
    
    
       if ($result) {
    echo "Your payment has been submitted!";
    echo "<br>";
    echo $alertText;

} else {
    echo "Error updating record: " . mysqli_error($conn);
}}}

    
    
    

            
    // You can also use header('Location: thank_you.php'); to redirect to another page.
    // You cannot use header and echo together. It's one or the other.
    
?>
        


</body>
</html>