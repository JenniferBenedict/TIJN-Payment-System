<!DOCTYPE html>
<link rel="stylesheet" href= "css/bootstrap.min.css">
<link rel="stylesheet" href="tijn.css">
<html>
    <body>
 <div id= "mydiv" name="mydiv" class="inline">
     <ul>
<!--form for user to enter their login credentials -->

</ul>
     </div> 
        <div style="float: auto; style="margin-top: 100px;margin-left:200px;>

    		<div class="panel panel-default" style="margin-left:200px;">
			  	<div class="panel-heading"><br>
			    	<h2 class="panel-title">TIJN Payment System</h2>
			 	</div>
			  	<div class="panel-body">
			    	<form  method="post" action="tijnchecklogin.php">
                    <fieldset >
			    	  	<div class="form-group">
			    		    <input class="form-control" placeholder="Username" name="myusername" id="myusername"type="text"><br>
			    		</div>
			    		<div class="form-group">
			    			<input class="form-control" placeholder="Password" name="mypassword" type="password" value="" id="mypassword"><br>
                        </div>
			    		<input class="btn btn-lg btn-success btn-block" type="submit" value="Login" style="width:200px; margin:auto;"><br><br>
			    	</fieldset>
			      	</form>
                    <form method="post" action="tijnuserregistration.php">
			    		<input class="btn btn-lg btn-success btn-block" type="submit" value="Sign Up" style="width:200px; margin:auto;"><br><br>
                    </form>
                                           
         
			    </div>
    </div>
			</div>
    </body>
</html>
    