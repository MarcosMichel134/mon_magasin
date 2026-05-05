<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ventes - Historique</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Playfair Display", serif; }
        body { 
            /* font-family: "Playfair Display", serif;  */
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
            border-left: 5px solid #28a745;
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        .total { 
            font-weight: bold; 
            color: #28a745;
            font-size: 16px;
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
        .badge {
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 12px;
            display: inline-block;
            margin: 2px;
        }
        .articles-list {
            max-width: 300px;
        }
        @media (max-width: 768px) {
            .container { padding: 15px; }
            table { font-size: 12px; }
            th, td { padding: 8px; }
            .total { font-size: 14px; }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Historique des ventes</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Date</th>
                <th>Articles</th>
                <th>Total (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT v.*, c.nom as client_nom FROM ventes v JOIN clients c ON v.client_id = c.id ORDER BY v.date_vente DESC");
            $ventes = $stmt->fetchAll();
            foreach($ventes as $vente):
                $stmt2 = $pdo->prepare("SELECT d.*, a.nom as article_nom FROM details_ventes d JOIN articles a ON d.article_id = a.id WHERE d.vente_id = ?");
                $stmt2->execute([$vente['id']]);
                $details = $stmt2->fetchAll();
                $articles_str = implode(', ', array_map(function($d) { 
                    return '<span class="badge">' . htmlspecialchars($d['article_nom']) . " (x{$d['quantite']})</span>"; 
                }, $details));
            ?>
            <tr>
                <td><?= $vente['id'] ?></td>
                <td><?= htmlspecialchars($vente['client_nom']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($vente['date_vente'])) ?></td>
                <td class="articles-list"><?= $articles_str ?></td>
                <td class="total"><?= number_format($vente['montant_total'], 0, ',', ' ') ?> FCFA</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <a href="index.php?action=accueil" class="btn-retour">Retour accueil</a>
</div>
</body>
</html>
