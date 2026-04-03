<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check authentication
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php?action=show-login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Niveau - Taghazout Surf Expo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            color: #333;
            font-size: 24px;
        }
        
        .btn-logout {
            background: #d32f2f;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
        }
        
        .btn-logout:hover {
            background: #b71c1c;
        }
        
        .content {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .level-display {
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
            margin: 30px 0;
        }
        
        .description {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin: 20px 0;
        }
        
        .nav-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏄 Mon Niveau</h1>
            <a href="index.php?action=logout" class="btn-logout">Déconnexion</a>
        </div>
        
        <div class="content">
            <h2>Votre Niveau Actuel</h2>
            <div class="level-display">
                Débutant
            </div>
            
            <div class="description">
                <p>Vous êtes actuellement au niveau <strong>Débutant</strong> en surf.</p>
                <p>Continuez à pratiquer pour progresser vers le niveau intermédiaire!</p>
            </div>
            
            <div class="nav-buttons">
                <a href="index.php?action=agenda" class="btn btn-primary">Voir mon agenda</a>
                <a href="index.php?action=show-login" class="btn btn-secondary">Retour</a>
            </div>
        </div>
    </div>
</body>
</html>
