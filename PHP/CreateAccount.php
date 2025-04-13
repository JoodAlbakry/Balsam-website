<!DOCTYPE html>
<html>
<head>
    <title>Registration page</title>
    <link rel="stylesheet" type="text/css" href="css/styleReham.css">
</head>
<body>
<header class="navbar">
            <!-- <a href="home.html" class="logo"> -->
                <img id="logo" src="img/Logo.png" alt="Balsam Logo">
            <!-- </a> -->
            <h1 class="spicea">BALSAM</h1>
    </header>
    <br><br><br><br>


    <div class="container">
        
        <div class="card">
            <h2>Create Account:</h2>
            <?php
            error_reporting(E_ALL ^ E_WARNING);

            ?>
            <form name="f1" action="account.php" onsubmit="return validation()" method="POST">  
                <label for="fullname">Full Name:</label><br>
                <input type="text" id="fullname" name="fullname"><br>
                <label for="phonenumber">Phone Number:</label><br>
                <input type="tel" id="phonenumber" name="phonenumber"><br>
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username"><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password"><br>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email"><br>
                <label for="status">Status:</label><br>
                <select name="status" required>
                    <option value="Guest">Guest</option>
                    <option value="Seller">Seller</option>
                    <option value="Donor">Donor</option>
                    <option value="Vendors">Vendors</option>
                </select> <br>
                <input type="submit" value="Register" id="register-button">
            </form>
        </div>
    </div>
    
        <script>  
        function validation() {  
            var fullname = document.f1.fullname.value;  
            var phonenumber = document.f1.phonenumber.value;  
            var username = document.f1.username.value;  
            var password = document.f1.password.value;  
            var email = document.f1.email.value;  

            // Fullname validation
            if (fullname.length == "" || fullname.length > 50) {  
                alert("Full name is required and must not exceed 50 characters.");  
                return false;  
            }

            // Phone number validation
            if (!/^05\d{8}$/.test(phonenumber)) {
                alert("Phone number must start with 05 and have 10 digits.");  
                return false;  
            }

            // Username validation
            if (username.length == "" || username.length > 50) {  
                alert("Username is required and must not exceed 50 characters.");  
                return false;  
            }

            // Password validation
            if (password.length < 8) {  
                alert("Password must be at least 8 characters long.");  
                return false;  
            }

            // Email validation
            if (!/^\S+@\S+\.\S+$/.test(email)) {
                alert("Please enter a valid email address.");  
                return false;  
            }

            return true;
        }  
        </script>

</body>
</html>
