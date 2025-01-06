<?php include "header.php" ?>
<div class="header_logo">
    <h1 class="page_title">Liste des notes globales</h1>
    <!-- Inclure le menu -->
    <?php include('menu.php'); ?>
</div>

<div class="container">
    <div class="page_form">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Cours</th>
                    <th scope="col">Note finale</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once "connection.php";

                $sql = "
                SELECT courses.id AS id_cours, courses.name AS nom_cours, resultats.id_evaluation, resultats.note
                FROM resultats
                INNER JOIN courses ON courses.id = resultats.id_cours
            ";

                $result = $conn->query($sql);

                $courses = [];

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $courseId = $row['id_cours'];
                        $evaluation = $row['id_evaluation'];
                        $note = $row['note'];

                        // Group the notes by course and evaluation type
                        if (!isset($courses[$courseId])) {
                            $courses[$courseId] = ['nom' => $row['nom_cours'], 'notes' => []];
                        }

                        // Assign the note to the appropriate evaluation
                        $courses[$courseId]['notes'][$evaluation] = $note;
                    }
                }

                // Function to calculate the median of three numbers
                function calculate_median($intra, $finale, $projet)
                {
                    $grades = [$intra, $finale, $projet];
                    sort($grades);
                    return $grades[1]; // Median of three sorted numbers
                }

                foreach ($courses as $course) {
                    $notes = $course['notes'];

                    // Check if the course has all three evaluations
                    if (isset($notes[1], $notes[2], $notes[3])) { // Assuming id_evaluation 1 = intra, 2 = finale, 3 = projet
                        $intra = $notes[1];
                        $finale = $notes[2];
                        $projet = $notes[3];

                        // Calculate the median of the three notes
                        $median = calculate_median($intra, $finale, $projet);

                        echo "<tr>";
                        echo "<td>" . $course['name'] . "</td>";
                        echo "<td>" . $intra . "</td>";
                        echo "<td>" . $finale . "</td>";
                        echo "<td>" . $projet . "</td>";
                        echo "<td>" . $median . "</td>";
                        echo "</tr>";
                    }
                }


                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include "footer.php" ?>