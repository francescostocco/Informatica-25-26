<link rel="stylesheet" href="CSS/header.css">

<header class="header">
    <a href="index.php">
        <img id="logo" src="IMG/cover.jpg" alt="Logo">
    </a>

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

  const CLOSE_DELAY_MS = 200;
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

  icon.addEventListener('mouseenter', openDropdown);
  icon.addEventListener('mouseleave', scheduleClose);

  dropdown.addEventListener('mouseenter', openDropdown);
  dropdown.addEventListener('mouseleave', scheduleClose);

  icon.addEventListener('focus', openDropdown);
  icon.addEventListener('blur', scheduleClose);

  document.addEventListener('click', (e) => {
    if (!menu.contains(e.target)) dropdown.style.display = 'none';
  });
})();
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const logoutLink = document.querySelector('a[href="logout.php"]');
  if (!logoutLink) return;

  logoutLink.addEventListener("click", function (e) {
    e.preventDefault();

    document.body.style.transition = "opacity 0.3s ease";
    document.body.style.opacity = "0";

    setTimeout(() => {
      window.location.href = this.href;
    }, 300);
  });
});
</script>
