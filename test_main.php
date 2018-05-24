<!DOCTYPE html>
<?php
require_once "pdo_test.php";
session_start();

$salt = 'XyZzy12*_';

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['id']) && isset($_POST['pw']) ) {
	unset($_SESSION['id']);  // Logout current user
    if ( strlen($_POST['id']) < 1 || strlen($_POST['pw']) < 1 ) {
        $_SESSION['failure'] = "User id and password are required";
		header( 'Location: test_main.php' ) ;
        return;
    } else {
		$stmt1 = $pdo->prepare("SELECT a_id,a_pw FROM admin where a_id = :id");
		$stmt1->execute(array(
							':id' => $_POST['id']));
		$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
		if ( $row1 === false ) {
			$stmt2 = $pdo->prepare("SELECT t_id,t_pw FROM teacher where t_id = :id");
			$stmt2->execute(array(
							':id' => $_POST['id']));
			$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
			if ( $row2 ===false ){
				$_SESSION['failure']="No such user ID";
				header( 'Location: test_main.php' ) ;
				return;
			}
			else {
				$check = hash('md5', $salt.$_POST['pw']);    //convert pw to hash to check with hash value of pw in db
				$_SESSION['check']=$check;
				if ( $check == $row2['t_pw']) {
					// Redirect the browser to view.php
					$_SESSION['id'] = $_POST['id'];
					$_SESSION['type'] = 1;            // type 0 for admin, type 1 for teachers
					header( 'Location: view.php' ) ;
					error_log("Login success ".$_SESSION['id']);
					return;
				} else{
					$_SESSION['failure'] = "Incorrect password.";
					header( 'Location: test_main.php' ) ;
					error_log("Login fail ".$_SESSION['id']." $check");
					return;
				} 
			}
		}
		else {
			$check = hash('md5', $salt.$_POST['pw']);    //convert pw to hash to check with hash value of pw in db
			$_SESSION['check']=$check;
			if ( $check == $row1['a_pw']) {
				// Redirect the browser to view.php
				$_SESSION['id'] = $_POST['id'];
				$_SESSION['type'] = 0;            // type 0 for admin, type 1 for teachers
				header( 'Location: view.php' ) ;
				error_log("Login success ".$_SESSION['id']);
				return;
			} else{
				$_SESSION['failure'] = "Incorrect password.";
				header( 'Location: index.php' ) ;
				error_log("Login fail ".$_SESSION['id']." $check");
				return;
			} 
		
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<title> Online test system</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<h1>Online test system</h1><hr>
<form>
<button type="button" id="login_button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#login">Log In</button>
<button type="button" id="reg_button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#reg">Register Here</button>

<!--Login Pop up-->
<div class="modal fade" id="login">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"> &times;</button>
                                        <h2 class="modal-title">Log In</h2>
                                    </div>
                                    <?php
									if ( isset($_SESSION['failure']) ) {
									echo('<p style="color: red;">'.htmlentities($_SESSION['failure'])."</p>\n");
									echo  $_SESSION['check'];
									//echo $_SESSION['type'];
									unset($_SESSION['failure']);
									}
									?>
                                    <div class="modal-body">
                                        <form name="login" id="log_in" onsubmit="return validate()" action="form_send.php" method="post">
                                            
                                            <label for="user_id">User ID</label>
                                            <input type="text" id="userid" name="id" placeholder="Your user ID.."><br />

                                            <label for="password">Password </label>
                                            <input type="password" id="password" name="pw" placeholder="Give your password.."><br />
                                            <input type="submit" name='submit' value="Log In">
                                            
                                        </form>                               
                                    </div>
                                </div>
                            </div>
        </div>

<!--Registration Pop up-->
<div class="modal fade" id="reg">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"> &times;</button>
                                        <h2 class="modal-title">Registration Form</h2>
                                    </div>
                                    <?php
									if ( isset($_SESSION['failure']) ) {
									echo('<p style="color: red;">'.htmlentities($_SESSION['failure'])."</p>\n");
									echo  $_SESSION['check'];
									//echo $_SESSION['type'];
									unset($_SESSION['failure']);
									}
									?>
                                    <div class="modal-body">
                                        <form name="registration" id="register" onsubmit="return validate()" action="form_send.php" method="post">
                                            
                                            <label for="name">Name</label>
                                            <input type="text" id="name" name="name" placeholder="Your name.."><br />

                                            <label for="lname">E-mail </label>
                                            <input type="text" id="emailid" name="email" placeholder="Your email id.."><br />

                                            <label for="phone">Contact No.</label>
                                            <input type="text" id="phone" name="contact_no" placeholder="Your contact number.."><br />                                   <label for="user_id">User ID</label>
                                            <input type="text" id="user_id" name="id" placeholder="Select an available user id .."><br />

											<label for="password">Password</label>
                                            <input type="password" id="password" name="pw" placeholder="Type a password.."><br />											

                                            <input type="submit" name='submit' value="Register">
                                            
                                        </form>                               
                                    </div>
                                </div>
                            </div>
        </div>

		
<script type="text/javascript">
            

            function validate()
            {
                var a=document.forms["registration"]["firstname"].value;
                var b=document.forms["registration"]["lastname"].value;
                var c=document.forms["registration"]["emailaddress"].value;
                var d=document.forms["registration"]["contact_no"].value;
                var e=document.forms["registration"]["msg"].value;
                
                if(a=="" || b==""|| c==""|| d==""|| e=="")
                    {
                        alert("Please fill all the Input fields!!");
                        return false;
                    }
            }
            
            $("#register").submit(function(event){
                
                function isEmail(email) {
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                return regex.test(email);
                }
                
                if($("#email").val()!="")
                {
                    if(!isEmail($("#email").val()))
                    {
                        alert("Enter a valid Email-Id");
                        event.preventDefault();
                    }
                }
                
            
            if($("#phone").val()!="")
                    {
                        
                        if(!$.isNumeric($("#phone").val()))
                            {
                                alert("Enter a valid Contact Number");
                                event.preventDefault();
                            }
                    }
                
            });
            
            
            
        </script>
        
        
		
		
</form>
</div>
</body>
</html>