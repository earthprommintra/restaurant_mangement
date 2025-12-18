<?php
include "db.php";
if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "select * from users where email = '$email'";
    $result = mysqli_query($conn,$sql);
    if(!$result){
        echo"Error!:{$conn->error}";
    }
    else{
        if($result->num_rows>0){
            $row = mysqli_fetch_assoc($result);
            if($row['password'] == $password){
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_type'] = $row['type'];
                if($_SESSION['user_id']){
                    if($_SESSION['user_type'] == "admin"){
                        header("Location: admin/admin_dashboard.php");
                        exit();
                    }
                    if($_SESSION['user_type'] == "user"){
                        header("Location: user_dashboard.php");
                        exit();
                    }
                }
            }
            else{
                $error_message = "Password is incorrect!";
            }
        } else {
            $error_message = "Email not found!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 400px;
        }

        .form-container h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 2em;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #f5c6cb;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .login-button {
            width: 100%;
            padding: 14px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-button:hover {
            background-color: #5568d3;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #2c3e50;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        
        <?php if(isset($error_message)){ ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>

        <form action="login.php" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" name="submit" class="login-button">Log In</button>
            
            <p class="register-link">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        </form>
    </div>
</body>
</html>