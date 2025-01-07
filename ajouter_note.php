<?php include "header.php" ?>
<div class="header_logo">
    <h1 class="page_title">Ajouter la note</h1>
    <!-- Inclure le menu -->
    <?php include('menu.php'); ?>
</div>
<div class="container">
    <div class="page_form">
        <form action="" method="POST">
            <?php
            require_once "connection.php";


            // Récupérer les cours
            $sql = "SELECT id, name FROM courses";
            $result = $conn->query($sql);
            ?>
            <!-- Sélectionner un cours -->
            <label for="cours_id" class="form-label">Cours:</label>
            <select name="cours_id" class="form-control" id="cours_id" required>
                <option value="">Sélectionnez un cours</option>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Aucun cours disponible</option>";
                }
                ?>
            </select>

            <!-- Sélectionner une évaluation -->
            <label for="evaluation_id" class="form-label">Évaluation:</label>
            <select name="evaluation_id" class="form-control" id="evaluation_id" required>
                <option value="">Sélectionnez une évaluation</option>
                <?php
                // Récupérer les évaluations
                $sql = "SELECT id, name, ponderation FROM evaluations";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . " " . $row['ponderation'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Aucune évaluation disponible</option>";
                }
                ?>
            </select>


            <!-- Nouvelle note -->
            <label for="nouvelle_note" class="form-label"> Note:</label>
            <input type="number" class="form-control" name="nouvelle_note" id="nouvelle_note" step="1" min="1" max="100" required>


            <!-- Sélectionner la nouvelle date -->
            <label for="date" class="form-label">Sélectionner une nouvelle date:</label>
            <input type="date" class="form-control" name="date" id="date" max="<?php echo date('Y-m-d') ?>" required>


            <button type="submit" class="btn btn-primary mt-3">Ajouter la note</button>
        </form>

        <?php

        // Traitement du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cours_id = $_POST['cours_id'];
            $evaluation_id = $_POST['evaluation_id'];
            $nouvelle_note = $_POST['nouvelle_note'];
            $nouvelle_date = $_POST['date'];

            // Validation des champs
            if (empty($cours_id) || empty($evaluation_id) || empty($nouvelle_note) || empty($nouvelle_date)) {
                echo "<span class='text-danger m-5'>Veuillez remplir tous les champs.</span>";
            } else {
                // check 
                $sql = "SELECT id from resultats where id_cours='$cours_id' and id_evaluation='$evaluation_id'";
                $res = $conn->query($sql);
                if ($res->num_rows > 0) {
                    $data = $res->fetch_assoc();
                    echo "<span class='text-dark pt-5'>Cette note existe.</span> <a href='modifier_note.php?id=" . $data['id'] . "'>Modifier la note</a>";
                } else {
                    // Mise à jour de la note et de la date
                    $sql = "INSERT INTO resultats SET note='$nouvelle_note', date_note='$nouvelle_date', id_cours='$cours_id', id_evaluation='$evaluation_id'";
                    if ($conn->query($sql) === TRUE) {
                        echo "<span class='text-success p-5'>Note mise à jour avec succès!</span>";
                    } else {
                        echo "<span class='text-danger p-5'>Erreur lors de la mise à jour</span>";
                    }
                }
            }
        }
        ?>
    </div>
</div>


<?php include "footer.php" ?>
