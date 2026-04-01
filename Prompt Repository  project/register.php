<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        session_start();
        require "db.php";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            if (empty($username) || empty($email) || empty($password))
                 { $error = "All fields are required"; } 
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
                { $error = "Invalid email format"; } 
            else {  
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                try {
                    $stmt->execute([$username, $email, $hash]);
                    $_SESSION['user_id'] = $pdo->lastInsertId();
                    header("Location: index.php");
                    exit;
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $error = "Email already registered";
                    } else {
                        $error = "Database error: " . $e->getMessage();
                    }
                }
            }
            
        }
    ?>
    <div class="register-container">
        <h2>Create Account</h2>
        <form method="post">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required />
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required />
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required />
            </div>
            <button type="submit">Register</button>
        </form>
        <?php if(!empty($error)) echo "<p class=\"error\">$error</p>"; ?>
        <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
