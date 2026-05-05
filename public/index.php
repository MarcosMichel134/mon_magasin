<?php
// public/index.php - Routeur principal

session_start();

require_once '../config/db.php';

// Autoload des modèles
spl_autoload_register(function($class) {
    $file = '../models/' . $class . '.php';
    if(file_exists($file)) require_once $file;
});

$action = $_GET['action'] ?? 'login';

switch($action) {
    case 'login':
        // Gestion connexion
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ? AND mot_de_passe != ''");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if($user && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                header('Location: index.php?action=accueil');
            } else {
                $error = "Email ou mot de passe incorrect";
                include '../views/login.php';
            }
        } else {
            include '../views/login.php';
        }
        break;
        
    case 'inscription':
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        if($password !== $confirm_password) {
            $error = "Les mots de passe ne correspondent pas";
            include '../views/inscription.php';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO clients (nom, email, mot_de_passe, date_inscription) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$nom, $email, $password_hash]);
            header('Location: index.php?action=login');
        }
    } else {
        include '../views/inscription.php';
    }
    break;
        
    case 'accueil':
        include '../views/accueil.php';
        break;
        
    case 'articles':
        include '../views/articles/liste.php';
        break;
        
    case 'ventes':
        include '../views/ventes/liste.php';
        break;
        
    case 'effectuer_vente':
        include '../views/ventes/effectuer.php';
        break;
        
    case 'clients':
        // Liste des clients (tous, y compris ceux sans mot de passe)
        $stmt = $pdo->query("SELECT * FROM clients ORDER BY id");
        $clients = $stmt->fetchAll();
        include '../views/clients/liste.php';
        break;
        
    case 'utilisateurs':
        // Liste des utilisateurs avec compte (ceux qui ont un mot de passe)
        $stmt = $pdo->query("SELECT * FROM clients WHERE mot_de_passe != '' ORDER BY id");
        $utilisateurs = $stmt->fetchAll();
        include '../views/utilisateurs/liste.php';
        break;
        
    case 'deconnexion':
        session_destroy();
        header('Location: index.php?action=login');
        break;
        
    default:
        header('Location: index.php?action=login');
}
?>