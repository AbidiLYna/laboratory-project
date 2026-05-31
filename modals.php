!DOCTYPE html>
<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['log_u'])) {
    header('location:loginpage.php'); exit;
}

$error = [];
$success = "";

if (isset($_POST["submit"])) {
    $univ_name     = mysqli_real_escape_string($link, $_POST["univ_name"]);
    $univ_email    = mysqli_real_escape_string($link, $_POST["univ_email"]);
    $univ_mobile   = mysqli_real_escape_string($link, $_POST["univ_mobile"]);
    $univ_location = mysqli_real_escape_string($link, $_POST["univ_location"]);
    $univ_password = $_POST["univ_password"];

    $select = "SELECT * FROM universities WHERE email = '$univ_email' LIMIT 1";
    $result = mysqli_query($link, $select) or die(mysqli_error($link));

    if (mysqli_num_rows($result) > 0) {
        $e = 'Cet email existe déjà dans la base de données.';
        array_push($error, $e);
    } else {
        $hashed_password = password_hash($univ_password, PASSWORD_DEFAULT);
        $insert = "INSERT INTO universities (name, email, mobile, location, password) 
                   VALUES ('$univ_name', '$univ_email', '$univ_mobile', '$univ_location', '$hashed_password')";
        if (mysqli_query($link, $insert)) {
            $success = "Université enregistrée avec succès !";
        } else {
            $e = mysqli_error($link);
            array_push($error, $e);
        }
    }
}
?>
<html dir="ltr" lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>University Management | Academic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        input, select, textarea {
            display: block;
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
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
                    <div class="p-4 w-full bg-neutral-700 rounded-lg text-white flex gap-4 items-center">
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
                    <h4 class="text-white text-xl font-bold">Enregistrement Université</h4>
                    <p class="text-white opacity-80">Gérer les établissements universitaires</p>
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

                <form id="register" action="modals.php" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="block mb-2 font-bold text-gray-700">Nom Université</label>
                            <input type="text" name="univ_name" placeholder="Ex: USTHB" required>
                        </div>
                        <div class="form-group">
                            <label class="block mb-2 font-bold text-gray-700">Email Contact</label>
                            <input type="email" name="univ_email" placeholder="email@univ.dz" required>
                        </div>
                        <div class="form-group">
                            <label class="block mb-2 font-bold text-gray-700">Téléphone</label>
                            <input type="text" name="univ_mobile" placeholder="021 XX XX XX" required>
                        </div>
                        <div class="form-group">
                            <label class="block mb-2 font-bold text-gray-700">Localisation</label>
                            <input type="text" name="univ_location" placeholder="Ville, Algérie" required>
                        </div>
                        <div class="form-group col-span-2">
                            <label class="block mb-2 font-bold text-gray-700">Mot de passe</label>
                            <input type="password" name="univ_password" placeholder="********" required>
                        </div>
                    </div>
                    <div class="flex justify-end gap-4 mt-8">
                        <button type="reset" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-300">Annuler</button>
                        <button type="submit" name="submit" class="bg-emerald-600 text-white px-8 py-2 rounded-lg font-bold hover:bg-emerald-700">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>

