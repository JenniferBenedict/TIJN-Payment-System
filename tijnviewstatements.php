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
$usrID=$_SESSION['sesh_user'];
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
    <h1 style="margin-right:200px;">View Statements</h1>
    <br>
    <br>
     <form method="post" action="">
<select name = "Month"/>
     <option value="Select">Select Month</option>
            <?php
                echo "<option value='01'>".January.'</option>';    
                echo "<option value='02'>".February.'</option>'; 
                echo "<option value='03'>".March.'</option>';    
                echo "<option value='04'>".April.'</option>';
                echo "<option value='05'>".May.'</option>';    
                echo "<option value='06'>".June.'</option>'; 
                echo "<option value='07'>".July.'</option>';    
                echo "<option value='08'>".August.'</option>';
                echo "<option value='09'>".September.'</option>';    
                echo "<option value='10'>".October.'</option>'; 
                echo "<option value='11'>".November.'</option>';    
                echo "<option value='12'>".December.'</option>';
            echo "</select>"; 
         ?>
         <select name = "Year"/>
     <option value="Select">Select Year</option>
            <?php
                echo "<option value='2008'>".'2008'.'</option>';    
                echo "<option value='2009'>".'2009'.'</option>'; 
                echo "<option value='2010'>".'2010'.'</option>';    
                echo "<option value='2011'>".'2011'.'</option>';
                echo "<option value='2012'>".'2012'.'</option>';    
                echo "<option value='2013'>".'2013'.'</option>'; 
                echo "<option value='2014'>".'2014'.'</option>';    
                echo "<option value='2015'>".'2015'.'</option>';
                echo "<option value='2016'>".'2016'.'</option>';    
                echo "<option value='2017'>".'2017'.'</option>'; 
                echo "<option value='2018'>".'2018'.'</option>';    
            echo "</select>"; 
         ?>
         <br>
         <input type="submit" name = "submitMonth" value="Generate Statement" class="btn btn-lg btn-success btn-block"  style="width:300px; height:40px; margin:auto;"/> 
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
    $month=$_POST['Month'];
$year=$_POST['Year'];
if(isset($_POST['submitMonth'])){
//replace

$statementDate="%$year-$month-%";
    
      $amountYouSentQuery = "SELECT SUM(Send_Transaction.Amount) AS Amount FROM Send_Transaction WHERE Send_Transaction.username='$usrID' AND Send_Transaction.`Date/Time` LIKE '$statementDate';";
    $amountYouSentQueryResult = mysqli_query($link, $amountYouSentQuery) or trigger_error($db->error);

    $amountYouSentRow = mysqli_fetch_array($amountYouSentQueryResult);
    $amountYouSent = round($amountYouSentRow['Amount'],2);
    
$amountYouRequestedQuery = "SELECT SUM(Request_Transaction.Amount) AS Amount FROM Request_Transaction WHERE Request_Transaction.username='$usrID' AND Request_Transaction.`Date/Time` LIKE '$statementDate';";
    $amountYouRequestedQueryResult = mysqli_query($link, $amountYouRequestedQuery) or trigger_error($db->error);

    $amountYouRequestedRow = mysqli_fetch_array($amountYouRequestedQueryResult);
    $amountYouRequested = round($amountYouRequestedRow['Amount'],2);
      $amountSentToYouQuery = "SELECT SUM(Send_Transaction.Amount) AS Amount FROM `Send_Transaction`,`Electronic_Address`,`User_Account`
WHERE `User_Account`.ssn=`Electronic_Address`.ssn AND `Electronic_Address`.`Identifier`=`Send_Transaction`.`Identifier` AND `User_Account`.`username`='$usrID' AND Send_Transaction.`Date/Time` LIKE '$statementDate';";
    $amountSentToYouResult = mysqli_query($link, $amountSentToYouQuery) or trigger_error($db->error);

    $amountSentToYouRow = mysqli_fetch_array($amountSentToYouResult);
    $amountSentToYou = round($amountSentToYouRow['Amount'],2);
    $amountRequestedFromYouQuery = "SELECT SUM(Request_Transaction.Amount) AS Amount FROM `Request_Transaction`,`Electronic_Address`,`User_Account`
WHERE `User_Account`.ssn=`Electronic_Address`.ssn AND `Electronic_Address`.`Identifier`=`Request_Transaction`.`Identifier` AND `User_Account`.`username`='$usrID' AND Request_Transaction.`Date/Time` LIKE '$statementDate';";
    $amountRequestedFromYouResult = mysqli_query($link, $amountRequestedFromYouQuery) or trigger_error($db->error);

    $amountRequestedFromYouRow = mysqli_fetch_array($amountRequestedFromYouResult);
    $amountRequestedFromYou = round($amountRequestedFromYouRow['Amount'],2);
$totalOutGoing=round($amountYouSent + $amountRequestedFromYou,2);
$totalIncoming=round($amountYouRequested + $amountSentToYou,2);
echo "Total Outgoing Payments: $";
    echo $totalOutGoing;
    echo "<br>";
echo "Total Incoming Payments: $";
echo $totalIncoming;
$showtablequery="SELECT * FROM Send_Transaction WHERE Send_Transaction.username='$usrID' AND Send_Transaction.`Date/Time` LIKE '$statementDate';";
$showtableresult= mysqli_query($link, $showtablequery) 
    or trigger_error($db->error); 
    
    ?>
        <br>
 <div style="overflow: scroll;  margin-left:220px; margin-right:120px;">   
<TABLE class="table">
<TR>
<TH>Transaction ID</TH>
<TH>Amount</TH>
<TH>Date/Time</TH>
<TH>Sent To</TH>
</TR>
<?php
echo "Payments You've Sent";
$array = array('Send ID', 'Amount', 'Date/Time','Identifier');
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
 $showSecondTableQuery="SELECT * FROM REQUEST_TRANSACTION WHERE REQUEST_TRANSACTION.username='$usrID' AND Request_Transaction.`Date/Time` LIKE '$statementDate';";
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
    </TR>
<?php
    echo "Payments You've Requested";
    $secondArray = array('Request ID', 'Amount', 'Date/Time','Identifier');
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
    $showtablequery="SELECT `Send_Transaction`.`Send ID`,`Send_Transaction`.`Amount`,`Send_Transaction`.`Date/Time`,`Send_Transaction`.`username` FROM `Send_Transaction`,`Electronic_Address`,`User_Account`
WHERE `User_Account`.ssn=`Electronic_Address`.ssn AND `Electronic_Address`.`Identifier`=`Send_Transaction`.`Identifier` AND `User_Account`.`username`='$usrID' AND Send_Transaction.`Date/Time` LIKE '$statementDate';";
$showtableresult= mysqli_query($link, $showtablequery) 
    or trigger_error($db->error); 
 
    ?>
        <br>
 <div style="overflow: scroll;  margin-left:220px; margin-right:120px;">   
<TABLE class="table">
<TR>
<TH>Transaction ID</TH>
<TH>Amount</TH>
<TH>Date/Time</TH>
<TH>Sent From</TH>
</TR>
<?php
echo "Payments Sent To You";
$array = array('Send ID', 'Amount', 'Date/Time','username');
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
    $showtablequery="SELECT `Request_Transaction`.`Request ID`,`Request_Transaction`.`Amount`,`Request_Transaction`.`Date/Time`,`Request_Transaction`.`username` FROM `Request_Transaction`,`Electronic_Address`,`User_Account`
WHERE `User_Account`.ssn=`Electronic_Address`.ssn AND `Electronic_Address`.`Identifier`=`Request_Transaction`.`Identifier` AND `User_Account`.`username`='$usrID' AND Request_Transaction.`Date/Time` LIKE '$statementDate';";
$showtableresult= mysqli_query($link, $showtablequery) 
    or trigger_error($db->error); 
    
    ?>
        <br>
 <div style="overflow: scroll;  margin-left:220px; margin-right:120px;">   
<TABLE class="table">
<TR>
<TH>Transaction ID</TH>
<TH>Amount</TH>
<TH>Date/Time</TH>
<TH>Requested From</TH>
</TR>
<?php
echo "Payments Requested From You";
$array = array('Request ID', 'Amount', 'Date/Time','username');
        while($row = mysqli_fetch_array($showtableresult)) {

    echo "<TR>";
    foreach($array as $field) { 
        echo "<TD>".$row[$field]."</TD>";
    }
    echo "</TR>";
}
}
    ?>
        </TABLE>
    </div>
 

</body>
</html>