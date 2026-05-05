<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Proprietari - OpinioniVacanze</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/ownerauth.css">
</head>
<body>

<div class="owner-page">
    <div class="owner-container" id="ownerContainer">

        <!-- REGISTRAZIONE PROPRIETARIO -->
        <div class="owner-form-panel owner-register-panel">
            <form action="../actions/ownerregistercheck.php" method="POST" class="owner-form">
                <h2>Registrazione proprietario</h2>
                <p class="owner-subtitle">Crea il tuo account per inserire e gestire strutture.</p>

                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Ripeti password" required>

                <input type="text" name="nomeAttivita" placeholder="Nome attività" required>
                <input type="text" name="sedeLegale" placeholder="Sede legale" required>
                <input type="text" name="partitaIVA" placeholder="Partita IVA" required>
                <input type="text" name="telefono" placeholder="Telefono" required>

                <button type="submit">Registrati</button>
            </form>
        </div>

        <!-- LOGIN PROPRIETARIO -->
        <div class="owner-form-panel owner-login-panel">
            <form action="../actions/ownerlogincheck.php" method="POST" class="owner-form">
                <h2>Accesso proprietari</h2>
                <p class="owner-subtitle">Accedi al pannello per gestire le tue strutture.</p>

                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit">Accedi</button>

                <p class="back-user-link">
                    Sei un utente normale? <a href="../login.php">Torna all’accesso utente</a>
                </p>
            </form>
        </div>

        <!-- PANNELLO ANIMATO -->
        <div class="owner-overlay-shell">
            <div class="owner-overlay-track">

                <div class="owner-overlay-content owner-overlay-left">
                    <h2>Hai già un account?</h2>
                    <p>Accedi e vai al pannello proprietari per inserire o gestire le tue strutture.</p>
                    <button type="button" class="owner-switch-btn ghost" id="ownerShowLogin">Accedi</button>
                </div>

                <div class="owner-overlay-content owner-overlay-right">
                    <h2>Nuovo proprietario?</h2>
                    <p>Registrati per entrare nell’area riservata ai gestori delle strutture.</p>
                    <button type="button" class="owner-switch-btn ghost" id="ownerShowRegister">Registrati</button>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
const ownerContainer = document.getElementById("ownerContainer");
const ownerShowRegister = document.getElementById("ownerShowRegister");
const ownerShowLogin = document.getElementById("ownerShowLogin");

ownerShowRegister.addEventListener("click", () => {
    ownerContainer.classList.add("register-mode");
});

ownerShowLogin.addEventListener("click", () => {
    ownerContainer.classList.remove("register-mode");
});
</script>

</body>
</html>