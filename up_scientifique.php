<!DOCTYPE html>
<?php
require_once('connection.php');
session_start();



    $id_scientifique = $_GET["id"];

    $result = mysqli_query($link, "SELECT * FROM scientifiques WHERE id='$id_scientifique'") or die(mysqli_error($link));

    if (isset($_POST['submit'])) {

        $nom        = $_POST["nom"];
        $type       = $_POST["type"];
        $university = $_POST["university"];
        $faculte    = $_POST["faculte"];
        $email      = $_POST["email"];
        $telephone  = $_POST["telephone"];

        $updateQuery = "UPDATE scientifiques SET 
            `nom`        = '$nom',
            `type`       = '$type',
            `university` = '$university',
            `faculte`    = '$faculte',
            `email`      = '$email',
            `telephone`  = '$telephone'
            WHERE `id` = '$id_scientifique'";

        $response = mysqli_query($link, $updateQuery) or die(mysqli_error($link));

        echo '<script>alert("تم تحديث المعلومات بنجاح شكرا");</script>';
        echo '<script>window.location = "table_bootstrap.php";</script>';
        exit();
    }
?>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier Scientifique</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #1a1a2e;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: none;
            border-color: #6ee7b7;
        }
        .card {
            background-color: #2d2d44;
            border: none;
            border-radius: 16px;
        }
        .card-header {
            background-color: #1f1f35;
            border-radius: 16px 16px 0 0 !important;
            border-bottom: 1px solid #3d3d5c;
        }
        label {
            color: #a0aec0;
            font-size: 13px;
        }
        .form-control, .form-select {
            background-color: #1f1f35;
            border: 1px solid #3d3d5c;
            color: #e2e8f0;
        }
        .form-control:disabled {
            background-color: #16162a;
            color: #718096;
        }
        .btn-save {
            background-color: #6ee7b7;
            color: #1a1a2e;
            font-weight: bold;
            border: none;
        }
        .btn-save:hover {
            background-color: #34d399;
            color: #1a1a2e;
        }
        .btn-back {
            background-color: #ef4444;
            color: white;
            border: none;
        }
        .btn-back:hover {
            background-color: #dc2626;
            color: white;
        }
        .flask-icon {
            color: #6ee7b7;
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5">

        <!-- Header -->
        <div class="d-flex align-items-center gap-3 mb-4">
            <i class="fa-solid fa-flask fa-2x flask-icon"></i>
            <h2 class="text-white mb-0"> Modifier Scientifique</h2>
        </div>

        <?php
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($data as $row) {
        ?>

        <div class="card shadow-lg">
            <div class="card-header py-3">
                <h5 class="text-white mb-0">
                    <i class="fa-solid fa-microscope me-2 flask-icon"></i>
                    <?php echo htmlspecialchars($row['nom']); ?>
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="up_scientifique.php?id=<?php echo $id_scientifique; ?>">

                    <!-- ID (readonly) -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label> ID</label>
                            <input type="text" class="form-control" value="<?php echo $row['id']; ?>" disabled />
                        </div>
                    </div>

                    <!-- Nom -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label> Nom complet</label>
                            <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($row['nom']); ?>" required />
                        </div>
                    </div>

                    <!-- Type -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label> Type</label>
                            <select name="type" class="form-select">
                                <option value="<?php echo $row['type']; ?>" selected><?php echo $row['type']; ?></option>
                                <option value="Professeur">Professeur</option>
                                <option value="Doctorant">Doctorant</option>
                                <option value="Chercheur">Chercheur</option>
                            </select>
                        </div>
                    </div>

                    <!-- University -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label Université</label>
                            <input type="text" name="university" class="form-control" value="<?php echo htmlspecialchars($row['university']); ?>" required />
                        </div>
                    </div>

                    <!-- Faculté -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label> Faculté</label>
                            <input type="text" name="faculte" class="form-control" value="<?php echo htmlspecialchars($row['faculte']); ?>" required />
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label> Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" required />
                        </div>
                    </div>

                    <!-- Téléphone -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label> Téléphone</label>
                            <input type="text" name="telephone" class="form-control" value="<?php echo htmlspecialchars($row['telephone']); ?>" />
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <input name="submit" class="btn btn-save w-100 py-2" type="submit" value="Modifier" />
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-back w-100 py-2" onclick="retour()">
                                Retour
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <?php } ?>

    </div>

    <script>
        function retour() {
            window.location = "table_bootstrap.php";
        }
    </script>
</body>
</html>

<?php

?>
