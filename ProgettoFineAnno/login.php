<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OpinioniVacanze</title>
    <link rel="stylesheet" href="CSS/login.css">
</head>
<body>

<div class="login-container">
    <h2>Accedi</h2>

    <form action="logincheck.php" method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p class="hint">
        Non hai un account? <a href="register.php">Registrati</a>
    </p>
</div>

</body>
</html>