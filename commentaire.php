<?php
// commentaire.php
session_start();
require_once './config/db.php';

if (empty($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentaire = trim(filter_input(INPUT_POST, 'commentaire'));
    if (!$commentaire) {
        $errors[] = 'Le commentaire ne peut pas être vide.';
    }
    if (empty($errors)) {
        $ins = $pdo->prepare('INSERT INTO commentaires (commentaire, id_utilisateur, date) VALUES (?, ?, NOW())');
        $ins->execute([$commentaire, $_SESSION['user']['id']]);
        header('Location: livre-or.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Ajouter un commentaire — Livre d'or</title>
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h1 class="h1">Ajouter un commentaire</h1>
      <?php if ($errors): ?>
        <div class="alert">
          <?php foreach ($errors as $error): ?>
            <div>- <?php echo htmlspecialchars($error); ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="post" action="">
        <div class="form-group">
          <label for="commentaire">Votre commentaire</label>
          <textarea id="commentaire" name="commentaire" required><?php echo isset($commentaire) ? htmlspecialchars($commentaire) : ''; ?></textarea>
        </div>
        <button class="btn" type="submit">Poster</button>
      </form>

    </div>
  </div>
</body>
</html>
