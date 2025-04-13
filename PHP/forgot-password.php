<?php
// Start the session to track user data, such as the shopping cart
session_start();

// Load the website structure data from the 'website_structure.xml' file
$xml = simplexml_load_file('website_structure_blsm.xml');
if (!$xml) {
    die('Error: Unable to load XML file.');
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $xml->webpages->header->title; ?> - Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="css/styleReham.css">
    
<header class="navbar">
            <!-- <a href="home.html" class="logo"> -->
                <img id="logo" src="img/Logo.png" alt="Balsam Logo">
            <!-- </a> -->
            <h1 class="spicea">BALSAM</h1>
    </header>

    <h1>Forgot Password</h1>

    <div class="container">
        <div class="card">
            <h2>Reset Password</h2>
            <!-- Change the action attribute to your home page URL -->
            <form name="f1" action="login.php" onsubmit="return validation()" method="POST">  
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email"><br>
                <!-- Add the same CSS class to your reset button as your login button -->
                <input type="submit" value="Reset Password" id="reset-button" class="login-button">
            </form>
        </div>

        <div class="card">
            <h2 style="color:#3C4C70;" >Remembered your password?</h2> 
            <a href="login.php" class="register-button" >Login here</a>
        </div>
    </div>
    <script>  
            function validation()  
            {  
                var email=document.f1.email.value;  
                if(email.length=="") {  
                    alert("Email field is empty");  
                    return false;  
                } else {
                    alert("The reset link is sent to your email");
                    return true;
                }                             
            }  
        </script> 
</body>
</html>
