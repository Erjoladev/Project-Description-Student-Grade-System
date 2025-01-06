<?php include "header.php" ?>
<div class="header_logo">
    <h1 class="page_title">Liste des notes détaillées</h1>
    <!-- Inclure le menu -->
    <?php include('menu.php'); ?>
</div>

<div class="container">
    <div class="page_form">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Cours</th>
                    <th scope="col">Évaluation</th>
                    <th scope="col">Note</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once "connection.php";

                // Requête pour récupérer les notes triées par courses et par type d'évaluation (Intra -> Examen final -> Projet)
                $sql = "SELECT courses.name AS cours_nom, evaluations.name AS evaluation_type, resultats.note, resultats.date_note 
            FROM resultats
            INNER JOIN courses ON resultats.id_cours = courses.id
            INNER JOIN evaluations ON resultats.id_evaluation = evaluations.id
            ORDER BY courses.name ASC, 
            FIELD(evaluations.name, 'Intra', 'Examen final', 'Projet') ASC";

                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    // Afficher les résultats sous forme de liste
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['cours_nom'] . "</td>";
                        echo "<td>" . $row['evaluation_type'] . "</td>";
                        echo "<td>" . $row['note'] . "</td>";
                        echo "<td>" . $row['date_note'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "Aucune note disponible.";
                }

                // Fermer la connexion
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include "footer.php" ?>