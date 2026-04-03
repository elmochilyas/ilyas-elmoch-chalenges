<?php
// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier l'authentification
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php?action=show-login");
    exit();
}

// Récupérer les messages
$success = $_SESSION['success'] ?? null;
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['success'], $_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Agenda - Taghazout Surf Expo</title>
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
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .header-info h1 {
            color: #333;
            margin-bottom: 8px;
            font-size: 28px;
        }
        
        .header-info p {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .profile-badge {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .country-flag {
            margin-left: 5px;
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 13px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .stat-card .number {
            font-size: 42px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-card .label {
            font-size: 12px;
            color: #999;
        }
        
        .stat-card.total .number {
            color: #667eea;
        }
        
        .stat-card.upcoming .number {
            color: #3498db;
        }
        
        .stat-card.paid .number {
            color: #27ae60;
        }
        
        .stat-card.pending .number {
            color: #e67e22;
        }
        
        .success-box {
            background: #d4edda;
            border-left: 5px solid #28a745;
            color: #155724;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .success-box::before {
            content: "✓";
            font-size: 24px;
            font-weight: bold;
        }
        
        .error-box {
            background: #f8d7da;
            border-left: 5px solid #dc3545;
            color: #721c24;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .error-box ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .error-box li {
            margin-bottom: 5px;
        }
        
        .error-box li::before {
            content: "⚠ ";
            margin-right: 5px;
        }
        
        .lessons-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .lessons-section h2 {
            color: #333;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
        }
        
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #999;
        }
        
        .empty-state-icon {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        
        .empty-state h3 {
            font-size: 22px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            font-size: 16px;
            margin-bottom: 25px;
            color: #999;
        }
        
        .empty-state a {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .empty-state a:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        
        .lesson-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-left: 5px solid #667eea;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 10px;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .lesson-card:hover {
            transform: translateX(8px);
            box-shadow: 0 5px 25px rgba(102, 126, 234, 0.15);
        }
        
        .lesson-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .lesson-title {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        
        .lesson-coach {
            color: #666;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .payment-badge {
            padding: 8px 18px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        
        .payment-badge.paid {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 2px solid #28a745;
        }
        
        .payment-badge.pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border: 2px solid #ffc107;
        }
        
        .lesson-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .lesson-detail {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            font-size: 14px;
            padding: 8px;
            background: white;
            border-radius: 8px;
        }
        
        .lesson-detail-icon {
            font-size: 20px;
            min-width: 25px;
            text-align: center;
        }
        
        .lesson-detail strong {
            color: #333;
            margin-right: 5px;
        }
        
        .no-lessons-yet {
            background: #e3f2fd;
            border-left: 5px solid #2196f3;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .no-lessons-yet h4 {
            color: #1565c0;
            margin-bottom: 10px;
        }
        
        .no-lessons-yet p {
            color: #1976d2;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }
            
            .header-info h1 {
                font-size: 22px;
            }
            
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .lesson-header {
                flex-direction: column;
            }
            
            .lesson-details {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .stats {
                grid-template-columns: 1fr;
            }
            
            body {
                padding: 10px;
            }
            
            .container {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-info">
                <h1>🏄‍♂️ Bienvenue, <?php echo htmlspecialchars($student['prenom'] . ' ' . $student['nom']); ?></h1>
                <p>
                    📧 <?php echo htmlspecialchars($student['email']); ?>
                    <span class="profile-badge"><?php echo htmlspecialchars($student['niveau']); ?></span>
                </p>
                <p style="margin-top: 5px;">
                    🌍 <?php echo htmlspecialchars($student['pays']); ?>
                </p>
            </div>
            <a href="index.php?action=logout" class="logout-btn">🚪 Déconnexion</a>
        </div>
        
        <!-- Messages -->
        <?php if ($success): ?>
            <div class="success-box">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <!-- Statistiques -->
        <div class="stats">
            <div class="stat-card total">
                <h3>📊 Total de cours</h3>
                <div class="number"><?php echo $total_lessons; ?></div>
                <div class="label">Toutes sessions</div>
            </div>
            <div class="stat-card upcoming">
                <h3>⏰ Cours à venir</h3>
                <div class="number"><?php echo count($lessons); ?></div>
                <div class="label">Sessions planifiées</div>
            </div>
            <div class="stat-card paid">
                <h3>✅ Cours payés</h3>
                <div class="number"><?php echo $lessons_payed; ?></div>
                <div class="label">Paiements confirmés</div>
            </div>
            <div class="stat-card pending">
                <h3>⏳ En attente</h3>
                <div class="number"><?php echo $lessons_pending; ?></div>
                <div class="label">À régler</div>
            </div>
        </div>
        
        <!-- Liste des cours -->
        <div class="lessons-section">
            <h2>📅 Mes Prochaines Sessions de Surf</h2>
            
            <?php if (empty($lessons)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">🌊</div>
                    <h3>Aucun cours prévu</h3>
                    <p>Vous n'avez pas encore de sessions de surf planifiées</p>
                    <p style="font-size: 14px; color: #999; margin-bottom: 25px;">
                        Contactez le gérant de l'école pour vous inscrire à une session !
                    </p>
                    <a href="index.php?action=home">← Retour à l'accueil</a>
                </div>
            <?php else: ?>
                <?php foreach ($lessons as $lesson): ?>
                    <?php
                    $date = new DateTime($lesson['date_heure']);
                    $now = new DateTime();
                    $diff = $now->diff($date);
                    $days_until = $diff->days;
                    ?>
                    <div class="lesson-card">
                        <div class="lesson-header">
                            <div>
                                <div class="lesson-title">
                                    <?php echo htmlspecialchars($lesson['titre']); ?>
                                </div>
                                <div class="lesson-coach">
                                    👨‍🏫 Coach: <strong><?php echo htmlspecialchars($lesson['coach']); ?></strong>
                                </div>
                                <?php if ($days_until <= 3): ?>
                                    <div style="margin-top: 8px; color: #e74c3c; font-weight: 600; font-size: 13px;">
                                        🔥 Dans <?php echo $days_until; ?> jour<?php echo $days_until > 1 ? 's' : ''; ?> !
                                    </div>
                                <?php endif; ?>
                            </div>
                            <span class="payment-badge <?php echo $lesson['payment_status'] === 'Payé' ? 'paid' : 'pending'; ?>">
                                <?php echo $lesson['payment_status'] === 'Payé' ? '✓ Payé' : '⏳ En attente'; ?>
                            </span>
                        </div>
                        
                        <div class="lesson-details">
                            <div class="lesson-detail">
                                <span class="lesson-detail-icon">📅</span>
                                <span>
                                    <strong>Date:</strong> 
                                    <?php echo $date->format('d/m/Y'); ?>
                                </span>
                            </div>
                            <div class="lesson-detail">
                                <span class="lesson-detail-icon">🕐</span>
                                <span>
                                    <strong>Heure:</strong> 
                                    <?php echo $date->format('H:i'); ?>
                                </span>
                            </div>
                            <div class="lesson-detail">
                                <span class="lesson-detail-icon">⏱️</span>
                                <span>
                                    <strong>Durée:</strong> 
                                    <?php echo htmlspecialchars($lesson['duree'] ?? '2h'); ?>
                                </span>
                            </div>
                            <div class="lesson-detail">
                                <span class="lesson-detail-icon">👥</span>
                                <span>
                                    <strong>Max:</strong> 
                                    <?php echo htmlspecialchars($lesson['capacite_max']); ?> personnes
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if (count($lessons) > 0): ?>
                    <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; text-align: center;">
                        <p style="color: #666; margin: 0;">
                            💡 <strong>Astuce:</strong> Préparez votre équipement 24h avant chaque session !
                        </p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>