<?php
// profil.php
session_start();
require_once './config/db.php';


if (empty($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_login = trim(filter_input(INPUT_POST, 'login'));
    $new_password = $_POST['password'] ?? '';
    $new_password_confirm = $_POST['password_confirm'] ?? '';

    if (!$new_login) {
        $errors[] = 'Le login est requis.';
    }
    if ($new_password !== '' && strlen($new_password) < 6) {
        $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
    }
    if ($new_password !== $new_password_confirm) {
        $errors[] = 'La confirmation du mot de passe est différente.';
    }

    if (empty($errors)) {
        // Vérifier si nouveau login est déjà pris par un autre
        $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE login = ? AND id != ?');
        $stmt->execute([$new_login, $_SESSION['user']['id']]);
        if ($stmt->fetch()) {
            $errors[] = 'Ce login est déjà pris.';
        } else {
            if ($new_password === '') {
                $upd = $pdo->prepare('UPDATE utilisateurs SET login = ? WHERE id = ?');
                $upd->execute([$new_login, $_SESSION['user']['id']]);
            } else {
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                $upd = $pdo->prepare('UPDATE utilisateurs SET login = ?, password = ? WHERE id = ?');
                $upd->execute([$new_login, $hash, $_SESSION['user']['id']]);
            }
            $_SESSION['user']['login'] = $new_login;
            $success = 'Profil mis à jour.';
        }
    }
}

// Récupérer données actuelles
$stmt = $pdo->prepare('SELECT login FROM utilisateurs WHERE id = ?');
$stmt->execute([$_SESSION['user']['id']]);
$current = $stmt->fetch();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Profil — Livre d'or</title>
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h1 class="h1">Mon profil</h1>
      <?php if ($errors): ?>
        <div class="alert">
          <?php foreach ($errors as $e): ?>
            <div>- <?php echo htmlspecialchars($e); ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <?php if ($success): ?>
        <div class="alert" style="background:#d4edda;color:#155724;"><?php echo htmlspecialchars($success); ?></div>
      <?php endif; ?>

      <form method="post" action="">
        <div class="form-group">
          <label for="login">Login</label>
          <input id="login" name="login" type="text" value="<?php echo htmlspecialchars($current['login']); ?>" required>
        </div>
        <div class="form-group">
          <label for="password">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
          <input id="password" name="password" type="password">
        </div>
        <div class="form-group">
          <label for="password_confirm">Confirmer le nouveau mot de passe</label>
          <input id="password_confirm" name="password_confirm" type="password">
        </div>
        <button class="btn" type="submit">Enregistrer</button>
      </form>

    </div>
  </div>
</body>
</html>
