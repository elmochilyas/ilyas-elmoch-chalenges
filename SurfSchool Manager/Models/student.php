<?php

class Student {
    // Propriétés privées (encapsulation)
    private $conn;
    private $table = 'students';
    
    // Attributs de l'étudiant
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $niveau;
    private $pays;
    
    // Constructeur
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Setters
    public function setNom($nom) {
        $this->nom = $nom;
    }
    
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function setNiveau($niveau) {
        $this->niveau = $niveau;
    }
    
    public function setPays($pays) {
        $this->pays = $pays;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getNom() {
        return $this->nom;
    }
    
    public function getPrenom() {
        return $this->prenom;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getNiveau() {
        return $this->niveau;
    }
    
    public function getPays() {
        return $this->pays;
    }
    
    // Créer un nouvel étudiant
    public function create() {
        // First, create the user in the users table
        $name = $this->nom . ' ' . $this->prenom;
        $userQuery = "INSERT INTO users (email, password, role) 
                      VALUES (:email, :password, 'student')";
        
        $userStmt = $this->conn->prepare($userQuery);
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        $userStmt->bindParam(':email', $this->email);
        $userStmt->bindParam(':password', $hashedPassword);
        
        if ($userStmt->execute()) {
            $user_id = $this->conn->lastInsertId();
            
            // Map French level names to database enum values
            $levelMap = [
                'Débutant' => 'Beginner',
                'Intermédiaire' => 'Intermediate',
                'Avancé' => 'Advanced'
            ];
            
            $dbLevel = $levelMap[$this->niveau] ?? 'Beginner';
            
            // Then, create the student record
            $studentQuery = "INSERT INTO students (user_id, name, country, level) 
                            VALUES (:user_id, :name, :country, :level)";
            
            $studentStmt = $this->conn->prepare($studentQuery);
            $studentStmt->bindParam(':user_id', $user_id);
            $studentStmt->bindParam(':name', $name);
            $studentStmt->bindParam(':country', $this->pays);
            $studentStmt->bindParam(':level', $dbLevel);
            
            if ($studentStmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return true;
            }
        }
        
        return false;
    }
    
    // Récupérer tous les étudiants
    public function getAllStudents() {
        $query = "SELECT s.* FROM " . $this->table . " s ORDER BY s.id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer un étudiant par email
    public function getByEmail($email) {
        $query = "SELECT s.*, u.password FROM " . $this->table . " s
                  INNER JOIN users u ON s.user_id = u.id
                  WHERE u.email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Vérifier le mot de passe
    public function verifyPassword($email, $password) {
        $query = "SELECT password FROM users WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && password_verify($password, $row['password'])) {
            return true;
        }
        
        return false;
    }
    
    // Récupérer un étudiant par ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->id = $row['id'];
            $this->email = $this->getEmailByStudentId($id);
            $this->niveau = $row['level'];
            $this->pays = $row['country'];
            $nameArray = explode(' ', $row['name'], 2);
            $this->nom = $nameArray[0];
            $this->prenom = $nameArray[1] ?? '';
            return true;
        }
        
        return false;
    }
    
    // Helper method to get email from user_id
    private function getEmailByStudentId($student_id) {
        $query = "SELECT u.email FROM users u
                  INNER JOIN students s ON u.id = s.user_id
                  WHERE s.id = :student_id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['email'] ?? null;
    }
    
    // Mettre à jour le niveau d'un étudiant (US3)
    public function updateNiveau($id, $nouveau_niveau) {
        // Map French level names to database enum values
        $levelMap = [
            'Débutant' => 'Beginner',
            'Intermédiaire' => 'Intermediate',
            'Avancé' => 'Advanced'
        ];
        
        $dbLevel = $levelMap[$nouveau_niveau] ?? $nouveau_niveau;
        
        $query = "UPDATE " . $this->table . " 
                  SET level = :level 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':level', $dbLevel);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // Vérifier si un email existe déjà
    public function emailExists($email) {
        $query = "SELECT id FROM users WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    // ============= MÉTHODES POUR L'AGENDA (US5) =============
    
    // Récupérer les cours d'un étudiant spécifique avec statut de paiement
    public function getMyLessons($student_id) {
        $query = "SELECT 
                    l.id as lesson_id,
                    l.title,
                    l.coach,
                    l.datetime,
                    l.price,
                    ls.pay_status
                  FROM lessons l
                  INNER JOIN lesson_student ls ON l.id = ls.lesson_id
                  WHERE ls.student_id = :student_id
                  AND l.datetime >= NOW()
                  ORDER BY l.datetime ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Compter le nombre total de cours d'un étudiant
    public function countMyLessons($student_id) {
        $query = "SELECT COUNT(*) as total
                  FROM lesson_student
                  WHERE student_id = :student_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    
    // Récupérer tous les cours passés d'un étudiant
    public function getMyPastLessons($student_id) {
        $query = "SELECT 
                    l.id as lesson_id,
                    l.title,
                    l.coach,
                    l.datetime,
                    l.price,
                    ls.pay_status
                  FROM lessons l
                  INNER JOIN lesson_student ls ON l.id = ls.lesson_id
                  WHERE ls.student_id = :student_id
                  AND l.datetime < NOW()
                  ORDER BY l.datetime DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Vérifier si un étudiant est inscrit à un cours
    public function isEnrolledInLesson($student_id, $lesson_id) {
        $query = "SELECT id FROM lesson_student 
                  WHERE student_id = :student_id AND lesson_id = :lesson_id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':lesson_id', $lesson_id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    // Compter le nombre d'étudiants
    public function countAllStudents() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}