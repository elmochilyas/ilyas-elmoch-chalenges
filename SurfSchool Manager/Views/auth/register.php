<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get messages
$errors = $_SESSION['errors'] ?? [];
$old_data = $_SESSION['old_data'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_data']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Surf School Manager</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .register-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .register-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .register-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .errors-box {
            background: #ffebee;
            border: 1px solid #ef5350;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .errors-box ul {
            list-style: none;
            padding-left: 0;
        }
        
        .errors-box li {
            color: #d32f2f;
            margin-bottom: 5px;
            font-size: 13px;
        }
        
        .btn-register {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
        }
        
        .btn-register:active {
            transform: translateY(0);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .login-link p {
            color: #666;
            font-size: 14px;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>🏄 Surf School Manager</h1>
            <p>Inscription</p>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="errors-box">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?action=register">
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input 
                        type="text" 
                        id="nom" 
                        name="nom" 
                        required
                        placeholder="Votre nom"
                        value="<?php echo htmlspecialchars($old_data['nom'] ?? ''); ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input 
                        type="text" 
                        id="prenom" 
                        name="prenom" 
                        required
                        placeholder="Votre prénom"
                        value="<?php echo htmlspecialchars($old_data['prenom'] ?? ''); ?>"
                    >
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required
                    placeholder="votre.email@example.com"
                    value="<?php echo htmlspecialchars($old_data['email'] ?? ''); ?>"
                >
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    minlength="6"
                    placeholder="Minimum 6 caractères"
                >
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="niveau">Niveau</label>
                    <select id="niveau" name="niveau" required>
                        <option value="">-- Sélectionnez un niveau --</option>
                        <option value="Débutant" <?php echo ($old_data['niveau'] ?? '') === 'Débutant' ? 'selected' : ''; ?>>Débutant</option>
                        <option value="Intermédiaire" <?php echo ($old_data['niveau'] ?? '') === 'Intermédiaire' ? 'selected' : ''; ?>>Intermédiaire</option>
                        <option value="Avancé" <?php echo ($old_data['niveau'] ?? '') === 'Avancé' ? 'selected' : ''; ?>>Avancé</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="pays">Pays</label>
                    <input 
                        type="text" 
                        id="pays" 
                        name="pays" 
                        required
                        placeholder="Votre pays"
                        value="<?php echo htmlspecialchars($old_data['pays'] ?? ''); ?>"
                    >
                </div>
            </div>
            
            <button type="submit" class="btn-register">S'inscrire</button>
        </form>
        
        <div class="login-link">
            <p>Déjà inscrit ? <a href="index.php?action=show-login">Se connecter</a></p>
        </div>
    </div>
</body>
</html>
