<?php
require_once "../config/db.php";
session_start();

$coef_id = $_GET['id'];

$coef_prepare = $pdo->prepare("
    SELECT c.*, m.intitule 
    FROM coefficient c
    JOIN module m ON c.id_module = m.id
    WHERE c.id = ?
");
$coef_prepare->execute([$coef_id]);
$coef = $coef_prepare->fetch(PDO::FETCH_ASSOC);

if (!$coef) {
    echo "Coefficient non trouvé.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coefficient = $_POST['coefficient'];

    $update = $pdo->prepare("UPDATE coefficient SET coefficient = ? WHERE id = ?");
    $update->execute([$coefficient, $coef_id]);

    header("Location: ../modules/afficher.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Coefficient</title>
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
            <a href="../modules/afficher.php"
                class="p-3 text-sm font-semibold text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>

        <div class="text-center mt-6">
            <h2 class="text-2xl font-extrabold text-blue-900 mb-6">
                Modifier le Coefficient pour : <?= htmlspecialchars($coef['intitule']) ?>
            </h2>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">

            <form method="POST" class="space-y-6">

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Coefficient</label>
                    <input type="number" step="0.01" min="0" name="coefficient" required
                           value="<?= htmlspecialchars($coef['coefficient']) ?>"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-blue-700 text-white py-2 rounded-lg font-semibold hover:bg-blue-800 transition">
                        Modifier
                    </button>
                    <a href="../modules/afficher.php"
                       class="flex-1 text-center bg-gray-200 text-gray-700 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">
                        Annuler
                    </a>
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
