<?php
session_start();
require_once './config/db.php';


// Récupération des commentaires (du plus récent au plus ancien)
$sql = "SELECT commentaires.commentaire, commentaires.date, utilisateurs.login 
        FROM commentaires 
        JOIN utilisateurs ON commentaires.id_utilisateur = utilisateurs.id 
        ORDER BY commentaires.date DESC";
$stmt = $pdo->query($sql);
$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Livre d'or</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header>
        <h1>Livre d'or</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <?php if (isset($_SESSION['user']['id'])): ?>
                <a href="profil.php">Mon profil</a>
                <a href="commentaire.php">Ajouter un commentaire</a>
                <a href="deconnexion.php">Déconnexion</a>
            <?php else: ?>
                <a href="inscription.php">Inscription</a>
                <a href="connexion.php">Connexion</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <section class="livreor-container">
            <h2>Les derniers avis</h2>
            <?php if (!empty($commentaires)): ?>
                <?php foreach ($commentaires as $com): ?>
                    <div class="comment-card">
                        <p class="comment-meta">
                            Posté le <?= date("d/m/Y H:i", strtotime($com['date'])) ?> par <strong><?= htmlspecialchars($com['login']) ?></strong>
                        </p>
                        <p class="comment-text"><?= nl2br(htmlspecialchars($com['commentaire'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun commentaire pour le moment. Soyez le premier !</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
