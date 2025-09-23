<?php
session_start();
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Accueil — Livre d'or</title>
<link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<div class="container">
<header class="header">
<div class="brand">
<div class="logo">LD</div>
<div>
<h2 style="margin:0">Livre d'or</h2>
<div class="small">Partagez vos impressions sur notre site</div>
</div>
</div>
<nav>
<a href="index.php">Accueil</a>
<a href="livre-or.php">Voir le livre d'or</a>
<?php if (empty($_SESSION['user'])): ?>
<a href="inscription.php">Inscription</a>
<a href="connexion.php">Connexion</a>
<?php else: ?>
<a href="profil.php">Mon profil (<?php echo htmlspecialchars($_SESSION['user']['login']); ?>)</a>
<a href="deconnexion.php">Se déconnecter</a>
<?php endif; ?>
</nav>
</header>


<div class="card">
<h1 class="h1">Bienvenue</h1>
<p>Ce site contient un livre d'or : les utilisateurs peuvent s'inscrire, se connecter et laisser leurs commentaires. Thème : sobriété moderne.</p>
<div class="actions">
<a class="btn" href="livre-or.php">Voir le livre d'or</a>
<?php if (empty($_SESSION['user'])): ?>
<a class="btn secondary" href="inscription.php">S'inscrire</a>
<?php else: ?>
<a class="btn secondary" href="commentaire.php">Ajouter un commentaire</a>
<?php endif; ?>
</div>
</div>


<div class="footer">© <?php echo date('Y'); ?> — Livre d'or</div>
</div>
</body>
</html>