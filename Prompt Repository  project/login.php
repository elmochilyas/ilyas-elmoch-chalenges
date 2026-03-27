<?php
        session_start();
        require "db.php";
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            
            
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password'])) {

                // store session data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                // redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: index.php");
                }

                exit;
            }
            else {$error = "Invalid username or password";}  
        }
        
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
    
    <form method="post">
        <div class="form-group">
            <input type="text" name="username" placeholder="Username" required />
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required />
        </div>
        <button type="submit">Login</button>
    </form>
    
    <?php if(!empty($error)) echo "<p class=\"error\">$error</p>"; ?>
    <p class="login-link">You dont have an account ? <a href="register.php">REGISTER</a></p>
    </div>
</body>
</html>
