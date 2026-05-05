<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Mon Magasin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Playfair Display", serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Bandeau défilant moderne */
        .marquee-wrapper {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 60px;
            margin-bottom: 30px;
            padding: 3px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        marquee {
            background: linear-gradient(90deg, #ffd89b, #19547b);
            color: white;
            padding: 14px;
            border-radius: 60px;
            font-weight: 600;
            font-size: 1.2rem;
            letter-spacing: 1px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        /* Header avec logos */
        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(255,255,255,0.95);
            padding: 15px 30px;
            border-radius: 40px;
            margin-bottom: 50px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            backdrop-filter: blur(5px);
            transition: transform 0.3s;
        }
        .header:hover {
            transform: translateY(-3px);
        }
        .logo-box {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            overflow: hidden;
            padding: 8px;
        }
        .logo-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.3s;
        }
        .logo-box:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .logo-box:hover img {
            transform: scale(1.02);
        }
        .header h1 {
            font-size: 2.2rem;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 2px;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Cartes modernes */
        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 50px;
        }
        .card {
            flex: 1;
            min-width: 200px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(5px);
            padding: 35px 20px 30px;
            border-radius: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.3);
        }
        .card:hover {
            transform: translateY(-12px);
            background: white;
            box-shadow: 0 25px 45px rgba(0,0,0,0.2);
            border-color: #ffd89b;
        }
        .card h3 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            font-weight: 700;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .card a {
            text-decoration: none;
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            padding: 10px 24px;
            border-radius: 40px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .card a:hover {
            background: linear-gradient(90deg, #ffd89b, #19547b);
            transform: scale(1.03);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        /* Bouton déconnexion */
        .deconnexion {
            display: inline-block;
            background: linear-gradient(90deg, #dc3545, #b02a37);
            color: white;
            padding: 12px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(220,53,69,0.3);
            border: none;
            font-size: 2rem;
        }
        .deconnexion:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(220,53,69,0.4);
            background: linear-gradient(90deg, #b02a37, #dc3545);
        }

        /* Responsive */
        @media (max-width: 900px) {
            .cards {
                gap: 20px;
            }
            .card h3 {
                font-size: 1.4rem;
            }
            .header h1 {
                font-size: 1.6rem;
            }
            .logo-box {
                width: 65px;
                height: 65px;
            }
        }
        @media (max-width: 700px) {
            .header {
                flex-direction: column;
                gap: 15px;
                border-radius: 30px;
            }
            .card {
                min-width: 100%;
            }
            marquee {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="marquee-wrapper">
        <marquee behavior="scroll" direction="left">BIENVENUE DANS MA PLATEFORME !</marquee>
    </div>

    <div class="header">
        <h1>ACCUEIL</h1>
    </div>

    <div class="cards">
        <div class="card">
            <h3>Articles</h3>
            <a href="index.php?action=articles">Gérer & voir →</a>
        </div>
        <div class="card">
            <h3>Ventes</h3>
            <a href="index.php?action=ventes">Historique →</a>
        </div>
        <div class="card">
            <h3>Effectuer vente</h3>
            <a href="index.php?action=effectuer_vente">Nouvelle vente →</a>
        </div>
        <div class="card">
            <h3>Clients</h3>
            <a href="index.php?action=clients">Liste clients →</a>
        </div>
        <div class="card">
            <h3>Utilisateurs</h3>
            <a href="index.php?action=utilisateurs">Comptes actifs →</a>
        </div>
    </div>

    <a href="index.php?action=deconnexion" class="deconnexion">Déconnexion</a>
</div>
</body>
</html>