<?php
require __DIR__ . '/include/connect.php';

/* Valori ricerca e filtri */
$search = trim($_GET['search'] ?? '');
$tipologiaFiltro = trim($_GET['tipologia'] ?? '');
$localitaFiltro = trim($_GET['localita'] ?? '');

/* Query strutture */
$sql = "SELECT S.CodStruttura, S.NomeStruttura, S.`Città`, F.UrlFoto, T.`TipoLocalità`,
        CASE
            WHEN A.CodStruttura IS NOT NULL THEN 'Albergo'
            WHEN B.CodStruttura IS NOT NULL THEN 'B&B'
            WHEN C.CodStruttura IS NOT NULL THEN 'Casa vacanze'
            ELSE 'Struttura'
        END AS Tipologia
        FROM Strutture S
        LEFT JOIN FotoStrutture F ON S.CodStruttura = F.CodStruttura
        LEFT JOIN Alberghi A ON S.CodStruttura = A.CodStruttura
        LEFT JOIN BnB B ON S.CodStruttura = B.CodStruttura
        LEFT JOIN CaseVacanze C ON S.CodStruttura = C.CodStruttura
        LEFT JOIN `TipoLocalità` T ON S.`IdTipoLocalità` = T.`IdTipoLocalità`";

$where = [];

if ($search !== '') {
    $where[] = "(S.NomeStruttura LIKE :search OR S.`Città` LIKE :search)";
}

if ($tipologiaFiltro !== '') {
    $where[] = "(
        CASE
            WHEN A.CodStruttura IS NOT NULL THEN 'albergo'
            WHEN B.CodStruttura IS NOT NULL THEN 'bnb'
            WHEN C.CodStruttura IS NOT NULL THEN 'casavacanze'
            ELSE 'struttura'
        END
    ) = :tipologia";
}

if ($localitaFiltro !== '') {
    $where[] = "T.`TipoLocalità` = :localita";
}

if (count($where) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " GROUP BY S.CodStruttura
          ORDER BY S.CodStruttura DESC";

$stmt = $conn->prepare($sql);

if ($search !== '') {
    $searchLike = "%$search%";
    $stmt->bindParam(':search', $searchLike);
}

if ($tipologiaFiltro !== '') {
    $stmt->bindParam(':tipologia', $tipologiaFiltro);
}

if ($localitaFiltro !== '') {
    $stmt->bindParam(':localita', $localitaFiltro);
}

$stmt->execute();

$strutture = $stmt->fetchAll(PDO::FETCH_ASSOC);

$filtriAttivi = $tipologiaFiltro !== '' || $localitaFiltro !== '';
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="IMG/favicon.ico">
    <link rel="stylesheet" href="CSS/index.css">

    <title>Homepage - OpinioniVacanze</title>
</head>

<body class="<?php echo (!empty($_SESSION['ruolo']) && $_SESSION['ruolo'] === 'proprietario') ? 'owner-theme' : ''; ?>">

<main class="main-content">

    <h1>Opinioni Vacanze</h1>

    <form class="search-area" method="GET" action="index.php" id="searchForm">

        <div class="search-bar">
            <input 
                type="text" 
                name="search"
                id="searchInput"
                placeholder="Cerca strutture o città..."
                value="<?php echo htmlspecialchars($search); ?>"
                autocomplete="off"
            >

            <button type="button" class="filter-toggle <?php echo $filtriAttivi ? 'active' : ''; ?>" id="filterToggle">
                <i class="fa-solid fa-sliders"></i>
                Filtri
            </button>
        </div>

        <div class="filters-panel <?php echo $filtriAttivi ? 'show' : ''; ?>" id="filtersPanel">

            <div class="filter-group">
                <label for="tipologiaFilter">Tipo struttura</label>
                <select name="tipologia" id="tipologiaFilter">
                    <option value="">Tutte</option>
                    <option value="albergo" <?php echo $tipologiaFiltro === 'albergo' ? 'selected' : ''; ?>>Alberghi</option>
                    <option value="bnb" <?php echo $tipologiaFiltro === 'bnb' ? 'selected' : ''; ?>>B&B</option>
                    <option value="casavacanze" <?php echo $tipologiaFiltro === 'casavacanze' ? 'selected' : ''; ?>>Case vacanze</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="localitaFilter">Località</label>
                <select name="localita" id="localitaFilter">
                    <option value="">Tutte</option>
                    <option value="mare" <?php echo $localitaFiltro === 'mare' ? 'selected' : ''; ?>>Mare</option>
                    <option value="montagna" <?php echo $localitaFiltro === 'montagna' ? 'selected' : ''; ?>>Montagna</option>
                    <option value="lago" <?php echo $localitaFiltro === 'lago' ? 'selected' : ''; ?>>Lago</option>
                    <option value="campagna" <?php echo $localitaFiltro === 'campagna' ? 'selected' : ''; ?>>Campagna</option>
                    <option value="citta" <?php echo $localitaFiltro === 'citta' ? 'selected' : ''; ?>>Città d'arte</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit">Applica</button>
                <a href="index.php">Reset</a>
            </div>

        </div>

    </form>

    <div class="home-structures">

        <h2>Strutture disponibili</h2>

        <div class="structures-grid">

            <?php if (count($strutture) === 0): ?>
                <p class="no-results">Nessuna struttura trovata.</p>
            <?php endif; ?>

            <?php foreach ($strutture as $struttura): ?>
                <a href="structurepages/struttura.php?id=<?php echo $struttura['CodStruttura']; ?>" class="structure-card">

                    <div class="structure-image">
                        <?php if (!empty($struttura['UrlFoto'])): ?>
                            <img 
                                src="<?php echo htmlspecialchars($struttura['UrlFoto']); ?>" 
                                alt="Foto struttura"
                            >
                        <?php else: ?>
                            <div class="structure-placeholder">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="structure-body">
                        <span class="structure-type">
                            <?php echo htmlspecialchars($struttura['Tipologia']); ?>
                        </span>

                        <h3>
                            <?php echo htmlspecialchars($struttura['NomeStruttura']); ?>
                        </h3>

                        <p>
                            <?php echo htmlspecialchars($struttura['Città']); ?>
                            <?php if (!empty($struttura['TipoLocalità'])): ?>
                                · <?php echo htmlspecialchars(mb_convert_case($struttura['TipoLocalità'], MB_CASE_TITLE, "UTF-8")); ?>
                            <?php endif; ?>
                        </p>
                    </div>

                </a>
            <?php endforeach; ?>

        </div>
    </div>

</main>

<script>
const searchForm = document.getElementById("searchForm");
const searchInput = document.getElementById("searchInput");
const filterToggle = document.getElementById("filterToggle");
const filtersPanel = document.getElementById("filtersPanel");
const tipologiaFilter = document.getElementById("tipologiaFilter");
const localitaFilter = document.getElementById("localitaFilter");

let searchTimer = null;

filterToggle.addEventListener("click", function () {
    filtersPanel.classList.toggle("show");
});

function submitSearch() {
    const value = searchInput.value.trim();

    if (value.length === 0 || value.length >= 3) {
        searchForm.submit();
    }
}

searchInput.addEventListener("input", function () {
    clearTimeout(searchTimer);

    searchTimer = setTimeout(() => {
        submitSearch();
    }, 450);
});

tipologiaFilter.addEventListener("change", function () {
    searchForm.submit();
});

localitaFilter.addEventListener("change", function () {
    searchForm.submit();
});
</script>

</body>
</html>