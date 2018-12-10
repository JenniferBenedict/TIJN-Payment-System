<?php
    session_start();

require("tijndatabaseconnect.php");//$_SESSION['sesh_user'] = "test";
if (!isset($_SESSION['sesh_user']) || $_SESSION['sesh_user'] === '') { 
?>

<script text="text/javascript">
window.location.href = "tijnmainlogin.php";
</script>
<?php
}

?>
<link rel="stylesheet" href= "css/bootstrap.min.css">
<link rel="stylesheet" href="tijn.css">
<html>
<body>
   
    <div id= "mydiv" name="mydiv" class="inline">
     <ul>
<li><a href=tjinrequestmoney.php>Request Money</a></li>
  <li><a href=tijnsendmoney.php>Send Money</a></li>
<li><a href=tijnviewstatements.php>View Statements</a></li>
<li><a href=tijnaccountsettings.php>Account Settings</a></li>
 <li><a href=tjinlogout.php>Logout</a></li>
         </ul>




     </div> 
    <h1 style="margin-right:200px;">Search Transactions</h1>
<br>
    <br>
             <!--form for searching transactions between date change -->
<form method="post" action="" style="margin-right:200px;">
  Search for Transactions between Dates:<br>
  Beginning: <input type="text" name="fromDate" value=""placeholder="2018-11-01">   
    Ending: <input type="text" name="toDate" value=""placeholder="2018-12-01"> <br> <br>
<input type="submit" name = "submitDates" value="Search" class="btn btn-lg btn-success btn-block"  style="width:300px; margin:auto; height:50px;"/> <br> <br>
</form>
    
            <!--form for general transaction search -->
<form method="post" action="">
  Search for Transactions:<br>
    <input type="text" name="search" value=""placeholder="e.g. Lunch or 19.99" style="width:500px;"> <br> <br>
<input type="submit" name = "submitSearch" value="Search" class="btn btn-lg btn-success btn-block"  style="width:300px; margin:auto; height:50px;"/> <br> <br>
</form>  

    <!--form for searching for an account-->
<form method="post" action="">
  Search Users:<br>
    <input type="text" name="searchUser" value=""placeholder="e.g. Alice or Alice123@gmail.com" style="width:500px;"> <br> <br>
<input type="submit" name = "submitUserSearch" value="Search" class="btn btn-lg btn-success btn-block"  style="width:300px; margin:auto; height:50px;"/> <br> <br>
</form>  
    
<?php
if($db->connect_errno){
    printf("Connect failed: %s\n", $db->connect_error);
    exit();
}
$usrID= $_SESSION['sesh_user'];
 $ssnquery = "SELECT User_Account.ssn FROM User_Account WHERE User_Account.username= '$usrID'";
    $ssnqueryresult = mysqli_query($link, $ssnquery) or trigger_error($db->error);

    $ssnrow = mysqli_fetch_array($ssnqueryresult);
    $ssn = $ssnrow['ssn'];
    
/*Code Below shows all transactions sent from, sent to, requested from
requested to the user between a certain time frame */
$startDate=$_POST[fromDate]." 00:00:00";
$toDate=$_POST[toDate]." 00:00:00";
$userSearchText=$_POST[searchUser];
$generalSearch = $_POST[search];
if(isset($_POST['submitDates'])){
    
    /* if user chooses two dates, first search the send_transaction table for 
    transactions in which the logged in user has sent a payment to someone else, and create a table with these appropriate tuples*/
    $showtablequery="SELECT * FROM `Send_Transaction` WHERE `Send_Transaction`.`username`='$usrID' AND `Send_Transaction`.`Date/Time` BETWEEN '$startDate' AND '$toDate';";

$showtableresult= mysqli_query($link, $showtablequery) 
    or trigger_error($db->error); ?>
        <br>
 <div style="overflow: scroll;  margin-left:220px; margin-right:120px;">   
<TABLE class="table">
<TR>
<TH>Transaction ID</TH>
<TH>Amount</TH>
<TH>Date/Time</TH>
<TH>Sent To</TH>
<TH>Memo</TH>

</TR>
<?php
echo "Payments You've Sent:";
$array = array('Send ID', 'Amount', 'Date/Time','Identifier','Memo');
        while($row = mysqli_fetch_array($showtableresult)) {

    echo "<TR>";
    foreach($array as $field) { 
        echo "<TD>".$row[$field]."</TD>";
    }
    echo "</TR>";
} ?>
        </TABLE>
    </div>
    

  
<?php
    /*  search the request_transaction table for 
    transactions in which the logged in user has requested a payment from someone else, and create a table with these appropriate tuples*/
 $showSecondTableQuery="SELECT * FROM REQUEST_TRANSACTION WHERE REQUEST_TRANSACTION.username='$usrID' AND `Request_Transaction`.`Date/Time` BETWEEN '$startDate' AND '$toDate';";
$showSecondTableResult= mysqli_query($link, $showSecondTableQuery)
    or trigger_error($db->error);
   ?>
    <div style="overflow: scroll;  margin-left:220px; margin-right:120px;">   
<TABLE class="table">
<TR>
<TH>Transaction ID</TH>
<TH>Amount</TH>
<TH>Date/Time</TH>
<TH>Requested From</TH>
<TH>Memo</TH>

    </TR>
<?php
    echo "Payments You've Requested:";
    $secondArray = array('Request ID', 'Amount', 'Date/Time','Identifier','Memo');
        while($secondRow = mysqli_fetch_array($showSecondTableResult)) {

    echo "<TR>";
    foreach($secondArray as $secondField) { 
        echo "<TD>".$secondRow[$secondField]."</TD>";
    }
    echo "</TR>";
        }

   
 ?> 
    </TABLE>
    
    </div>
<?php
/*  search the send_transactioin table for 
    transactions in which the logged in user has had a payment sent to them, and create a table with these appropriate tuples*/    
    
 $showThirdTableQuery="SELECT `Send_Transaction`.`Send ID`,`Send_Transaction`.`Amount`,`Send_Transaction`.`Date/Time`,`Send_Transaction`.`username`, `Send_Transaction`.`Memo` FROM `Send_Transaction`,`Electronic_Address`,`User_Account`
WHERE `User_Account`.ssn=`Electronic_Address`.ssn AND `Electronic_Address`.`Identifier`=`Send_Transaction`.`Identifier` AND `User_Account`.`username`='$usrID' AND `Send_Transaction`.`Date/Time` BETWEEN '$startDate' AND '$toDate';";
$showThirdTableResult= mysqli_query($link, $showThirdTableQuery)
    or trigger_error($db->error);
   ?>
    <div style="overflow: scroll;  margin-left:220px; margin-right:120px;">   
<TABLE class="table">
<TR>
<TH>Transaction ID</TH>
<TH>Amount</TH>
<TH>Date/Time</TH>
<TH>Sending User</TH>
<TH>Memo</TH>

    </TR>
<?php
    echo "Payments Sent to you:";
    $thirdArray = array('Send ID', 'Amount', 'Date/Time','username','Memo');
        while($thirdRow = mysqli_fetch_array($showThirdTableResult)) {

    echo "<TR>";
    foreach($thirdArray as $thirdField) { 
        echo "<TD>".$thirdRow[$thirdField]."</TD>";
    }
    echo "</TR>";
        }

   
 ?> 
    </TABLE>
    
    </div>
    
    <?php
    
/*  search the request_transaction table for 
    transactions in which the logged in user has been requested to make a payment by another user, and create a table with these appropriate tuples*/    
    
 $showFourthTableQuery="SELECT `Request_Transaction`.`Request ID`,`Request_Transaction`.`Amount`,`Request_Transaction`.`Date/Time`,`Request_Transaction`.`username`, `Request_Transaction`.`Memo` FROM `Request_Transaction`,`Electronic_Address`,`User_Account`
WHERE `User_Account`.ssn=`Electronic_Address`.ssn AND `Electronic_Address`.`Identifier`=`Request_Transaction`.`Identifier` AND `User_Account`.`username`='$usrID' AND `Request_Transaction`.`Date/Time` BETWEEN '$startDate' AND '$toDate';";
$showFourthTableResult= mysqli_query($link, $showFourthTableQuery)
    or trigger_error($db->error);
   ?>
    <div style="overflow: scroll;  margin-left:220px; margin-right:120px;">   
<TABLE class="table">
<TR>
<TH>Transaction ID</TH>
<TH>Amount</TH>
<TH>Date/Time</TH>
<TH>Requesting User</TH>
<TH>Memo</TH>

    </TR>
<?php
    echo "Payments Requested from you:";
    $fourthArray = array('Request ID', 'Amount', 'Date/Time','username','Memo');
        while($fourthRow = mysqli_fetch_array($showFourthTableResult)) {

    echo "<TR>";
    foreach($fourthArray as $fourthField) { 
        echo "<TD>".$fourthRow[$fourthField]."</TD>";
    }
    echo "</TR>";
        }

   
 ?> 
    </TABLE>
    
    </div>
    <?php 
}
/*code below allows user to search TIJN for other users */
    if(isset($_POST['submitUserSearch'])){
    
    
    $showtablequery="SELECT User_Account.Name, Electronic_Address.Identifier, User_Account.username FROM User_Account, Electronic_Address WHERE User_Account.ssn = Electronic_Address.ssn AND (User_Account.Name LIKE '%$userSearchText%' OR Electronic_Address.Identifier LIKE '%$userSearchText%' OR User_Account.username LIKE '%$userSearchText%');";

$showtableresult= mysqli_query($link, $showtablequery) 
    or trigger_error($db->error); ?>
        <br>
 <div style="overflow: scroll;  margin-left:220px; margin-right:120px;">   
<TABLE class="table">
<TR>
<TH>User</TH>
<TH>Identifier</TH>
<TH>Username</TH>
<TH>Memo</TH>

</TR>
<?php
echo "Users:";
$array = array('Name', 'Identifier', 'username','Memo');
        while($row = mysqli_fetch_array($showtableresult)) {

    echo "<TR>";
    foreach($array as $field) { 
        echo "<TD>".$row[$field]."</TD>";
    }
    echo "</TR>";
} ?>
        </TABLE>
    </div>
    <?php
    }
    
    
    
    
    if(isset($_POST['submitSearch'])){
        /*search the send_transaction table for any transactions that the user sent that 
        correlates to the search query*/
        
  $showtablequery="SELECT * FROM `Send_Transaction` WHERE `Send_Transaction`.`username`='$usrID' AND (`Send_Transaction`.`Memo` LIKE '%$generalSearch%' OR `Send_Transaction`.`Amount` LIKE '%$generalSearch%' OR `Send_Transaction`.`Identifier` LIKE '%$generalSearch%' );";

$showtableresult= mysqli_query($link, $showtablequery) 
    or trigger_error($db->error); ?>
        <br>
 <div style="overflow: scroll;  margin-left:220px; margin-right:120px;">   
<TABLE class="table">
<TR>
<TH>Transaction ID</TH>
<TH>Amount</TH>
<TH>Date/Time</TH>
<TH>Sent To</TH>
<TH>Memo</TH>

</TR>
<?php
echo "Payments You've Sent:";
$array = array('Send ID', 'Amount', 'Date/Time','Identifier','Memo');
        while($row = mysqli_fetch_array($showtableresult)) {

    echo "<TR>";
    foreach($array as $field) { 
        echo "<TD>".$row[$field]."</TD>";
    }
    echo "</TR>";
} ?>
        </TABLE>
    </div>
    

  
<?php
    /*  search the request_transaction table for 
    transactions in which the logged in user has requested a payment from someone else, and create a table with these appropriate tuples*/
 $showSecondTableQuery="SELECT * FROM REQUEST_TRANSACTION WHERE REQUEST_TRANSACTION.username='$usrID' AND (`Request_transaction`.`Memo` LIKE '%$generalSearch%' OR `Request_transaction`.`Amount` LIKE '%$generalSearch%' OR `Request_transaction`.`Identifier` LIKE '%$generalSearch%');";
$showSecondTableResult= mysqli_query($link, $showSecondTableQuery)
    or trigger_error($db->error);
   ?>
    <div style="overflow: scroll;  margin-left:220px; margin-right:120px;">   
<TABLE class="table">
<TR>
<TH>Transaction ID</TH>
<TH>Amount</TH>
<TH>Date/Time</TH>
<TH>Requested From</TH>
<TH>Memo</TH>

    </TR>
<?php
    echo "Payments You've Requested:";
    $secondArray = array('Request ID', 'Amount', 'Date/Time','Identifier','Memo');
        while($secondRow = mysqli_fetch_array($showSecondTableResult)) {

    echo "<TR>";
    foreach($secondArray as $secondField) { 
        echo "<TD>".$secondRow[$secondField]."</TD>";
    }
    echo "</TR>";
        }

   
 ?> 
    </TABLE>
    
    </div>
<?php
/*  search the send_transactioin table for 
    transactions in which the logged in user has had a payment sent to them, and create a table with these appropriate tuples*/    
    
 $showThirdTableQuery="SELECT `Send_Transaction`.`Send ID`,`Send_Transaction`.`Amount`,`Send_Transaction`.`Date/Time`,`Send_Transaction`.`username`, `Send_Transaction`.`Memo` FROM `Send_Transaction`,`Electronic_Address`,`User_Account`
WHERE `User_Account`.ssn=`Electronic_Address`.ssn AND `Electronic_Address`.`Identifier`=`Send_Transaction`.`Identifier` AND `User_Account`.`username`='$usrID' AND (`Send_Transaction`.`Memo` LIKE '%$generalSearch%' OR `Send_Transaction`.`Amount` LIKE '%$generalSearch%' OR `Send_Transaction`.`username` LIKE '%$generalSearch%' ) ;";
$showThirdTableResult= mysqli_query($link, $showThirdTableQuery)
    or trigger_error($db->error);
   ?>
    <div style="overflow: scroll;  margin-left:220px; margin-right:120px;">   
<TABLE class="table">
<TR>
<TH>Transaction ID</TH>
<TH>Amount</TH>
<TH>Date/Time</TH>
<TH>Sending User</TH>
<TH>Memo</TH>

    </TR>
<?php
    echo "Payments Sent to you:";
    $thirdArray = array('Send ID', 'Amount', 'Date/Time','username','Memo');
        while($thirdRow = mysqli_fetch_array($showThirdTableResult)) {

    echo "<TR>";
    foreach($thirdArray as $thirdField) { 
        echo "<TD>".$thirdRow[$thirdField]."</TD>";
    }
    echo "</TR>";
        }

   
 ?> 
    </TABLE>
    
    </div>
    
    <?php
    
/*  search the request_transaction table for 
    transactions in which the logged in user has been requested to make a payment by another user, and create a table with these appropriate tuples*/    
    
 $showFourthTableQuery="SELECT `Request_Transaction`.`Request ID`,`Request_Transaction`.`Amount`,`Request_Transaction`.`Date/Time`,`Request_Transaction`.`username`, `Request_Transaction`.`Memo` FROM `Request_Transaction`,`Electronic_Address`,`User_Account`
WHERE `User_Account`.ssn=`Electronic_Address`.ssn AND `Electronic_Address`.`Identifier`=`Request_Transaction`.`Identifier` AND `User_Account`.`username`='$usrID' AND (`Request_transaction`.`Memo` LIKE '%$generalSearch%' OR `Request_transaction`.`Amount` LIKE '%$generalSearch%' OR `Request_transaction`.`username` LIKE '%$generalSearch%');";
$showFourthTableResult= mysqli_query($link, $showFourthTableQuery)
    or trigger_error($db->error);
   ?>
    <div style="overflow: scroll;  margin-left:220px; margin-right:120px;">   
<TABLE class="table">
<TR>
<TH>Transaction ID</TH>
<TH>Amount</TH>
<TH>Date/Time</TH>
<TH>Requesting User</TH>
<TH>Memo</TH>

    </TR>
<?php
    echo "Payments Requested from you:";
    $fourthArray = array('Request ID', 'Amount', 'Date/Time','username','Memo');
        while($fourthRow = mysqli_fetch_array($showFourthTableResult)) {

    echo "<TR>";
    foreach($fourthArray as $fourthField) { 
        echo "<TD>".$fourthRow[$fourthField]."</TD>";
    }
    echo "</TR>";
        }

   
 ?> 
    </TABLE>
    
    </div>
        
  <?php      
    }
    ?>

</body>
</html>


