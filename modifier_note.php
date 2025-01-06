<?php include "header.php" ?>
<div class="header_logo">
    <h1 class="page_title">Modifier note</h1>
    <!-- Inclure le menu -->
    <?php include('menu.php'); ?>
</div>
<div class="container">
    <div class="page_form">
        <form action="" method="POST" id="form_elements">
            <?php
            require_once "connection.php";
            // Requête pour récupérer les cours ayant des notes
            $sql = "SELECT DISTINCT courses.id, courses.name FROM courses INNER JOIN resultats ON courses.id = resultats.id_cours";
            $option = "";
            if (!empty($_GET['id'])) {
                $id = (int)$_GET['id'];
                $sql = "SELECT DISTINCT courses.id, courses.name FROM courses INNER JOIN resultats ON courses.id = resultats.id_cours where resultats.id='$id'";
                $option = "selected";
            }
            $result = $conn->query($sql);
            ?>
            <!-- Sélectionner un cours -->
            <label for="cours" class="form-label">Courses:</label>
            <select name="cours" class="form-control" id="cours" required>
                <option value="">Sélectionnez un cours</option>
                <?php
                

                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "' $option>" . $row['name'] . "</option>";
                }
                ?>
            </select>

            <!-- Sélectionner une évaluation -->
            <label for="evaluation" class="form-label">Évaluations:</label>
            <select name="evaluation" class="form-control" id="evaluation" required>
                <option value="">Sélectionnez une évaluation</option>
                <?php
                // Requête pour récupérer les types d'évaluation
                $sql = "SELECT DISTINCT evaluations.id, evaluations.name, ponderation FROM evaluations  INNER JOIN resultats ON evaluations.id = resultats.id_evaluation";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . " " . $row['ponderation'] . "</option>";
                }
                ?>
            </select>
            <div class="">
                <label for="current_note" class="form-label">Note actuelle:</label>
                <input type="text" class="form-control" id="current_note" name="current_note" readonly disabled>
    
                <!-- Nouvelle note -->
                <label for="nouvelle_note" class="form-label">Nouvelle note:</label>
                <input type="number" class="form-control" id="nouvelle_note" name="nouvelle_note" step="1" min="1" max="100" required>
            </div>

            <!-- Sélectionner la nouvelle date -->
            <label for="date" class="form-label">Sélectionner une nouvelle date:</label>
            <input type="date" class="form-control" name="date" id="date" max="<?php echo date('Y-m-d') ?>" required>

            <!-- Soumettre le formulaire -->
            <button type="submit" class="btn btn-primary">Modifier la note</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cours_id = $_POST['cours'];
            $evaluation_id = $_POST['evaluation'];
            $nouvelle_note = $_POST['nouvelle_note'];
            $nouvelle_date = $_POST['date'];

            // Validation des champs
            if (empty($cours_id) || empty($evaluation_id) || empty($nouvelle_note) || empty($nouvelle_date)) {
                echo "<p class='text-danger p-5'>Veuillez remplir tous les champs.</p>";
            } else {
                $sql = "SELECT id from resultats where id_cours='$cours_id' and id_evaluation='$evaluation_id'";
                $res = $conn->query($sql);
                if ($res->num_rows < 1) {
                    $data = $res->fetch_assoc();
                    echo "<span class='text-dark pt-5'>Cette note n'existe pas.</span>";
                } else {
                    // Mise à jour de la note et de la date
                    $sql = "UPDATE resultats SET note='$nouvelle_note', date_note='$nouvelle_date' WHERE id_cours='$cours_id' AND id_evaluation='$evaluation_id'";
                    if ($conn->query($sql) === TRUE) {
                        echo "<p class='text-success p-5'>Note mise à jour avec succès!</p>";
                    } else {
                        echo "<p class='text-danger p-5'>Erreur lors de la mise à jour: " . $conn->error . "</p>";
                    }
                }
            }
        } ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#cours, #evaluation').on('change', function() {
            var coursId = $('#cours').val();
            var evalId = $('#evaluation').val();

            if (coursId && evalId) {
                // Make an AJAX request to fetch the current note
                $.ajax({
                    url: 'fetchNote.php',
                    method: 'POST',
                    data: {cours: coursId, evaluation: evalId},
                    success: function(response) {
                        $('#current_note').val(response);
                    }
                });
            }
        });
    });
</script>

<?php include "footer.php" ?>