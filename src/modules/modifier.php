<?php
require_once "../config/db.php";
session_start();

$id = $_GET['id'] ?? null;

$module_prepare = $pdo->prepare("SELECT * FROM module WHERE id = ?");
$module_prepare->execute([$id]);
$module = $module_prepare->fetch(PDO::FETCH_ASSOC);

if (!$module) {
    header("Location: afficher.php");
    exit;
}

$enseignants = $pdo->query("SELECT id, nom, prenom FROM enseignant ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST["code"];
    $intitule = $_POST["intitule"];
    $enseignant_id = $_POST["enseignant"];

    $update = $pdo->prepare("UPDATE module SET code = ?, intitule = ?, id_enseignant = ? WHERE id = ?");
    $update->execute([$code, $intitule, $enseignant_id, $id]);

    header("Location: afficher.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Module</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-slate-100 text-gray-800">

<header class="fixed w-full z-50 bg-white/80 backdrop-blur shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-extrabold text-blue-900 tracking-wide">Gestion Modules</h1>
        <nav class="flex items-center gap-3 nav"></nav>
    </div>
</header>

<section class="min-h-screen pt-32 pb-16">
    <div class="max-w-3xl mx-auto px-6">

        <div class="">
            <a href="./afficher.php"
                class="p-3 text-sm font-semibold text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>

        <div class="text-center">
            <h2 class="text-2xl text-center font-extrabold text-blue-900 mb-6">
                Modifier le Module
            </h2>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">

            <form method="POST" class="space-y-6">

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Code</label>
                    <input type="text" name="code" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="<?= $module['code'] ?>">
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Intitulé</label>
                    <input type="text" name="intitule" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="<?= $module['intitule'] ?>">
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Enseignant</label>
                    <select name="enseignant" class="w-full border border-gray-300 rounded-lg px-4 h-[50px]">
                        <option value="">-- Aucun --</option>
                        <?php foreach($enseignants as $ens): ?>
                            <option value="<?= $ens['id'] ?>" <?= $module['id_enseignant'] == $ens['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ens['nom'] . ' ' . $ens['prenom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <button type="submit"
                        class="w-full bg-blue-700 text-white py-2 rounded-lg font-semibold hover:bg-blue-800 transition">
                        Modifier
                    </button>
                </div>

            </form>

        </div>

    </div>
</section>

<script>
    const pages = [
        {name:"accueil", url:"../index.php"},
        {name:"étudiants", url:"../etudiants/afficher.php"},
        {name:"enseignants", url:"../enseignants/afficher.php"},
        {name:"modules", url:"../modules/afficher.php"}
    ];
    const nav = document.querySelector(".nav");
    pages.forEach(p => {
        const a = document.createElement("a");
        a.href = p.url;
        a.textContent = p.name;
        a.className = "capitalize px-4 py-2 rounded-lg text-sm font-semibold text-blue-900 hover:bg-blue-100 hover:text-blue-800 transition";
        nav.appendChild(a);
    });
</script>

</body>
</html>
