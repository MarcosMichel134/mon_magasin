<?php
// views/ventes/effectuer.php
$clients = $pdo->query("SELECT * FROM clients ORDER BY nom")->fetchAll();
$articles = $pdo->query("SELECT * FROM articles WHERE quantite > 0 ORDER BY nom")->fetchAll();

if($_SERVER['REQUEST_METHOD'] === 'POST'):
    $client_id = $_POST['client_id'];
    $articles_vente = $_POST['articles'];
    $total = 0;
    $details = [];
    $error = null;
    
    foreach($articles_vente as $article_id => $quantite):
        if($quantite > 0):
            $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
            $stmt->execute([$article_id]);
            $article = $stmt->fetch();
            if($article && $article['quantite'] >= $quantite):
                $total += $article['prix'] * $quantite;
                $details[] = ['id' => $article_id, 'quantite' => $quantite, 'prix' => $article['prix']];
            else:
                $error = "Stock insuffisant pour : " . $article['nom'];
            endif;
        endif;
    endforeach;
    
    if(!$error && $total > 0):
        $stmt = $pdo->prepare("INSERT INTO ventes (client_id, montant_total) VALUES (?, ?)");
        $stmt->execute([$client_id, $total]);
        $vente_id = $pdo->lastInsertId();
        
        foreach($details as $detail):
            $stmt = $pdo->prepare("INSERT INTO details_ventes (vente_id, article_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
            $stmt->execute([$vente_id, $detail['id'], $detail['quantite'], $detail['prix']]);
            $stmt = $pdo->prepare("UPDATE articles SET quantite = quantite - ? WHERE id = ?");
            $stmt->execute([$detail['quantite'], $detail['id']]);
        endforeach;
        
        header('Location: index.php?action=ventes');
        exit;
    endif;
endif;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Effectuer vente</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Playfair Display", serif; }
        body { 
            /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;  */
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
            border-left: 5px solid #28a745;
            padding-left: 15px;
        }
        h3 {
            margin: 20px 0 15px 0;
            color: #555;
            font-size: 20px;
        }
        .label { 
            font-weight: bold; 
            margin-top: 15px; 
            margin-bottom: 5px;
            display: block;
            color: #333;
        }
        select, input[type="text"], input[type="number"] { 
            padding: 10px 12px; 
            margin: 5px 0 15px 0; 
            border: 2px solid #dee2e6; 
            border-radius: 8px; 
            width: 100%;
            font-size: 14px;
            transition: all 0.3s;
        }
        select:focus, input:focus {
            outline: none;
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40,167,69,0.1);
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th, td { 
            padding: 12px 15px; 
            text-align: left; 
        }
        th { 
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
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
        input[type="number"] { 
            width: 90px; 
            text-align: center;
            margin: 0;
            padding: 8px;
        }
        button { 
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white; 
            padding: 12px 30px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
            margin-top: 10px;
        }
        button:hover { 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40,167,69,0.3);
        }
        .error { 
            color: #721c24; 
            background: #f8d7da; 
            padding: 12px; 
            border-radius: 8px; 
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        .btn-retour { 
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white; 
            padding: 10px 25px; 
            border-radius: 8px; 
            text-decoration: none; 
            display: inline-block; 
            margin-top: 20px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-retour:hover { 
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .prix-cell {
            font-weight: bold;
            color: #28a745;
        }
        .stock-cell {
            color: #dc3545;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .container { padding: 15px; }
            table { font-size: 12px; }
            th, td { padding: 8px; }
            input[type="number"] { width: 60px; }
            button { width: 100%; }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Nouvelle vente</h2>
    
    <?php if(isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <label class="label">Client :</label>
        <select name="client_id" required>
            <option value="">-- Choisir un client --</option>
            <?php foreach($clients as $client): ?>
                <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['nom']) ?></option>
            <?php endforeach; ?>
        </select>
        
        <h3>Articles</h3>
        <table>
            <thead>
                <tr><th>Article</th><th>Prix (FCFA)</th><th>Stock</th><th>Quantité</th></tr>
            </thead>
            <tbody>
                <?php foreach($articles as $article): ?>
                <tr>
                    <td><?= htmlspecialchars($article['nom']) ?></td>
                    <td class="prix-cell"><?= number_format($article['prix'], 0, ',', ' ') ?> FCFA</td>
                    <td class="stock-cell"><?= number_format($article['quantite'], 0, ',', ' ') ?></td>
                    <td><input type="number" name="articles[<?= $article['id'] ?>]" min="0" max="<?= $article['quantite'] ?>" value="0"></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <button type="submit">Enregistrer la vente</button>
    </form>
    
    <a href="index.php?action=accueil" class="btn-retour">Retour accueil</a>
</div>
</body>
</html>
