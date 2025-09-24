<?php
session_start();
require_once './config/db.php';
$errors = [];
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    $login = trim(
        filter_input(INPUT_POST, 'login')
    );
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';


    if (!$login) {
        $errors[] = 'Le login est requis.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
    }
    if ($password !== $password_confirm) {
        $errors[] = 'La confirmation du mot de passe est différente.';
    }


    if (empty($errors)) {
        // Vérifier si le login existe
        $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE login = ?');
        $stmt->execute([$login]);
        if ($stmt->fetch()) {
            $errors[] = 'Ce login est déjà utilisé.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare('INSERT INTO utilisateurs (login, password) VALUES (?, ?)');
            $insert->execute([$login, $hash]);
            header('Location: connexion.php?inscription=ok');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Inscription — Livre d'or</title>
<link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<div class="container">
<div class="card">
<h1 class="h1">Inscription</h1>
<?php if ($errors): ?>
<div class="alert">
<?php foreach ($errors as $e): ?>
<div>- <?php echo htmlspecialchars($e); ?></div>
<?php endforeach; ?>
</div>
<?php endif; ?>


<form method="post" action="">
<div class="form-group">
<label for="login">Login</label>
<input id="login" name="login" type="text" value="<?php echo isset($login) ? htmlspecialchars($login) : ''; ?>" required>
</div>
<div class="form-group">
<label for="password">Mot de passe</label>
<input id="password" name="password" type="password" required>
</div>
<div class="form-group">
<label for="password_confirm">Confirmer le mot de passe</label>
<input id="password_confirm" name="password_confirm" type="password" required>
</div>
<button class="btn" type="submit">S'inscrire</button>
</form>


<p class="small">Déjà inscrit ? <a href="connexion.php">Connectez-vous</a></p>
</div>
</div>
</body>
</html>