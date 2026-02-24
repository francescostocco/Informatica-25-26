<link rel="stylesheet" href="CSS/header.css">

<header class="header">
    <img id="logo" src="IMG/cover.jpg" alt="Logo">

    <div class="user-menu">
        <a class="user-icon" title="Account">
            <i class="fa-solid fa-user"></i>
        </a>

        <div class="dropdown">
            <a href="account.php">Area personale</a>
            <a href="logout.php" class="danger">Logout</a>
        </div>
    </div>
</header>

<script>
(function () {
  const menu = document.querySelector('.user-menu');
  if (!menu) return;

  const icon = menu.querySelector('.user-icon');
  const dropdown = menu.querySelector('.dropdown');
  if (!icon || !dropdown) return;

  const CLOSE_DELAY_MS = 200; // aumenta/diminuisci (es. 2000 = 2s)
  let closeTimer = null;

  function openDropdown() {
    if (closeTimer) clearTimeout(closeTimer);
    dropdown.style.display = 'block';
  }

  function scheduleClose() {
    if (closeTimer) clearTimeout(closeTimer);
    closeTimer = setTimeout(() => {
      dropdown.style.display = 'none';
    }, CLOSE_DELAY_MS);
  }

  // Hover su icona o dropdown: apre e non chiude subito
  icon.addEventListener('mouseenter', openDropdown);
  icon.addEventListener('mouseleave', scheduleClose);

  dropdown.addEventListener('mouseenter', openDropdown);
  dropdown.addEventListener('mouseleave', scheduleClose);

  // Tastiera: quando l'icona riceve/perde focus
  icon.addEventListener('focus', openDropdown);
  icon.addEventListener('blur', scheduleClose);

  // Chiude se clicchi altrove (opzionale ma comodo)
  document.addEventListener('click', (e) => {
    if (!menu.contains(e.target)) dropdown.style.display = 'none';
  });
})();
</script>
