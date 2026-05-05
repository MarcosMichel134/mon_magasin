<?php
// views/articles/liste.php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    if($action === 'ajouter') {
        $stmt = $pdo->prepare("INSERT INTO articles (nom, quantite, categorie, prix) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['nom'], $_POST['quantite'], $_POST['categorie'], $_POST['prix']]);
        header('Location: index.php?action=articles');
        exit;
    } elseif($action === 'modifier') {
        $stmt = $pdo->prepare("UPDATE articles SET nom=?, quantite=?, categorie=?, prix=? WHERE id=?");
        $stmt->execute([$_POST['nom'], $_POST['quantite'], $_POST['categorie'], $_POST['prix'], $_POST['id']]);
        header('Location: index.php?action=articles');
        exit;
    } elseif($action === 'supprimer') {
        $stmt = $pdo->prepare("DELETE FROM articles WHERE id=?");
        $stmt->execute([$_POST['id']]);
        header('Location: index.php?action=articles');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Articles - Gestion de stock</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Playfair Display", serif; }
        body { 
            /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;  */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container { 
            max-width: 1200px; 
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
            border-left: 5px solid #667eea;
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
            min-width: 120px;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            font-weight: 600;
        }
        tr {
            transition: background 0.2s;
        }
        tr:hover {
            background: #f8f9fa;
        }
        td {
            border-bottom: 1px solid #dee2e6;
        }
        .btn-ajouter { 
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white; 
        }
        .btn-modifier { 
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #333;
        }
        .btn-supprimer { 
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 6px 12px;
            font-size: 12px;
        }
        .btn-quitter { 
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            padding: 10px 25px;
            margin-top: 10px;
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
        a {
            text-decoration: none;
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
    <h2>Gestion des articles</h2>
    
    <form method="POST">
        <input type="hidden" name="id" id="article_id">
        <input type="text" name="nom" id="nom" placeholder="Nom de l'article" required>
        <input type="number" name="quantite" id="quantite" placeholder="Quantité" required>
        <input type="text" name="categorie" id="categorie" placeholder="Catégorie">
        <input type="number" step="0.01" name="prix" id="prix" placeholder="Prix (FCFA)" required>
        <button type="submit" name="action" value="ajouter" class="btn-ajouter">Ajouter</button>
        <button type="submit" name="action" value="modifier" class="btn-modifier">Modifier</button>
    </form>
    
    <table>
        <thead>
            <tr><th>ID</th><th>Nom</th><th>Qté</th><th>Catégorie</th><th>Prix (FCFA)</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM articles ORDER BY id");
            $articles = $stmt->fetchAll();
            foreach($articles as $article):
            ?>
            <tr>
                <td><?= $article['id'] ?></td>
                <td><?= htmlspecialchars($article['nom']) ?></td>
                <td><?= number_format($article['quantite'], 0, ',', ' ') ?></td>
                <td><?= htmlspecialchars($article['categorie']) ?></td>
                <td><?= number_format($article['prix'], 0, ',', ' ') ?> FCFA</td>
                <td>
                    <div class="action-buttons">
                        <button onclick="edit(<?= $article['id'] ?>, '<?= htmlspecialchars($article['nom']) ?>', <?= $article['quantite'] ?>, '<?= htmlspecialchars($article['categorie']) ?>', <?= $article['prix'] ?>)" class="btn-modifier">Modifier</button>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="id" value="<?= $article['id'] ?>">
                            <button type="submit" name="action" value="supprimer" class="btn-supprimer" onclick="return confirm('Supprimer cet article ?')">Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <a href="index.php?action=accueil"><button class="btn-quitter">Retour accueil</button></a>
</div>

<script>
function edit(id, nom, quantite, categorie, prix) {
    document.getElementById('article_id').value = id;
    document.getElementById('nom').value = nom;
    document.getElementById('quantite').value = quantite;
    document.getElementById('categorie').value = categorie;
    document.getElementById('prix').value = prix;
}
</script>
</body>
</html>
