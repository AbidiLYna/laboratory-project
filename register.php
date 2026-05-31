<?php
include 'connection.php';
session_start();

if (isset($_SESSION['log_u'])) {
    header('location: modals.php');
    exit;
}

$error = [];
$success = "";

if (isset($_POST['submit_register'])) {
    $nom          = mysqli_real_escape_string($link, $_POST['nom']);
    $prenom       = mysqli_real_escape_string($link, $_POST['prenom']);
    $date_naiss   = mysqli_real_escape_string($link, $_POST['date_naissance']);
    $sexe         = mysqli_real_escape_string($link, $_POST['sexe']);
    $email        = mysqli_real_escape_string($link, $_POST['email']);
    $telephone    = mysqli_real_escape_string($link, $_POST['telephone']);
    $role         = mysqli_real_escape_string($link, $_POST['role']);
    $grade        = mysqli_real_escape_string($link, $_POST['grade']);
    $universite   = mysqli_real_escape_string($link, $_POST['universite']);
    $faculte      = mysqli_real_escape_string($link, $_POST['faculte']);
    $departement  = mysqli_real_escape_string($link, $_POST['departement']);
    $laboratoire  = mysqli_real_escape_string($link, $_POST['laboratoire']);
    $equipe       = mysqli_real_escape_string($link, $_POST['equipe']);
    $password     = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        array_push($error, "Les champs Nom, Prénom, Email et Mot de passe sont obligatoires.");
    } elseif ($password !== $confirm_pass) {
        array_push($error, "Les mots de passe ne correspondent pas.");
    } elseif (strlen($password) < 6) {
        array_push($error, "Le mot de passe doit contenir au moins 6 caractères.");
    } else {
        $check = mysqli_query($link, "SELECT id FROM users WHERE email = '$email' LIMIT 1");
        if ($check && mysqli_num_rows($check) > 0) {
            array_push($error, "Un compte avec cet email existe déjà.");
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (nom, prenom, date_naissance, sexe, email, telephone, role, grade, university, faculte, department, laboratory, equipe, password)
                      VALUES ('$nom', '$prenom', '$date_naiss', '$sexe', '$email', '$telephone', '$role', '$grade', '$universite', '$faculte', '$departement', '$laboratoire', '$equipe', '$hashed')";
            if (mysqli_query($link, $query)) {
                $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            } else {
                array_push($error, "Erreur : " . mysqli_error($link));
            }
        }
    }
}

$universities = mysqli_query($link, "SELECT name FROM universities ORDER BY name");
$faculties    = mysqli_query($link, "SELECT name FROM faculties ORDER BY name");
$departments  = mysqli_query($link, "SELECT name FROM departments ORDER BY name");
$laboratories = mysqli_query($link, "SELECT name FROM laboratories ORDER BY name");
$teams        = mysqli_query($link, "SELECT name FROM teams ORDER BY name");
?>
<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Créer un compte | Academic Management</title>
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
            background-color: #fff;
        }
        input:focus, select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
        }
        .section-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #10b981;
            border-bottom: 2px solid #d1fae5;
            padding-bottom: 6px;
            margin-bottom: 16px;
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
                <i class="fa-solid fa-user-plus"></i><span>Créer un compte</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="lg:ml-[300px] w-full lg:w-[calc(100%-300px)] bg-neutral-800 min-h-screen px-6 lg:px-10 pt-12 flex flex-col">

        <div class="w-full pb-4">
            <div class="bg-emerald-600 p-6 rounded-lg">
                <h4 class="text-white text-xl font-bold">Créer un compte</h4>
                <p class="text-white opacity-80">Remplissez le formulaire pour créer votre compte</p>
            </div>
        </div>

        <div class="w-full flex-1 pb-20">
            <div class="bg-white rounded-lg p-8 mt-4 shadow-xl">

                <?php if (!empty($error)): ?>
                    <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
                        <?php foreach ($error as $e) echo "<p><i class='fa-solid fa-circle-exclamation mr-2'></i>$e</p>"; ?>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                        <p><i class="fa-solid fa-check-circle mr-2"></i><?php echo $success; ?></p>
                        <a href="loginpage.php" class="mt-3 inline-block text-emerald-700 font-bold hover:underline">
                            <i class="fa-solid fa-right-to-bracket mr-1"></i>Se connecter maintenant
                        </a>
                    </div>
                <?php endif; ?>

                <form action="register.php" method="POST">

                    <!-- Section 1: Informations personnelles -->
                    <div class="section-title"><i class="fa-solid fa-user mr-2"></i>Informations personnelles</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="nom" placeholder="Votre nom" required
                                   value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="prenom" placeholder="Votre prénom" required
                                   value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Date de Naissance</label>
                            <input type="date" name="date_naissance"
                                   value="<?php echo isset($_POST['date_naissance']) ? htmlspecialchars($_POST['date_naissance']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Sexe</label>
                            <div class="flex gap-8 mt-3">
                                <label class="flex items-center gap-2 cursor-pointer text-gray-700 font-medium">
                                    <input type="radio" name="sexe" value="Homme" style="width:auto; padding:0;"
                                           <?php echo (isset($_POST['sexe']) && $_POST['sexe'] == 'Homme') ? 'checked' : ''; ?>>
                                    <i class="fa-solid fa-mars text-blue-500"></i> Homme
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer text-gray-700 font-medium">
                                    <input type="radio" name="sexe" value="Femme" style="width:auto; padding:0;"
                                           <?php echo (isset($_POST['sexe']) && $_POST['sexe'] == 'Femme') ? 'checked' : ''; ?>>
                                    <i class="fa-solid fa-venus text-pink-500"></i> Femme
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Informations de contact -->
                    <div class="section-title"><i class="fa-solid fa-address-card mr-2"></i>Informations de contact</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Adresse Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" placeholder="email@université.dz" required
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Téléphone</label>
                            <input type="text" name="telephone" placeholder="05X XXX XX XX"
                                   value="<?php echo isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : ''; ?>">
                        </div>
                    </div>

                    <!-- Section 3: Informations professionnelles -->
                    <div class="section-title"><i class="fa-solid fa-briefcase mr-2"></i>Informations professionnelles</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Rôle</label>
                            <input type="text" name="role" placeholder="Ex: Chercheur, Enseignant..."
                                   value="<?php echo isset($_POST['role']) ? htmlspecialchars($_POST['role']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Grade</label>
                            <select name="grade">
                                <option value="">-- Sélectionner --</option>
                                <option value="Professeur" <?php echo (isset($_POST['grade']) && $_POST['grade']=='Professeur') ? 'selected' : ''; ?>>Professeur</option>
                                <option value="Maître de Conférences A" <?php echo (isset($_POST['grade']) && $_POST['grade']=='Maître de Conférences A') ? 'selected' : ''; ?>>Maître de Conférences A</option>
                                <option value="Maître de Conférences B" <?php echo (isset($_POST['grade']) && $_POST['grade']=='Maître de Conférences B') ? 'selected' : ''; ?>>Maître de Conférences B</option>
                                <option value="Maître Assistant A" <?php echo (isset($_POST['grade']) && $_POST['grade']=='Maître Assistant A') ? 'selected' : ''; ?>>Maître Assistant A</option>
                                <option value="Maître Assistant B" <?php echo (isset($_POST['grade']) && $_POST['grade']=='Maître Assistant B') ? 'selected' : ''; ?>>Maître Assistant B</option>
                                <option value="Doctorant" <?php echo (isset($_POST['grade']) && $_POST['grade']=='Doctorant') ? 'selected' : ''; ?>>Doctorant</option>
                                <option value="Chercheur" <?php echo (isset($_POST['grade']) && $_POST['grade']=='Chercheur') ? 'selected' : ''; ?>>Chercheur</option>
                            </select>
                        </div>
                    </div>

                    <!-- Section 4: Affectation -->
                    <div class="section-title"><i class="fa-solid fa-map-pin mr-2"></i>Affectation</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Université</label>
                            <select name="universite">
                                <option value="">-- Sélectionner --</option>
                                <?php if($universities): while($u = mysqli_fetch_assoc($universities)): ?>
                                    <option value="<?php echo htmlspecialchars($u['name']); ?>"
                                        <?php echo (isset($_POST['universite']) && $_POST['universite']==$u['name']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($u['name']); ?>
                                    </option>
                                <?php endwhile; endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Faculté</label>
                            <select name="faculte">
                                <option value="">-- Sélectionner --</option>
                                <?php if($faculties): while($f = mysqli_fetch_assoc($faculties)): ?>
                                    <option value="<?php echo htmlspecialchars($f['name']); ?>"
                                        <?php echo (isset($_POST['faculte']) && $_POST['faculte']==$f['name']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($f['name']); ?>
                                    </option>
                                <?php endwhile; endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Département</label>
                            <select name="departement">
                                <option value="">-- Sélectionner --</option>
                                <?php if($departments): while($d = mysqli_fetch_assoc($departments)): ?>
                                    <option value="<?php echo htmlspecialchars($d['name']); ?>"
                                        <?php echo (isset($_POST['departement']) && $_POST['departement']==$d['name']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($d['name']); ?>
                                    </option>
                                <?php endwhile; endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Laboratoire</label>
                            <select name="laboratoire">
                                <option value="">-- Sélectionner --</option>
                                <?php if($laboratories): while($l = mysqli_fetch_assoc($laboratories)): ?>
                                    <option value="<?php echo htmlspecialchars($l['name']); ?>"
                                        <?php echo (isset($_POST['laboratoire']) && $_POST['laboratoire']==$l['name']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($l['name']); ?>
                                    </option>
                                <?php endwhile; endif; ?>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block mb-2 font-bold text-gray-700">Équipe</label>
                            <select name="equipe">
                                <option value="">-- Sélectionner --</option>
                                <?php if($teams): while($t = mysqli_fetch_assoc($teams)): ?>
                                    <option value="<?php echo htmlspecialchars($t['name']); ?>"
                                        <?php echo (isset($_POST['equipe']) && $_POST['equipe']==$t['name']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($t['name']); ?>
                                    </option>
                                <?php endwhile; endif; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Section 5: Sécurité -->
                    <div class="section-title"><i class="fa-solid fa-shield-halved mr-2"></i>Sécurité</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Mot de passe <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password" id="pass1" placeholder="Min. 6 caractères" required>
                                <button type="button" onclick="toggle('pass1','eye1')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fa-solid fa-eye" id="eye1"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-gray-700">Confirmer Mot de passe <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="confirm_password" id="pass2" placeholder="Répétez le mot de passe" required>
                                <button type="button" onclick="toggle('pass2','eye2')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fa-solid fa-eye" id="eye2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                        <a href="loginpage.php" class="text-emerald-600 font-bold hover:underline text-sm">
                            <i class="fa-solid fa-arrow-left mr-1"></i>Retour à la connexion
                        </a>
                        <div class="flex gap-4">
                            <button type="reset" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-300">Effacer</button>
                            <button type="submit" name="submit_register"
                                class="bg-emerald-600 text-white px-8 py-2 rounded-lg font-bold hover:bg-emerald-700 flex items-center gap-2">
                                <i class="fa-solid fa-user-plus"></i> Créer un compte
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggle(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon  = document.getElementById(iconId);
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
