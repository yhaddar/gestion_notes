<?php
require_once "../config/db.php";
session_start();

$id = $_GET['id'] ?? null;

$enseignant_prepare = $pdo->prepare("SELECT * FROM enseignant WHERE id = ?");
$enseignant_prepare->execute([$id]);
$enseignant = $enseignant_prepare->fetch(PDO::FETCH_ASSOC);

if (!$enseignant) {
    header("Location: afficher.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];

    $update = $pdo->prepare("UPDATE enseignant SET nom = ?, prenom = ?, email = ? WHERE id = ?");
    $update->execute([$nom, $prenom, $email, $id]);

    header("Location: afficher.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Enseignant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-slate-100 text-gray-800 min-h-screen pt-32 px-6">

<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg p-8">

    <div class="">
        <a href="./afficher.php"
            class="p-3 text-sm font-semibold text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
    </div>

    <div class="text-center">
        <h2 class="text-2xl font-extrabold text-blue-900 mb-6">
            Modifier un Enseignant
        </h2>
    </div>

    <form method="POST" class="space-y-6">
        <div>
            <label class="block mb-2 font-semibold text-gray-700">Nom</label>
            <input type="text" name="nom" value="<?= $enseignant["nom"] ?>" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block mb-2 font-semibold text-gray-700">Prénom</label>
            <input type="text" name="prenom" value="<?= $enseignant["prenom"] ?>" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block mb-2 font-semibold text-gray-700">Email</label>
            <input type="email" name="email" value="<?= $enseignant["email"] ?>" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <button type="submit"
                class="w-full bg-blue-700 text-white py-2 rounded-lg font-semibold hover:bg-blue-800 transition">
                Modifier
            </button>
        </div>
    </form>
</div>

</body>
</html>
