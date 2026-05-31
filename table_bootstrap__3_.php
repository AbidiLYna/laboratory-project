<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['log_u'])) {
    header('location:loginpage.php'); exit;
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Table | Academic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        * { scrollbar-width: auto; scrollbar-color: #000000 #333333; }
        *::-webkit-scrollbar { width: 2px; }
        *::-webkit-scrollbar-track { background: #333333; }
        *::-webkit-scrollbar-thumb { background-color: #000000; border-radius: 10px; border: 3px solid #333333; }
        table { border-collapse: collapse; width: 100%; }
        thead th { background-color: #059669; color: white; padding: 12px 16px; text-align: left; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        tbody tr { border-bottom: 1px solid #e5e7eb; transition: background 0.15s; }
        tbody tr:hover { background-color: #f0fdf4; }
        tbody td { padding: 11px 16px; font-size: 0.9rem; color: #374151; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .badge-prof { background: #d1fae5; color: #065f46; }
        .badge-doc  { background: #dbeafe; color: #1e40af; }
        .badge-cher { background: #fef3c7; color: #92400e; }
        select { background: transparent; border: none; outline: none; color: white; font-size: 0.9rem; }
        input[type=text] { background: transparent; border: none; outline: none; color: white; font-size: 0.95rem; }
        input[type=text]::placeholder { color: #9ca3af; }
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
                    <?php echo isset($_SESSION['nom']) ? ($_SESSION['nom'] . ' ' . (isset($_SESSION['prenom']) ? $_SESSION['prenom'] : '')) : 'Administrateur'; ?>
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
                    <div class="p-4 w-full hover:bg-neutral-700 rounded-lg text-white flex gap-4 items-center transition">
                        <i class="fa-solid fa-user-graduate"></i>
                        <a href="icons.php">Membre</a>
                    </div>
                    <div class="p-4 w-full bg-neutral-700 rounded-lg text-white flex gap-4 items-center">
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

        <!-- Header -->
        <div class="flex w-full items-center justify-between pb-4">
            <div class="w-full">
                <div class="bg-emerald-600 p-6 rounded-lg">
                    <h4 class="text-white text-xl font-bold">Data Table — Scientifiques</h4>
                    <p class="text-white opacity-80">Liste de tous les scientifiques enregistrés</p>
                </div>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <!-- Search ID -->
            <div class="flex items-center border-2 px-4 border-neutral-600 rounded-full bg-neutral-700">
                <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                <input type="text" id="searchId" placeholder="Rechercher par ID..." class="pl-3 py-2 min-w-[160px]" oninput="filterTable()">
            </div>
            <!-- Search Name -->
            <div class="flex items-center border-2 px-4 border-neutral-600 rounded-full bg-neutral-700">
                <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                <input type="text" id="searchName" placeholder="Rechercher par nom..." class="pl-3 py-2 min-w-[180px]" oninput="filterTable()">
            </div>
            <!-- Filter Type -->
            <div class="flex items-center border-2 px-4 border-neutral-600 rounded-full bg-neutral-700 py-2">
                <i class="fa-solid fa-filter text-gray-400 text-sm mr-2"></i>
                <select id="filterType" onchange="filterTable()">
                    <option value="all">Tous les types</option>
                    <option value="Professeur">Professeur</option>
                    <option value="Doctorant">Doctorant</option>
                    <option value="Chercheur">Chercheur</option>
                </select>
            </div>
            <!-- Add button -->
            <a href="add_scientifique.php"
               class="ml-auto bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-5 py-2 rounded-lg flex items-center gap-2 transition">
                <i class="fa-solid fa-plus"></i> Ajouter
            </a>
        </div>

        <!-- Table -->
        <div class="w-full h-[calc(100vh-280px)] overflow-y-scroll rounded-lg shadow-xl">
            <table id="mainTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Université</th>
                        <th>Faculté</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($link, "SELECT * FROM scientifiques") or die(mysqli_error($link));
                    while ($row = mysqli_fetch_assoc($res)) {
                        $id         = htmlspecialchars($row['id']);
                        $nom        = htmlspecialchars($row['nom']);
                        $type       = htmlspecialchars($row['type']);
                        $university = htmlspecialchars($row['university']);
                        $faculte    = htmlspecialchars($row['faculte']);
                        $email      = htmlspecialchars($row['email']);
                        $telephone  = htmlspecialchars($row['telephone']);

                        $badgeClass = 'badge-cher';
                        if ($type === 'Professeur') $badgeClass = 'badge-prof';
                        elseif ($type === 'Doctorant') $badgeClass = 'badge-doc';

                        echo "
                        <tr>
                            <td class='font-mono text-xs text-gray-500'>$id</td>
                            <td class='font-semibold text-gray-800'>$nom</td>
                            <td><span class='badge $badgeClass'>$type</span></td>
                            <td>$university</td>
                            <td>$faculte</td>
                            <td class='text-blue-600'>$email</td>
                            <td>$telephone</td>
                            <td>
                                <div class='flex items-center gap-2'>
                                    <button onclick=\"openModal('$id','$nom','$type','$university','$faculte','$email','$telephone')\"
                                        class='bg-sky-100 hover:bg-sky-200 text-sky-700 p-2 rounded-lg transition' title='Voir'>
                                        <i class='fa-solid fa-eye text-sm'></i>
                                    </button>
                                    <a href='up_scientifique.php?id=$id'>
                                        <button class='bg-emerald-100 hover:bg-emerald-200 text-emerald-700 p-2 rounded-lg transition' title='Modifier'>
                                            <i class='fa-solid fa-pen text-sm'></i>
                                        </button>
                                    </a>
                                    <a href='pdf_scientifique.php?id=$id' target='_blank'>
                                        <button class='bg-amber-100 hover:bg-amber-200 text-amber-700 p-2 rounded-lg transition' title='PDF'>
                                            <i class='fa-solid fa-file-pdf text-sm'></i>
                                        </button>
                                    </a>
                                    <button onclick=\"confirmDelete('$id')\"
                                        class='bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition' title='Supprimer'>
                                        <i class='fa-solid fa-trash text-sm'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Modal Détail -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
            <h3 class="text-white font-bold text-lg flex items-center gap-2">
                <i class="fa-solid fa-user"></i> Détail Scientifique
            </h3>
            <button onclick="closeModal()" class="text-white hover:text-emerald-200 text-xl">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="p-6">
            <table class="w-full text-sm">
                <tbody id="modalBody"></tbody>
            </table>
        </div>
        <div class="px-6 pb-5 flex justify-end">
            <button onclick="closeModal()" class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg font-bold hover:bg-gray-300">Fermer</button>
        </div>
    </div>
</div>

<script>
function filterTable() {
    const idVal   = document.getElementById('searchId').value.toLowerCase();
    const nameVal = document.getElementById('searchName').value.toLowerCase();
    const typeVal = document.getElementById('filterType').value;

    const rows = document.querySelectorAll('#mainTable tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const id    = cells[0].textContent.toLowerCase();
        const name  = cells[1].textContent.toLowerCase();
        const type  = cells[2].textContent.trim();

        const matchId   = id.includes(idVal);
        const matchName = name.includes(nameVal);
        const matchType = (typeVal === 'all') || (type === typeVal);

        row.style.display = (matchId && matchName && matchType) ? '' : 'none';
    });
}

function openModal(id, nom, type, university, faculte, email, telephone) {
    const fields = [
        ['ID', id],
        ['Nom complet', nom],
        ['Type', type],
        ['Université', university],
        ['Faculté', faculte],
        ['Email', email],
        ['Téléphone', telephone]
    ];
    let html = '';
    fields.forEach(([label, val], i) => {
        const bg = i % 2 === 0 ? 'bg-gray-50' : 'bg-white';
        html += `<tr class="${bg}">
            <td class="py-2 px-3 font-bold text-emerald-700 w-1/3">${label}</td>
            <td class="py-2 px-3 text-gray-700">${val || '-'}</td>
        </tr>`;
    });
    document.getElementById('modalBody').innerHTML = html;
    document.getElementById('detailModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function confirmDelete(id) {
    if (confirm('Voulez-vous vraiment supprimer ce scientifique ?')) {
        window.location.href = 'delete_scientifique.php?id=' + id;
    }
}
</script>

</body>
</html>
