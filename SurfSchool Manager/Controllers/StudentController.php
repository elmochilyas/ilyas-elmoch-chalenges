<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Student.php';

class StudentController {
    
    private $db;
    private $studentModel;
    
    public function __construct() {
        $this->db = Database::getConnection();
        $this->studentModel = new Student($this->db);
    }
    
    // ============= INSCRIPTION (US4) =============
    
    // Afficher le formulaire d'inscription
    public function showRegisterForm() {
        include __DIR__ . '/../views/auth/register.php';
    }
    
    // Traiter l'inscription d'un nouvel étudiant
    public function register() {
        // Vérifier que c'est une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=show-register");
            exit();
        }
        
        // Initialiser un tableau d'erreurs
        $errors = [];
        
        // VALIDATION DES DONNÉES
        
        // 1. Vérifier que les champs obligatoires ne sont pas vides
        if (empty($_POST['nom'])) {
            $errors[] = "Le nom est requis";
        }
        
        if (empty($_POST['prenom'])) {
            $errors[] = "Le prénom est requis";
        }
        
        if (empty($_POST['email'])) {
            $errors[] = "L'email est requis";
        } else {
            // 2. Valider le format de l'email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Format d'email invalide";
            } else {
                // 3. Vérifier si l'email existe déjà
                if ($this->studentModel->emailExists($_POST['email'])) {
                    $errors[] = "Cet email est déjà utilisé";
                }
            }
        }
        
        if (empty($_POST['niveau'])) {
            $errors[] = "Le niveau est requis";
        } else {
            // 4. Vérifier que le niveau est valide
            $niveaux_valides = ['Débutant', 'Intermédiaire', 'Avancé'];
            if (!in_array($_POST['niveau'], $niveaux_valides)) {
                $errors[] = "Niveau invalide";
            }
        }
        
        if (empty($_POST['pays'])) {
            $errors[] = "Le pays est requis";
        }
        
        if (empty($_POST['password'])) {
            $errors[] = "Le mot de passe est requis";
        } else if (strlen($_POST['password']) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
        }
        
        // S'il y a des erreurs, retourner au formulaire
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            header("Location: index.php?action=show-register");
            exit();
        }
        
        // NETTOYER LES DONNÉES (Protection XSS)
        $nom = htmlspecialchars(strip_tags(trim($_POST['nom'])));
        $prenom = htmlspecialchars(strip_tags(trim($_POST['prenom'])));
        $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
        $password = $_POST['password'];
        $niveau = htmlspecialchars(strip_tags($_POST['niveau']));
        $pays = htmlspecialchars(strip_tags(trim($_POST['pays'])));
        
        // UTILISER LE MODÈLE
        $this->studentModel->setNom($nom);
        $this->studentModel->setPrenom($prenom);
        $this->studentModel->setEmail($email);
        $this->studentModel->setPassword($password);
        $this->studentModel->setNiveau($niveau);
        $this->studentModel->setPays($pays);
        
        // SAUVEGARDER EN BASE
        try {
            if ($this->studentModel->create()) {
                // Connexion automatique après inscription
                $_SESSION['student_id'] = $this->studentModel->getId();
                $_SESSION['student_nom'] = $nom;
                $_SESSION['student_prenom'] = $prenom;
                $_SESSION['student_email'] = $email;
                
                $_SESSION['success'] = "Inscription réussie ! Bienvenue " . $prenom . " !";
                header("Location: index.php?action=agenda");
                exit();
            } else {
                $errors[] = "Erreur lors de l'inscription. Veuillez réessayer.";
                $_SESSION['errors'] = $errors;
                header("Location: index.php?action=show-register");
                exit();
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur de base de données : " . $e->getMessage();
            $_SESSION['errors'] = $errors;
            header("Location: index.php?action=show-register");
            exit();
        }
    }
    
    // ============= AGENDA SURFEUR (US5) =============
    
    // Afficher l'agenda du surfeur
    public function showAgenda() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['student_id'])) {
            $_SESSION['errors'] = ["Vous devez être connecté pour voir votre agenda"];
            header("Location: index.php?action=show-login");
            exit();
        }
        
        $student_id = $_SESSION['student_id'];
        
        // Récupérer les informations de l'étudiant
        if (!$this->studentModel->getById($student_id)) {
            $_SESSION['errors'] = ["Étudiant non trouvé"];
            header("Location: index.php?action=logout");
            exit();
        }
        
        $student = [
            'id' => $this->studentModel->getId(),
            'nom' => $this->studentModel->getNom(),
            'prenom' => $this->studentModel->getPrenom(),
            'niveau' => $this->studentModel->getNiveau(),
            'email' => $this->studentModel->getEmail(),
            'pays' => $this->studentModel->getPays()
        ];
        
        // Récupérer les cours de l'étudiant
        $lessons = $this->studentModel->getMyLessons($student_id);
        $total_lessons = $this->studentModel->countMyLessons($student_id);
        
        // Calculer les statistiques
        $lessons_payed = 0;
        $lessons_pending = 0;
        
        foreach ($lessons as $lesson) {
            if ($lesson['payment_status'] === 'Payé') {
                $lessons_payed++;
            } else {
                $lessons_pending++;
            }
        }
        
        // Inclure la vue
        include __DIR__ . '/../views/student/agenda.php';
    }
    
    // ============= GESTION GÉRANT (US1, US3) =============
    
    // Afficher tous les étudiants (pour le dashboard gérant)
    public function listStudents() {
        // Vérifier que c'est un gérant
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'gerant') {
            $_SESSION['errors'] = ["Accès non autorisé"];
            header("Location: index.php?action=home");
            exit();
        }
        
        $students = $this->studentModel->getAllStudents();
        include __DIR__ . '/../views/gerant/students_list.php';
    }
    
    // Afficher le formulaire de modification de niveau
    public function showUpdateLevelForm($id) {
        // Vérifier que c'est un gérant
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'gerant') {
            $_SESSION['errors'] = ["Accès non autorisé"];
            header("Location: index.php?action=home");
            exit();
        }
        
        if ($this->studentModel->getById($id)) {
            $student = [
                'id' => $this->studentModel->getId(),
                'nom' => $this->studentModel->getNom(),
                'prenom' => $this->studentModel->getPrenom(),
                'niveau' => $this->studentModel->getNiveau(),
                'email' => $this->studentModel->getEmail()
            ];
            include __DIR__ . '/../views/gerant/update_level.php';
        } else {
            $_SESSION['errors'] = ["Étudiant non trouvé"];
            header("Location: index.php?action=dashboard");
            exit();
        }
    }
    
    // Mettre à jour le niveau d'un étudiant (US3)
    public function updateLevel() {
        // Vérifier que c'est un gérant
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'gerant') {
            $_SESSION['errors'] = ["Accès non autorisé"];
            header("Location: index.php?action=home");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=dashboard");
            exit();
        }
        
        $id = $_POST['student_id'] ?? null;
        $nouveau_niveau = $_POST['niveau'] ?? null;
        
        $niveaux_valides = ['Débutant', 'Intermédiaire', 'Avancé'];
        
        if (!$id || !$nouveau_niveau || !in_array($nouveau_niveau, $niveaux_valides)) {
            $_SESSION['errors'] = ["Données invalides"];
            header("Location: index.php?action=dashboard");
            exit();
        }
        
        if ($this->studentModel->updateNiveau($id, $nouveau_niveau)) {
            $_SESSION['success'] = "Niveau mis à jour avec succès";
        } else {
            $_SESSION['errors'] = ["Erreur lors de la mise à jour"];
        }
        
        header("Location: index.php?action=list-students");
        exit();
    }
    
    // Connexion simple pour étudiant (version simplifiée)
    public function showLoginForm() {
        include __DIR__ . '/../views/auth/login.php';
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=show-login");
            exit();
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email)) {
            $_SESSION['errors'] = ["L'email est requis"];
            header("Location: index.php?action=show-login");
            exit();
        }
        
        if (empty($password)) {
            $_SESSION['errors'] = ["Le mot de passe est requis"];
            header("Location: index.php?action=show-login");
            exit();
        }
        
        if ($this->studentModel->verifyPassword($email, $password)) {
            $student = $this->studentModel->getByEmail($email);
            
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_nom'] = $student['name'];
            $_SESSION['student_email'] = $email;
            $_SESSION['user_role'] = 'student';
            
            $_SESSION['success'] = "Connexion réussie !";
            header("Location: index.php?action=agenda");
            exit();
        } else {
            $_SESSION['errors'] = ["Email ou mot de passe incorrect"];
            header("Location: index.php?action=show-login");
            exit();
        }
    }
    
    public function logout() {
        session_destroy();
        header("Location: index.php?action=home");
        exit();
    }
}