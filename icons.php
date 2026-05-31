<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['log_u'])) {
    header('location:loginpage.php'); exit;
}

$error = [];
$success = "";

if (isset($_POST['submit_member'])) {
    $nom_membre   = mysqli_real_escape_string($link, $_POST['member_name']);
    $date_naiss   = mysqli_real_escape_string($link, $_POST['dob']);
    $email        = mysqli_real_escape_string($link, $_POST['member_email']);
    $genre        = mysqli_real_escape_string($link, $_POST['member_gender']);
    $tel          = mysqli_real_escape_string($link, $_POST['member_mobile']);
    $lieu         = mysqli_real_escape_string($link, $_POST['member_location']);
    $role         = mysqli_real_escape_string($link, $_POST['member_role']);
    $nom_equipe   = mysqli_real_escape_string($link, $_POST['team_name']);
    $nom_lab      = mysqli_real_escape_string($link, $_POST['lab_name']);
    $date_recrut  = mysqli_real_escape_string($link, $_POST['recruitment_date']);

    $stmt_lab = $link->prepare("SELECT id FROM laboratories WHERE name = ?");
    $stmt_lab->bind_param("s", $nom_lab);
    $stmt_lab->execute();
    $res_lab = $stmt_lab->get_result();

    if ($res_lab->num_rows > 0) {
        $lab_row = $res_lab->fetch_assoc();
        $laboratory_id = $lab_row['id'];

        $stmt_team = $link->prepare("SELECT id FROM teams WHERE name = ? AND laboratory_id = ?");
        $stmt_team->bind_param("si", $nom_equipe, $laboratory_id);
        $stmt_team->execute();
        $res_team = $stmt_team->get_result();
        $team_id = NULL;
        if ($res_team->num_rows > 0) {
            $team_row = $res_team->fetch_assoc();
            $team_id = $team_row['id'];
        }

        $check = $link->query("SELECT * FROM members WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            array_push($error, "Un membre avec cet email existe déjà.");
        } else {
            $team_val = $team_id ? "'$team_id'" : "NULL";
            $query = "INSERT INTO members (name, team_id, laboratory_id, date_of_birth, email, gender, mobile, location, role, recruitment_date) 
                      VALUES ('$nom_membre', $team_val, '$laboratory_id', '$date_naiss', '$email', '$genre', '$tel', '$lieu', '$role', '$date_recrut')";
            if (mysqli_query($link, $query)) {
                $success = "Membre ajouté avec succès !";
            } else {
                array_push($error, mysqli_error($link));
            }
        }
    } else {
        array_push($error, "Le laboratoire '$nom_lab' n'existe pas. Veuillez d'abord l'ajouter dans la page Laboratoire.");
    }
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Member Management | Academic</title>
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
                    <div class="p-4 w-full hover:bg-neutral-700 rounded-lg text-white flex gap-4 items-center transition">
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
                    <div class="p-4 w-full bg-neutral-700 rounded-lg text-white flex gap-4 items-center">
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
                    <h4 class="text-white text-xl font-bold">Gestion des Membres</h4>
                    <p class="text-white opacity-80">Ajouter un nouveau membre ou chercheur</p>
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

                <form action="icons.php" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block mb-2 font-bold text-gray-700">Nom Complet</label>
                            <input type="text" name="member_name" placeholder="Prénom Nom" required>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Date de Naissance</label>
                            <input type="date" name="dob">
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Genre</label>
                            <select name="member_gender">
                                <option value="Male">Masculin</option>
                                <option value="Female">Féminin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Email</label>
                            <input type="email" name="member_email" placeholder="membre@univ.dz" required>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Téléphone</label>
                            <input type="text" name="member_mobile" placeholder="05X XXX XXX">
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Localisation</label>
                            <input type="text" name="member_location" placeholder="Ville, Algérie">
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Rôle</label>
                            <select name="member_role">
                                <option value="Doctoral">Doctoral</option>
                                <option value="Researcher">Chercheur</option>
                                <option value="Teacher">Enseignant</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Équipe</label>
                            <input type="text" name="team_name" placeholder="Nom de l'équipe (optionnel)">
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Laboratoire</label>
                            <input type="text" name="lab_name" placeholder="Nom exact du laboratoire" required>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Date de Recrutement</label>
                            <input type="date" name="recruitment_date">
                        </div>
                    </div>
                    <div class="flex justify-end gap-4 mt-8">
                        <button type="reset" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-300">Annuler</button>
                        <button type="submit" name="submit_member" class="bg-emerald-600 text-white px-8 py-2 rounded-lg font-bold hover:bg-emerald-700">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
