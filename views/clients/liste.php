<!-- views/clients/liste.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Clients - Gestion</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Playfair Display", serif;}
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
            border-left: 5px solid #17a2b8;
            padding-left: 15px;
        }
        form { 
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px; 
            border-radius: 10px; 
            margin-bottom: 25px; 
            display: flex; 
            flex-wrap: wrap; 
            gap: 12px; 
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        input { 
            padding: 10px 12px; 
            border: 2px solid #dee2e6; 
            border-radius: 8px; 
            font-size: 14px;
            transition: all 0.3s;
            flex: 1;
            min-width: 150px;
        }
        input:focus {
            outline: none;
            border-color: #17a2b8;
            box-shadow: 0 0 0 3px rgba(23,162,184,0.1);
        }
        button { 
            padding: 10px 20px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
        }
        button:hover { 
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .btn-ajouter { 
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white; 
        }
        .btn-supprimer { 
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 6px 12px;
            font-size: 12px;
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
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white; 
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
        .error { 
            color: #721c24; 
            background: #f8d7da; 
            padding: 12px; 
            border-radius: 8px; 
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        .success { 
            color: #155724; 
            background: #d4edda; 
            padding: 12px; 
            border-radius: 8px; 
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
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
            form { flex-direction: column; }
            input, button { width: 100%; }
            table { font-size: 12px; }
            th, td { padding: 8px; }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Gestion des clients</h2>
    
    <?php
    // Traitement ajout/suppression
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['action_ajout'])) {
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $date_enregistrement = $_POST['date_enregistrement'];
            
            // Vérifier si email existe déjà
            $check = $pdo->prepare("SELECT id FROM clients WHERE email = ?");
            $check->execute([$email]);
            if(!$check->fetch()) {
                $stmt = $pdo->prepare("INSERT INTO clients (nom, email, date_inscription, mot_de_passe) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nom, $email, $date_enregistrement, '']);
                $success = "Client ajouté avec succès";
            } else {
                $error = "Cet email existe déjà";
            }
        }
        
        if(isset($_POST['action_supprimer'])) {
            $id = $_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Client supprimé";
        }
        
        // Rafraîchir la liste
        $clients = $pdo->query("SELECT * FROM clients ORDER BY id")->fetchAll();
    } else {
        $clients = $pdo->query("SELECT * FROM clients ORDER BY id")->fetchAll();
    }
    ?>
    
    <?php if(isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <?php if(isset($success)): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>
    
    <!-- Formulaire ajout client (manuel uniquement) -->
    <form method="POST">
        <input type="text" name="nom" placeholder="Nom complet" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="date" name="date_enregistrement" required>
        <button type="submit" name="action_ajout" value="1" class="btn-ajouter">Ajouter client</button>
    </form>
    
    <!-- Tableau des clients -->
    <table>
        <thead>
            <tr><th>ID</th><th>Nom</th><th>Email</th><th>Date inscription</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php foreach($clients as $client): ?>
            <tr>
                <td><?= $client['id'] ?></td>
                <td><?= htmlspecialchars($client['nom']) ?></td>
                <td><?= htmlspecialchars($client['email']) ?></td>
                <td><?= date('d/m/Y', strtotime($client['date_inscription'])) ?></td>
                <td>
                    <div class="action-buttons">
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="id" value="<?= $client['id'] ?>">
                            <button type="submit" name="action_supprimer" value="1" class="btn-supprimer" onclick="return confirm('Supprimer ce client ?')">Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <a href="index.php?action=accueil" class="btn-retour">Retour accueil</a>
</div>
</body>
</html>
