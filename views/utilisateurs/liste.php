<!-- views/utilisateurs/liste.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Utilisateurs - Comptes actifs</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Playfair Display", serif; }
        body { 
            /* font-family: "Playfair Display", serif;  */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container { 
            max-width: 1000px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h2 { 
            margin-bottom: 25px; 
            color: #333;
            font-size: 28px;
            border-left: 5px solid #ffc107;
            padding-left: 15px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 25px 0; 
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th, td { 
            padding: 12px 15px; 
            text-align: left; 
        }
        th { 
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #333; 
            font-weight: 600;
        }
        tr {
            transition: background 0.2s;
            border-bottom: 1px solid #dee2e6;
        }
        tr:hover {
            background: #f8f9fa;
        }
        td {
            border-bottom: 1px solid #dee2e6;
        }
        .badge {
            background: #28a745;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
        }
        .btn-retour { 
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white; 
            padding: 10px 25px; 
            border-radius: 8px; 
            text-decoration: none; 
            display: inline-block;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-retour:hover { 
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .btn-supprimer { 
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
        }
        .btn-supprimer:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(220,53,69,0.3);
        }
        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .action-buttons form {
            margin: 0;
            padding: 0;
            background: none;
            box-shadow: none;
            display: inline;
        }
        @media (max-width: 768px) {
            .container { padding: 15px; }
            table { font-size: 12px; }
            th, td { padding: 8px; }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Utilisateurs avec compte</h2>
    
    <?php
    // Traitement suppression utilisateur
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_supprimer'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ? AND mot_de_passe != ''");
        $stmt->execute([$id]);
        $success = "Utilisateur supprimé";
        
        // Rafraîchir la liste
        $utilisateurs = $pdo->query("SELECT * FROM clients WHERE mot_de_passe != '' ORDER BY id")->fetchAll();
    }
    ?>
    
    <?php if(isset($success)): ?>
        <div class="success" style="color:#155724; background:#d4edda; padding:12px; border-radius:8px; margin-bottom:20px; border-left:4px solid #28a745;">
            <?= $success ?>
        </div>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Date inscription</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($utilisateurs as $utilisateur): ?>
            <tr>
                <td><?= $utilisateur['id'] ?></td>
                <td><?= htmlspecialchars($utilisateur['nom']) ?></td>
                <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                <td><?= date('d/m/Y', strtotime($utilisateur['date_inscription'])) ?></td>
                <td><span class="badge">Actif</span></td>
                <td>
                    <div class="action-buttons">
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="id" value="<?= $utilisateur['id'] ?>">
                            <button type="submit" name="action_supprimer" value="1" class="btn-supprimer" onclick="return confirm('Supprimer cet utilisateur ? Il ne pourra plus se connecter.')">Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- <p style="margin: 15px 0; color: #666;">
        Seuls les utilisateurs avec mot de passe (inscrits via formulaire) apparaissent ici.
    </p> -->
    
    <a href="index.php?action=accueil" class="btn-retour">Retour accueil</a>
</div>
</body>
</html>
