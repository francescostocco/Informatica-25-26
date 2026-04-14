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

        <!-- FORM REGISTRAZIONE -->
        <div class="form-panel register-panel">
            <form action="registercheck.php" method="POST" class="auth-form" id="registerForm">
                <h2>Crea account</h2>
                <p class="subtitle">Registrati per iniziare a recensire strutture turistiche.</p>

                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" id="registerPassword" placeholder="Password" required>
                <input type="password" name="confirm_password" id="confirmPassword" placeholder="Ripeti password" required>

                <button type="submit">Registrati</button>
            </form>
        </div>

        <!-- FORM LOGIN -->
        <div class="form-panel login-panel">
            <form action="logincheck.php" method="POST" class="auth-form">
                <h2>Accedi</h2>
                <p class="subtitle">Bentornato, accedi per continuare.</p>

                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit">Login</button>

                <p class="owner-entry">
                    Sei un proprietario? <a href="ownerlogin.php">Accedi o registrati qui</a>
                </p>
            </form>
        </div>

        <!-- PANNELLO ANIMATO -->
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

<script>
const authContainer = document.getElementById("authContainer");
const showRegister = document.getElementById("showRegister");
const showLogin = document.getElementById("showLogin");

let isAnimating = false;
const ANIMATION_TIME = 750; // deve essere uguale o simile al tempo del CSS

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

<!-- SCRIPT per controllo inserimento password uguale al campo sopra -->
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