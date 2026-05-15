<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Registrazione - OpinioniVacanze</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/auth.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-container" id="authContainer">

        <!-- Form per la registrazione dell'utente, invia dati a registercheck.php -->
        <div class="form-panel register-panel">
            <form action="actions/registercheck.php" method="POST" class="auth-form" id="registerForm">
                <h2>Crea account</h2>
                <p class="subtitle">Registrati per iniziare a recensire strutture turistiche.</p>

                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" id="registerPassword" placeholder="Password" required>
                <input type="password" name="confirm_password" id="confirmPassword" placeholder="Ripeti password" required>

                <button type="submit">Registrati</button>
            </form>
        </div>

        <!-- Form per il login dell'utente, invia dati a logincheck.php -->
        <div class="form-panel login-panel">
            <form action="actions/logincheck.php" method="POST" class="auth-form">
                <h2>Accedi</h2>
                <p class="subtitle">Bentornato, accedi per continuare.</p>

                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit">Login</button>

                <p class="owner-entry">
                    Sei un proprietario? <a href="ownerpages/ownerlogin.php">Accedi o registrati qui</a>
                </p>
            </form>
        </div>

        <!-- Pannello con animazione passaggio tra login e registrazione  -->
        <div class="overlay-shell">
            <div class="overlay-track">

                <div class="overlay-content overlay-left">
                    <h2>Bentornato!</h2>
                    <p>Hai già un account? Accedi e continua a esplorare OpinioniVacanze.</p>
                    <button type="button" class="switch-btn ghost" id="showLogin">Login</button>
                </div>

                <div class="overlay-content overlay-right">
                    <h2>Ciao!</h2>
                    <p>Non hai ancora un account? Registrati e inizia subito a recensire.</p>
                    <button type="button" class="switch-btn ghost" id="showRegister">Registrati</button>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Script per transizione animata -->
<script>
const authContainer = document.getElementById("authContainer");
const showRegister = document.getElementById("showRegister");
const showLogin = document.getElementById("showLogin");

let isAnimating = false;
const ANIMATION_TIME = 750; // durata animazione

function switchMode(toRegister) {
    if (isAnimating) return;

    const alreadyInMode = authContainer.classList.contains("register-mode");
    if (toRegister === alreadyInMode) return;

    isAnimating = true;

    authContainer.classList.add("is-animating");

    if (toRegister) {
        authContainer.classList.add("register-mode");
    } else {
        authContainer.classList.remove("register-mode");
    }

    setTimeout(() => {
        authContainer.classList.remove("is-animating");
        isAnimating = false;
    }, ANIMATION_TIME);
}

showRegister.addEventListener("click", () => switchMode(true));
showLogin.addEventListener("click", () => switchMode(false));
</script>

<!-- Script che controlla che le due password coincidono, se non coincidono mostra alert con scritto che non corrispondono -->
<script>
const registerForm = document.getElementById("registerForm");
const registerPassword = document.getElementById("registerPassword");
const confirmPassword = document.getElementById("confirmPassword");

registerForm.addEventListener("submit", function(e) {
    if (registerPassword.value !== confirmPassword.value) {
        e.preventDefault();
        alert("Le password non coincidono.");
        confirmPassword.focus();
    }
});
</script>

</body>
</html>