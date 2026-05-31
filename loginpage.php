<?php
include 'connection.php';
session_start();

if (isset($_SESSION['log_u'])) {
    header('location: modals.php');
    exit;
}

$error = [];

if (isset($_POST['submit_login'])) {
    $email    = mysqli_real_escape_string($link, $_POST['email']);
    $password = $_POST['password'];

    // Chercher dans universities (admins)
    $result = mysqli_query($link, "SELECT * FROM universities WHERE email = '$email' LIMIT 1");

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['log_u']  = $user['id'];
            $_SESSION['nom']    = $user['name'];
            $_SESSION['prenom'] = '';
            $_SESSION['type']   = 'university';
            header('location: modals.php');
            exit;
        } else {
            array_push($error, "Mot de passe incorrect.");
        }
    } else {
        // Chercher dans users (comptes créés via register)
        $result2 = mysqli_query($link, "SELECT * FROM users WHERE email = '$email' LIMIT 1");
        if ($result2 && mysqli_num_rows($result2) > 0) {
            $user2 = mysqli_fetch_assoc($result2);
            if (isset($user2['password']) && password_verify($password, $user2['password'])) {
                $_SESSION['log_u']  = $user2['id'];
                $_SESSION['nom']    = $user2['nom'];
                $_SESSION['prenom'] = isset($user2['prenom']) ? $user2['prenom'] : '';
                $_SESSION['type']   = 'user';
                header('location: modals.php');
                exit;
            } else {
                array_push($error, "Mot de passe incorrect.");
            }
        } else {
            array_push($error, "Aucun compte trouvé avec cet email.");
        }
    }
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion | Academic Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        input, select {
            display: block;
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }
        input:focus, select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
        }
    </style>
</head>
<body class="w-full min-h-screen bg-neutral-800">
<div class="flex w-full min-h-screen">

    <!-- Sidebar -->
    <div class="hidden lg:flex w-[300px] bg-neutral-800 border-r-2 border-neutral-700 flex-col justify-between items-center py-6 px-8 fixed left-0 top-0 h-full">
        <div class="flex flex-col gap-6 w-full">
            <div class="flex gap-4 items-center justify-center scale-[1.2] mt-4">
                <i class="fa-solid fa-university fa-2xl text-emerald-200"></i>
                <p class="text-white font-semibold">ACADEMIC MGMT</p>
            </div>
            <div class="flex flex-col items-center mt-4">
                <div class="rounded-full h-[80px] w-[80px] bg-emerald-500 flex items-center justify-center">
                    <i class="fa-solid fa-user-tie fa-2x text-white"></i>
                </div>
                <p class="text-white text-base mt-3">Administrateur</p>
                <p class="px-6 py-1 border-neutral-700 text-white border-2 mt-2 rounded-full text-xs">Admin Profile</p>
                <nav class="flex flex-col w-full items-center mt-6 gap-2">
                    <div class="p-3 w-full rounded-lg text-neutral-500 flex gap-4 items-center"><i class="fa-solid fa-home"></i><span>Université</span></div>
                    <div class="p-3 w-full rounded-lg text-neutral-500 flex gap-4 items-center"><i class="fa-solid fa-building"></i><span>Faculté</span></div>
                    <div class="p-3 w-full rounded-lg text-neutral-500 flex gap-4 items-center"><i class="fa-solid fa-layer-group"></i><span>Département</span></div>
                    <div class="p-3 w-full rounded-lg text-neutral-500 flex gap-4 items-center"><i class="fa-solid fa-flask"></i><span>Laboratoire</span></div>
                    <div class="p-3 w-full rounded-lg text-neutral-500 flex gap-4 items-center"><i class="fa-solid fa-users"></i><span>Équipe</span></div>
                    <div class="p-3 w-full rounded-lg text-neutral-500 flex gap-4 items-center"><i class="fa-solid fa-user-graduate"></i><span>Membre</span></div>
                </nav>
            </div>
        </div>
        <div class="w-full">
            <div class="p-4 w-full bg-neutral-700 rounded-lg text-white flex gap-4 items-center">
                <i class="fa-solid fa-right-to-bracket"></i><span>Connexion</span>
            </div>
        </div>
    </div>

    <!-- Main -->
    <div class="lg:ml-[300px] w-full lg:w-[calc(100%-300px)] bg-neutral-800 min-h-screen px-6 lg:px-10 pt-12 flex flex-col">
        <div class="w-full pb-4">
            <div class="bg-emerald-600 p-6 rounded-lg">
                <h4 class="text-white text-xl font-bold">Connexion</h4>
                <p class="text-white opacity-80">Accédez à votre espace de gestion académique</p>
            </div>
        </div>

        <div class="w-full flex-1 pb-20">
            <div class="bg-white rounded-lg p-8 mt-4 shadow-xl max-w-2xl">

                <?php if (!empty($error)): ?>
                    <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
                        <?php foreach ($error as $e) echo "<p><i class='fa-solid fa-circle-exclamation mr-2'></i>$e</p>"; ?>
                    </div>
                <?php endif; ?>

                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center">
                        <i class="fa-solid fa-lock text-emerald-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Se connecter</h2>
                        <p class="text-gray-500 text-sm">Entrez vos identifiants pour accéder au système</p>
                    </div>
                </div>

                <form action="loginpage.php" method="POST">
                    <div class="flex flex-col gap-5">
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">
                                <i class="fa-solid fa-envelope mr-2 text-emerald-600"></i>Adresse Email
                            </label>
                            <input type="email" name="email" placeholder="email@université.dz" required
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">
                                <i class="fa-solid fa-key mr-2 text-emerald-600"></i>Mot de passe
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password_field" placeholder="••••••••" required>
                                <button type="button" onclick="togglePassword()"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fa-solid fa-eye" id="eye_icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-8">
                        <button type="reset" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-300">Effacer</button>
                        <button type="submit" name="submit_login"
                            class="bg-emerald-600 text-white px-8 py-2 rounded-lg font-bold hover:bg-emerald-700 flex items-center gap-2">
                            <i class="fa-solid fa-right-to-bracket"></i> Connexion
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <p class="text-gray-500 text-sm">Vous n'avez pas de compte ?</p>
                    <a href="register.php"
                       class="inline-block mt-3 text-emerald-600 font-bold hover:text-emerald-800 hover:underline text-base">
                        <i class="fa-solid fa-user-plus mr-2"></i>Créer un compte
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const field = document.getElementById('password_field');
    const icon  = document.getElementById('eye_icon');
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>
