<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "connection.php";
    $cours = $_POST['cours'];
    $evaluation = $_POST['evaluation'];

    $sql = "SELECT note FROM resultats WHERE id_cours = ? AND id_evaluation = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cours, $evaluation);
    $stmt->execute();
    $stmt->bind_result($note);
    $stmt->fetch();

    if ($note !== null) {
        echo $note;
    } else {
        echo "Aucune note trouvée";
    }

    $stmt->close();
    $conn->close();
}
