<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['log_u'])) {
    header('location:loginpage.php'); exit;
}

$error = [];
$success = "";

if (isset($_POST['submit_lab'])) {
    $nom_lab       = mysqli_real_escape_string($link, $_POST['lab_name']);
    $nom_dept      = mysqli_real_escape_string($link, $_POST['dept_name']);
    $nom_dir       = mysqli_real_escape_string($link, $_POST['director_name']);
    $date_creation = mysqli_real_escape_string($link, $_POST['creation_date']); // type DATE
    $budget        = mysqli_real_escape_string($link, $_POST['budget']);         // decimal(15,2)
    $desc          = mysqli_real_escape_string($link, $_POST['lab_desc']);

    // Chercher l'ID du département par son nom
    $stmt_dept = $link->prepare("SELECT id FROM departments WHERE name = ?");
    $stmt_dept->bind_param("s", $nom_dept);
    $stmt_dept->execute();
    $res_dept = $stmt_dept->get_result();

    if ($res_dept->num_rows > 0) {
        $dept_row = $res_dept->fetch_assoc();
        $department_id = $dept_row['id'];

        // Vérif doublon
        $check = $link->query("SELECT * FROM laboratories WHERE name='$nom_lab' AND department_id='$department_id'");
        if (mysqli_num_rows($check) > 0) {
            array_push($error, "Ce laboratoire existe déjà pour ce département.");
        } else {
            // Colonnes réelles: name, department_id, director_name, creation_date (date), budget (decimal), description
            $query = "INSERT INTO laboratories (name, department_id, director_name, creation_date, budget, description) 
                      VALUES ('$nom_lab', '$department_id', '$nom_dir', '$date_creation', '$budget', '$desc')";
            if (mysqli_query($link, $query)) {
                $success = "Laboratoire ajouté avec succès !";
            } else {
                array_push($error, mysqli_error($link));
            }
        }
    } else {
        array_push($error, "Le département '$nom_dept' n'existe pas. Veuillez d'abord l'ajouter.");
    }
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laboratory Management | Academic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        input, select, textarea {
            display: block; width: 100%; padding: 10px 14px;
            border-radius: 8px; border: 1px solid #d1d5db;
            font-size: 0.95rem; outline: none; transition: border-color 0.2s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
        }
    </style>
</head>
<body class="w-full h-screen bg-neutral-800">
<div class="relative flex w-full h-screen">

    <!-- Sidebar -->
    <header class="h-screen py-6 w-[300px] bg-neutral-800 border-r-2 border-neutral-700 flex flex-col justify-between items-center fixed left-0 top-0 z-50">
        <div class="flex flex-col gap-6 w-full px-8">
            <div class="flex gap-4 items-center justify-center scale-[1.2]">
                <i class="fa-solid fa-university fa-2xl text-emerald-200"></i>
                <p class="text-white font-semibold">ACADEMIC MGMT</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="rounded-full h-[80px] w-[80px] bg-emerald-500 overflow-hidden flex items-center justify-center">
                    <i class="fa-solid fa-user-tie fa-2x text-white"></i>
                </div>
                <p class="text-white text-base mt-3">
                    <?php echo isset($_SESSION['nom']) ? ($_SESSION['nom'] . ' ' . $_SESSION['prenom']) : 'Administrateur'; ?>
                </p>
                <p class="px-6 py-1 border-neutral-700 text-white border-2 mt-2 rounded-full text-xs">Admin Profile</p>
                <nav class="flex flex-col w-full items-center mt-6 gap-2">
                    <div class="p-4 w-full hover:bg-neutral-700 rounded-lg text-white flex gap-4 items-center transition">
                        <i class="fa-solid fa-home"></i><a href="modals.php">Université</a>
                    </div>
                    <div class="p-4 w-full hover:bg-neutral-700 rounded-lg text-white flex gap-4 items-center transition">
                        <i class="fa-solid fa-building"></i><a href="notifications.php">Faculté</a>
                    </div>
                    <div class="p-4 w-full hover:bg-neutral-700 rounded-lg text-white flex gap-4 items-center transition">
                        <i class="fa-solid fa-layer-group"></i><a href="carousel.php">Département</a>
                    </div>
                    <div class="p-4 w-full bg-neutral-700 rounded-lg text-white flex gap-4 items-center">
                        <i class="fa-solid fa-flask"></i><a href="range-slider.php">Laboratoire</a>
                    </div>
                    <div class="p-4 w-full hover:bg-neutral-700 rounded-lg text-white flex gap-4 items-center transition">
                        <i class="fa-solid fa-users"></i><a href="rating.php">Équipe</a>
                    </div>
                    <div class="p-4 w-full hover:bg-neutral-700 rounded-lg text-white flex gap-4 items-center transition">
                        <i class="fa-solid fa-user-graduate"></i><a href="icons.php">Membre</a>
                    </div>
                                    <div class="p-4 w-full hover:bg-neutral-700 rounded-lg text-white flex gap-4 items-center transition">
                        <i class="fa-solid fa-table"></i>
                        <a href="table_bootstrap__3_.php">Data Table</a>
                    </div>
                </nav>
            </div>
        </div>
        <div class="px-8 w-full">
            <div class="p-4 w-full hover:bg-neutral-700 rounded-lg text-white flex gap-4 items-center transition">
                <i class="fa-solid fa-power-off"></i><a href="logout.php">Déconnecter</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="ml-[300px] w-[calc(100%-300px)] bg-neutral-800 overflow-hidden h-screen px-10 pt-12">
        <div class="flex w-full items-center justify-between pb-4">
            <div class="w-full">
                <div class="bg-emerald-600 p-6 rounded-lg">
                    <h4 class="text-white text-xl font-bold">Gestion des Laboratoires</h4>
                    <p class="text-white opacity-80">Ajouter un nouveau laboratoire</p>
                </div>
            </div>
        </div>
        <div class="w-full h-full overflow-y-scroll pb-20">
            <div class="bg-white rounded-lg p-8 mt-4 shadow-xl">

                <?php if (!empty($error)): ?>
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
                        <?php foreach ($error as $e) echo "<p>$e</p>"; ?>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                        <p><?php echo $success; ?></p>
                    </div>
                <?php endif; ?>

                <form action="range-slider.php" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block mb-2 font-bold text-gray-700">Nom du Laboratoire</label>
                            <input type="text" name="lab_name" placeholder="Ex: Lab d'Intelligence Artificielle" required>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Département</label>
                            <select name="dept_name">
                                <option>Department of Mathematics</option>
                                <option>Department of computer Sciences</option>
                                <option>Department of physics</option>
                                <option>Department of Chemistry</option>
                                <option>Department of Architecture</option>
                                <option>Department of Civil Engineering</option>
                                <option>Department of Philosophy</option>
                                <option>Department of History</option>
                                <option>Department of Library Science</option>
                                <option>Department of Psychology</option>
                                <option>Department of Sociology</option>
                                <option>Department of Archaeology</option>
                                <option>Department of Arabic language</option>
                                <option>Department of French language</option>
                                <option>Department of Foreign language</option>
                                <option>Department of law</option>
                                <option>Department of political science</option>
                                <option>Department of International relations</option>
                                <option>Department of Economics</option>
                                <option>Department of Business Administration</option>
                                <option>Department of Management</option>
                                <option>Department of Finance and Accounting</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Nom du Directeur</label>
                            <input type="text" name="director_name" placeholder="Nom du directeur">
                        </div>
                        <div>
                            <!-- creation_date est type DATE dans la BDD (pas datetime) -->
                            <label class="block mb-2 font-bold text-gray-700">Date de Création</label>
                            <input type="date" name="creation_date">
                        </div>
                        <div>
                            <!-- budget est decimal(15,2) dans la BDD -->
                            <label class="block mb-2 font-bold text-gray-700">Budget ($)</label>
                            <input type="number" step="0.01" name="budget" placeholder="Ex: 50000.00">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block mb-2 font-bold text-gray-700">Description</label>
                            <textarea name="lab_desc" rows="4" placeholder="Description du laboratoire..."></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-4 mt-8">
                        <button type="reset" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-300">Annuler</button>
                        <button type="submit" name="submit_lab" class="bg-emerald-600 text-white px-8 py-2 rounded-lg font-bold hover:bg-emerald-700">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>

