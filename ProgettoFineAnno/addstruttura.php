<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['IdUtente']) || empty($_SESSION['ruolo']) || $_SESSION['ruolo'] !== 'proprietario') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Struttura - OpinioniVacanze</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/addstruttura.css">
</head>
<body class="<?php echo (!empty($_SESSION['ruolo']) && $_SESSION['ruolo'] === 'proprietario') ? 'owner-theme' : ''; ?>">

<div class="add-page">
    <div class="add-container">
        <div class="add-header">
            <h2>Aggiungi una nuova struttura</h2>
            <p>Inserisci le informazioni principali della struttura e carica le foto.</p>
        </div>

        <form action="addstrutturacheck.php" method="POST" enctype="multipart/form-data" class="add-form">

            <div class="form-grid">
                <div class="form-group full">
                    <label for="nomeStruttura">Nome struttura</label>
                    <input type="text" id="nomeStruttura" name="nomeStruttura" required>
                </div>

                <div class="form-group full">
                    <label for="descrizione">Descrizione</label>
                    <textarea id="descrizione" name="descrizione" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="indirizzo">Indirizzo</label>
                    <input type="text" id="indirizzo" name="indirizzo" required>
                </div>

                <div class="form-group">
                    <label for="citta">Città</label>
                    <input type="text" id="citta" name="citta" required>
                </div>

                <div class="form-group">
                    <label for="tipoLocalita">Tipo località</label>
                    <select id="tipoLocalita" name="tipoLocalita" required>
                        <option value="">Seleziona</option>
                        <option value="mare">Mare</option>
                        <option value="montagna">Montagna</option>
                        <option value="lago">Lago</option>
                        <option value="campagna">Campagna</option>
                        <option value="citta">Città d'arte</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tipologia">Tipologia struttura</label>
                    <select id="tipologia" name="tipologia" required>
                        <option value="">Seleziona</option>
                        <option value="albergo">Albergo</option>
                        <option value="bnb">B&B</option>
                        <option value="casavacanze">Casa vacanze</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label for="foto">Foto struttura</label>

                    <label for="foto" class="file-upload-box">
                    <span class="file-upload-title">Carica le foto della struttura</span>
                    <span class="file-upload-subtitle">Puoi selezionare una o più immagini</span>
                    </label>

                    <input type="file" id="foto" name="foto[]" multiple accept="image/*" class="file-input-hidden">
                </div>
            </div>

            <div class="section-title">
                <h3>Dettagli specifici</h3>
            </div>

            <!-- Albergo -->
            <div class="specific-section" id="albergo-fields">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="catena">Catena</label>
                        <input type="text" id="catena" name="catena">
                    </div>

                    <div class="form-group">
                        <label for="numeroCamereHotel">Numero camere</label>
                        <input type="number" id="numeroCamereHotel" name="numeroCamereHotel" min="1">
                    </div>

                    <div class="form-group">
                        <label for="numeroStelle">Numero stelle</label>
                        <input type="number" id="numeroStelle" name="numeroStelle" min="1" max="5">
                    </div>
                </div>
            </div>

            <!-- B&B -->
            <div class="specific-section" id="bnb-fields">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="categoriaBnb">Categoria</label>
                        <select id="categoriaBnb" name="categoriaBnb">
                            <option value="">Seleziona</option>
                            <option value="villa">Villa</option>
                            <option value="appartamento">Appartamento</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="numeroCamereBnb">Numero camere</label>
                        <input type="number" id="numeroCamereBnb" name="numeroCamereBnb" min="1">
                    </div>

                    <div class="form-group">
                        <label for="colazioneInclusa">Colazione inclusa</label>
                        <select id="colazioneInclusa" name="colazioneInclusa">
                            <option value="">Seleziona</option>
                            <option value="1">Sì</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Casa Vacanze -->
            <div class="specific-section" id="casavacanze-fields">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="numPostiLetto">Posti letto</label>
                        <input type="number" id="numPostiLetto" name="numPostiLetto" min="1">
                    </div>

                    <div class="form-group">
                        <label for="superficie">Superficie (mq)</label>
                        <input type="number" id="superficie" name="superficie" min="1">
                    </div>

                    <div class="form-group">
                        <label for="numBagni">Numero bagni</label>
                        <input type="number" id="numBagni" name="numBagni" min="1">
                    </div>

                    <div class="form-group">
                        <label for="animaliAmmessi">Animali ammessi</label>
                        <select id="animaliAmmessi" name="animaliAmmessi">
                            <option value="">Seleziona</option>
                            <option value="1">Sì</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Agriturismo -->
            <div class="specific-section" id="agriturismo-fields">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="servizioRistorante">Ristorante incluso</label>
                        <select id="servizioRistorante" name="servizioRistorante">
                            <option value="">Seleziona</option>
                            <option value="1">Sì</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="numeroCamereAgriturismo">Numero camere</label>
                        <input type="number" id="numeroCamereAgriturismo" name="numeroCamereAgriturismo" min="1">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit">Salva struttura</button>
            </div>
        </form>
    </div>
</div>

<script>
const tipologia = document.getElementById("tipologia");
const sections = {
    albergo: document.getElementById("albergo-fields"),
    bnb: document.getElementById("bnb-fields"),
    casavacanze: document.getElementById("casavacanze-fields"),
    agriturismo: document.getElementById("agriturismo-fields")
};

function hideAllSections() {
    Object.values(sections).forEach(section => {
        section.style.display = "none";
    });
}

tipologia.addEventListener("change", function() {
    hideAllSections();
    const selected = this.value;
    if (sections[selected]) {
        sections[selected].style.display = "block";
    }
});

hideAllSections();
</script>

<script>
const fotoInput = document.getElementById("foto");
const uploadSubtitle = document.querySelector(".file-upload-subtitle");

fotoInput.addEventListener("change", function() {
    if (this.files.length > 0) {
        uploadSubtitle.textContent = this.files.length + " file selezionati";
    } else {
        uploadSubtitle.textContent = "Puoi selezionare una o più immagini";
    }
});
</script>

</body>
</html>