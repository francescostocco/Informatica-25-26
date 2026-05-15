<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    empty($_SESSION['IdUtente']) ||
    empty($_SESSION['ruolo']) ||
    $_SESSION['ruolo'] !== 'proprietario'
) {
    header("Location: ../login.php");
    exit;
}

require __DIR__ . '/../include/connect.php';

/**Dati principali del form per inserire la struttura */
$nomeStruttura = trim($_POST['nomeStruttura'] ?? '');
$descrizione = trim($_POST['descrizione'] ?? '');
$indirizzo = trim($_POST['indirizzo'] ?? '');
$citta = trim($_POST['citta'] ?? '');
$tipoLocalita = trim($_POST['tipoLocalita'] ?? '');
$tipologia = trim($_POST['tipologia'] ?? '');

$idUtente = $_SESSION['IdUtente'];

/**Verifica che inserimento utente non sia vuoto */
if ($nomeStruttura === '' || $descrizione === '' || $indirizzo === '' || $citta === '' || $tipoLocalita === '' || $tipologia === '') {
    header("Location: ../ownerpages/addstruttura.php?err=1");
    exit;
}

try {
    $conn->beginTransaction();

/*Prendo con la query l'id del Proprietario*/ 
    $sqlProp = "SELECT IdProprietario FROM Proprietari
                WHERE IdUtente = :idUtente LIMIT 1";

    $stmtProp = $conn->prepare($sqlProp);
    $stmtProp->bindParam(':idUtente', $idUtente);
    $stmtProp->execute();

    $proprietario = $stmtProp->fetch(PDO::FETCH_ASSOC);

    if (!$proprietario) {
        $conn->rollBack();
        header("Location: ../ownerpages/addstruttura.php?err=2");
        exit;
    }

    $idProprietario = $proprietario['IdProprietario'];

/*TipoLocalità, se non esiste la crea direttamente*/ 
    $sqlTipo = "SELECT `IdTipoLocalità` FROM `TipoLocalità`WHERE `TipoLocalità` = :tipoLocalita LIMIT 1";

    $stmtTipo = $conn->prepare($sqlTipo);
    $stmtTipo->bindParam(':tipoLocalita', $tipoLocalita);
    $stmtTipo->execute();

    $tipo = $stmtTipo->fetch(PDO::FETCH_ASSOC);

    if ($tipo) {
        $idTipoLocalita = $tipo['IdTipoLocalità'];
    } else {
        $sqlInsertTipo = "INSERT INTO `TipoLocalità` (`TipoLocalità`) VALUES (:tipoLocalita)";
        $stmtInsertTipo = $conn->prepare($sqlInsertTipo);
        $stmtInsertTipo->bindParam(':tipoLocalita', $tipoLocalita);
        $stmtInsertTipo->execute();

        $idTipoLocalita = $conn->lastInsertId();
    }

/*Inserimento struttura nella tabella strutture */
    $sqlStruttura = "INSERT INTO Strutture (NomeStruttura, Descrizione, Indirizzo, `Città`, `IdTipoLocalità`, IdProprietario)
                    VALUES (:nomeStruttura, :descrizione, :indirizzo, :citta, :idTipoLocalita, :idProprietario)";

    $stmtStruttura = $conn->prepare($sqlStruttura);
    $stmtStruttura->bindParam(':nomeStruttura', $nomeStruttura);
    $stmtStruttura->bindParam(':descrizione', $descrizione);
    $stmtStruttura->bindParam(':indirizzo', $indirizzo);
    $stmtStruttura->bindParam(':citta', $citta);
    $stmtStruttura->bindParam(':idTipoLocalita', $idTipoLocalita);
    $stmtStruttura->bindParam(':idProprietario', $idProprietario);
    $stmtStruttura->execute();

    $codStruttura = $conn->lastInsertId();

/*Struttura inserita nella tabella specifica (Hotel, BnB, Case vacanze)*/ 
    if ($tipologia === 'albergo') {
        $catena = trim($_POST['catena'] ?? '');
        $numeroCamereHotel = $_POST['numeroCamereHotel'] ?? null;
        $numeroStelle = $_POST['numeroStelle'] ?? null;

        $sqlAlbergo = "INSERT INTO Alberghi (CodStruttura, Catena, NumeroCamere, NumeroStelle)
                      VALUES (:codStruttura, :catena, :numeroCamere, :numeroStelle)";

        $stmtAlbergo = $conn->prepare($sqlAlbergo);
        $stmtAlbergo->bindParam(':codStruttura', $codStruttura);
        $stmtAlbergo->bindParam(':catena', $catena);
        $stmtAlbergo->bindParam(':numeroCamere', $numeroCamereHotel);
        $stmtAlbergo->bindParam(':numeroStelle', $numeroStelle);
        $stmtAlbergo->execute();
    }

    elseif ($tipologia === 'bnb') {
        $categoriaBnb = trim($_POST['categoriaBnb'] ?? '');
        $numeroCamereBnb = $_POST['numeroCamereBnb'] ?? null;
        $colazioneInclusa = $_POST['colazioneInclusa'] ?? null;

        $sqlBnb = "INSERT INTO BnB (CodStruttura, Categoria, NumeroCamere, ColazioneInclusa)
                  VALUES (:codStruttura, :categoria, :numeroCamere, :colazioneInclusa)";

        $stmtBnb = $conn->prepare($sqlBnb);
        $stmtBnb->bindParam(':codStruttura', $codStruttura);
        $stmtBnb->bindParam(':categoria', $categoriaBnb);
        $stmtBnb->bindParam(':numeroCamere', $numeroCamereBnb);
        $stmtBnb->bindParam(':colazioneInclusa', $colazioneInclusa);
        $stmtBnb->execute();
    }

    elseif ($tipologia === 'casavacanze') {
        $numPostiLetto = $_POST['numPostiLetto'] ?? null;
        $superficie = $_POST['superficie'] ?? null;
        $numBagni = $_POST['numBagni'] ?? null;
        $animaliAmmessi = $_POST['animaliAmmessi'] ?? null;

        $sqlCasa = "INSERT INTO CaseVacanze (CodStruttura, NumPostiLetto, Superficie, NumBagni, AnimaliAmmessi)
                   VALUES (:codStruttura, :numPostiLetto, :superficie, :numBagni, :animaliAmmessi)";

        $stmtCasa = $conn->prepare($sqlCasa);
        $stmtCasa->bindParam(':codStruttura', $codStruttura);
        $stmtCasa->bindParam(':numPostiLetto', $numPostiLetto);
        $stmtCasa->bindParam(':superficie', $superficie);
        $stmtCasa->bindParam(':numBagni', $numBagni);
        $stmtCasa->bindParam(':animaliAmmessi', $animaliAmmessi);
        $stmtCasa->execute();
    }

    else {
        $conn->rollBack();
        header("Location: ../ownerpages/addstruttura.php?err=3");
        exit;
    }

/*Caricamento foto*/ 
    if (!empty($_FILES['foto']['name'][0])) {
        $uploadDir = __DIR__ . '/../uploads/strutture/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($_FILES['foto']['name'] as $key => $nomeOriginale) {
            if ($_FILES['foto']['error'][$key] === 0) {
                $tmpName = $_FILES['foto']['tmp_name'][$key];
                $estensione = pathinfo($nomeOriginale, PATHINFO_EXTENSION);
                $nomeFile = uniqid('struttura_', true) . '.' . $estensione;
                $percorsoCompleto = $uploadDir . $nomeFile;

                if (move_uploaded_file($tmpName, $percorsoCompleto)) {
                    $urlFoto = 'uploads/strutture/' . $nomeFile;

                    $sqlFoto = "INSERT INTO FotoStrutture (UrlFoto, CodStruttura)
                                VALUES (:urlFoto, :codStruttura)";

                    $stmtFoto = $conn->prepare($sqlFoto);
                    $stmtFoto->bindParam(':urlFoto', $urlFoto);
                    $stmtFoto->bindParam(':codStruttura', $codStruttura);
                    $stmtFoto->execute();
                }
            }
        }
    }

    $conn->commit();

    header("Location: ../ownerpages/owneraccount.php?ok=1");
    exit;

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    echo "Errore durante il salvataggio della struttura: " . $e->getMessage();
}
?>