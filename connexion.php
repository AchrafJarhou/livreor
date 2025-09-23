<?php

session_start();
require_once './config/db.php';
$errors = [];
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    $login = trim(filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING));
    $password = $_POST['password'] ?? '';


    if (!$login || !$password) {
        $errors[] = 'Login et mot de passe requis.';
    } else {
        $stmt = $pdo->prepare('SELECT id, login, password FROM utilisateurs WHERE login = ?');
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            // Connexion OK
            $_SESSION['user'] = [
            'id' => $user['id'],
            'login' => $user['login']
            ];
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Identifiants incorrects.';
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Connexion — Livre d'or</title>
<link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<div class="container">
<div class="card">
<h1 class="h1">Connexion</h1>
<?php if (!empty($_GET['inscription']) && $_GET['inscription'] === 'ok'): ?>
<div class="alert">Inscription réussie. Vous pouvez vous connecter.</div>
<?php endif; ?>
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
<input id="login" name="login" type="text" required>
</div>
<div class="form-group">
<label for="password">Mot de passe</label>
<input id="password" name="password" type="password" required>
</div>
<button class="btn" type="submit">Se connecter</button>
</form>


<p class="small">Pas encore inscrit ? <a href="inscription.php">S'inscrire</a></p>
</div>
</div>
</body>
</html>
