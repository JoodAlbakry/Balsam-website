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
    <title><?php echo $xml->webpages->header->title; ?> - Log-in</title>
    <link rel="stylesheet" type="text/css" href="css/styleJawaher.css">
</head>
<body>
 
    <header class="navbar">
            <!-- <a href="home.html" class="logo"> -->
                <img id="logo" src="img/Logo.png" alt="Balsam Logo">
            <!-- </a> -->
            <h1 class="spicea">BALSAM</h1>

    </header>

    <div class="container">
        <div class="card">
            <h2>Login</h2>
            <?php
            error_reporting(E_ALL ^ E_WARNING);

            if (isset($_GET["error"]) && $_GET["error"] == 'passwordError'){
                echo '<p style="color:red"> 
                    Login failed, Please check your user name or password.
                    </p>';
            }
            ?>
            
            <form name="f1" action="doLogin.php" method="post" onsubmit="return validation()"  > 
                <label for="username">User Name:</label>
                <input type="text" id="username" name="user"><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="pass"><br>
                <input type="submit" value="Login" id="login-button">
            </form>
            <br>
            <a style="color:#6096B4;" href="forgot-password.php">Forgot your password? click here</a>
        </div>
        

        <div class="card">
            <h2>Don't have an account?</h2>
            <a href="CreateAccount.php" class="register-button">Register here</a>
        </div>
    </div>
    <script>  
            function validation()  
            {  
                var id=document.f1.user.value;  
                var ps=document.f1.pass.value;  
                if(id.length=="" && ps.length=="") {  
                    alert("User Name and Password fields are empty");  
                    return false;  
                }  
                else  
                {  
                    if(id.length=="") {  
                        alert("User Name is empty");  
                        return false;  
                    }   
                    if (ps.length=="") {  
                    alert("Password field is empty");  
                    return false;  
                    }  
                }                             
            }  
    </script> 
</body>
</html>