<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - OpinioniVacanze</title>
    <link rel="stylesheet" href="CSS/register.css">
</head>
<body>

<div class="register-container">
    <h2>Registrati</h2>

    <form action="registercheck.php" method="POST">

        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" required>

        <label for="cognome">Cognome</label>
        <input type="text" id="cognome" name="cognome" required>

        <label for="dataNascita">Data di Nascita</label>
        <input type="date" id="dataNascita" name="dataNascita" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Registrati</button>
    </form>

    <p class="hint">
        Sei già registrato? <a href="login.php">Accedi ora!</a>
    </p>
</div>

</body>
</html>