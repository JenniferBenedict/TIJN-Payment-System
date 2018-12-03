<link rel="stylesheet" href= "css/bootstrap.min.css">
<link rel="stylesheet" href="tijn.css">
<?php 
session_start();
require("tijndatabaseconnect.php");
    ?>


<html>
<body>
     <div id= "mydiv" name="mydiv" class="inline">
     <ul>
  <li><a href=tijnmainlogin.php>Back to Login</a></li>

<!--form for user to enter their send request details -->

</ul>
     </div> 
    <!--- This form is for the user to register with TIJN --->
 <h1>Sign Up for TIJN</h1>
<form method="post" action=""style="margin-left:200px;">
  SSN:<br>
  <input type="text" name="ssn" value=""><br>
  Name:<br>
  <input type="text" name="name" value="" ><br>
    Username:<br>
  <input type="text" name="username" value="" ><br>
    Email:<br>
  <input type="text" name="email" value="" ><br>
     Phone:<br>
  <input type="text" name="phone" value="" ><br>
        Password:<br>
  <input name="password" value="" type ="password"><br>
        Primary Bank Account Number:<br>
  <input type="text" name="primaryBankAccountNumber" value="" ><br>
        Primary Bank ID:<br>
  <input type="text" name="primaryBankID" value="" ><br><br>
<input type="submit" name = "signUp" value="Sign Up" class="btn btn-lg btn-success btn-block"  style="width:200px; height:50px; margin:auto;"/> 
</form>
   
    

<?php
/* obtain the details of the user from the form */
    $ssn = $_POST['ssn'];
    $name = $_POST['name'];    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $primaryBankAccountNumber = $_POST['primaryBankAccountNumber'];
    $primaryBankID = $_POST['primaryBankID'];
    


    
    /* if the user clicks submit, first enter the user's bank account info in Bank_Account table*/
if(isset($_POST['signUp'])){
    
    $registerBankInfoQuery = "INSERT IGNORE INTO Bank_Account (BankAccountNumber,BankID) VALUES ('$primaryBankAccountNumber', '$primaryBankID');";
    $registerBankInfoResult = mysqli_query($link, $registerBankInfoQuery) 
    or trigger_error($db->error);
    
    /*Then enter the user's details in the User_Account table*/
    $registerUserInfoQuery="INSERT INTO `User_Account`(`ssn`, `Confirmed`, `Name`, `Balance`, `BankAccountNumber`, `username`, `password`, `BankID`) VALUES ('$ssn',1,'$name',100,'$primaryBankAccountNumber','$username','$password','$primaryBankID');";
     $registerUserInfoResult = mysqli_query($link, $registerUserInfoQuery) 
    or trigger_error($db->error);
    if ($registerUserInfoResult){
        echo "Welcome to TIJN, Registration Successful!";
        
    /*Then enter the user's phone and email in the Electronic_Address table*/
        $registerPhoneQuery= "INSERT INTO `Electronic_Address`(`Identifier`, `Type`, `verified`, `ssn`) VALUES ('$phone',0,1,'$ssn');";
            $registerPhoneResult=  mysqli_query($link, $registerPhoneQuery) 
    or trigger_error($db->error);
        if($registerPhoneResult){
        }
        else{
            echo"<br>";
            echo"That phone number already exists, please log into your account and add a different phone number under Account Settings!";
        }
        $registerEmailQuery= "INSERT INTO `Electronic_Address`(`Identifier`, `Type`, `verified`, `ssn`) VALUES ('$email',1,1,'$ssn');";
        
            $registerEmailResult=  mysqli_query($link, $registerEmailQuery) 
    or trigger_error($db->error);
        if($registerEmailResult){
        }
        else{
            echo"<br>";
            echo"That email address already exists, please log into your account and add a different email address under Account Settings!";
        }
        
        
    }
    else{
        echo "That SSN or Username already exists, please resubmit your registration information!";
    }
    
}


    
    
    

            
    // You can also use header('Location: thank_you.php'); to redirect to another page.
    // You cannot use header and echo together. It's one or the other.
    
?>
        


</body>
</html>