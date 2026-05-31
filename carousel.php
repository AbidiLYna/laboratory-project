<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['log_u'])) {
    header('location:loginpage.php'); exit;
}

$error = [];
$success = "";

if (isset($_POST['submit_dept'])) {
    $nom_dept = mysqli_real_escape_string($link, $_POST['dept_name']);
    $nom_fac  = mysqli_real_escape_string($link, $_POST['faculty_name']);
    $nom_hod  = mysqli_real_escape_string($link, $_POST['hod_name']);

    $stmt_fac = $link->prepare("SELECT id FROM faculties WHERE name = ?");
    $stmt_fac->bind_param("s", $nom_fac);
    $stmt_fac->execute();
    $res_fac = $stmt_fac->get_result();

    if ($res_fac->num_rows > 0) {
        $fac_row = $res_fac->fetch_assoc();
        $faculty_id = $fac_row['id'];

        $check = $link->query("SELECT * FROM departments WHERE name='$nom_dept' AND faculty_id='$faculty_id'");
        if (mysqli_num_rows($check) > 0) {
            array_push($error, "Ce département existe déjà pour cette faculté.");
        } else {
            $query = "INSERT INTO departments (name, faculty_id, hod_name) VALUES ('$nom_dept', '$faculty_id', '$nom_hod')";
            if (mysqli_query($link, $query)) {
                $success = "Département ajouté avec succès !";
            } else {
                array_push($error, mysqli_error($link));
            }
        }
    } else {
        array_push($error, "La faculté '$nom_fac' n'existe pas. Veuillez d'abord l'ajouter dans la page Faculté.");
    }
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Department Management | Academic</title>
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
                        <i class="fa-solid fa-home"></i>
                        <a href="modals.php">Université</a>
                    </div>
                    <div class="p-4 w-full hover:bg-neutral-700 rounded-lg text-white flex gap-4 items-center transition">
                        <i class="fa-solid fa-building"></i>
                        <a href="notifications.php">Faculté</a>
                    </div>
                    <div class="p-4 w-full bg-neutral-700 rounded-lg text-white flex gap-4 items-center">
                        <i class="fa-solid fa-layer-group"></i>
                        <a href="carousel.php">Département</a>
                    </div>
                    <div class="p-4 w-full hover:bg-neutral-700 rounded-lg text-white flex gap-4 items-center transition">
                        <i class="fa-solid fa-flask"></i>
                        <a href="range-slider.php">Laboratoire</a>
                    </div>
                    <div class="p-4 w-full hover:bg-neutral-700 rounded-lg text-white flex gap-4 items-center transition">
                        <i class="fa-solid fa-users"></i>
                        <a href="rating.php">Équipe</a>
                    </div>
                    <div class="p-4 w-full hover:bg-neutral-700 rounded-lg text-white flex gap-4 items-center transition">
                        <i class="fa-solid fa-user-graduate"></i>
                        <a href="icons.php">Membre</a>
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
                <i class="fa-solid fa-power-off"></i>
                <a href="logout.php">Déconnecter</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="ml-[300px] w-[calc(100%-300px)] bg-neutral-800 overflow-hidden h-screen px-10 pt-12">
        <div class="flex w-full items-center justify-between pb-4">
            <div class="w-full">
                <div class="bg-emerald-600 p-6 rounded-lg">
                    <h4 class="text-white text-xl font-bold">Gestion des Départements</h4>
                    <p class="text-white opacity-80">Ajouter un nouveau département</p>
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

                <form action="carousel.php" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block mb-2 font-bold text-gray-700">Nom du Département</label>
                            <input type="text" name="dept_name" placeholder="Ex: Département d'Informatique" required>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Faculté</label>
                            <select name="faculty_name">
                                <option>Faculty of Technology</option>
                                <option>Faculty of Sciences</option>
                                <option>Faculty of Medicine</option>
                                <option>Faculty of Economics</option>
                                <option>Faculty of Law</option>
                                <option>Faculty of Language</option>
                                <option>Faculty of Social Sciences</option>
                                <option>Faculty of Islamic Theology</option>
                                <option>Faculty of Engineering</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Chef de Département (HOD)</label>
                            <input type="text" name="hod_name" placeholder="Nom du chef de département">
                        </div>
                    </div>
                    <div class="flex justify-end gap-4 mt-8">
                        <button type="reset" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-300">Annuler</button>
                        <button type="submit" name="submit_dept" class="bg-emerald-600 text-white px-8 py-2 rounded-lg font-bold hover:bg-emerald-700">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
