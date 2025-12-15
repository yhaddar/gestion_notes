<?php
require_once "../config/db.php";
session_start();

if (!isset($_GET['id'])) {
    header("Location: afficher.php");
    exit;
}

$note_id = $_GET['id'];

$note_prepare = $pdo->prepare("
    SELECT * FROM notes WHERE id = ?
");
$note_prepare->execute([$note_id]);
$note = $note_prepare->fetch(PDO::FETCH_ASSOC);

if (!$note) {
    header("Location: afficher.php");
    exit;
}

$etudiants = $pdo->query("SELECT id, nom, prenom FROM etudiant ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
$modules = $pdo->query("SELECT id, intitule FROM module ORDER BY intitule")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note_cc = $_POST['note_cc'];
    $note_exam = $_POST['note_exam'];
    $moyenne = ($note_cc + $note_exam) / 2;

    $update = $pdo->prepare("
        UPDATE notes 
        SET note_cc = ?, note_exam = ?, moyenne_module = ?
        WHERE id = ?
    ");
    $update->execute([$note_cc, $note_exam, $moyenne, $note_id]);

    header("Location: afficher.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Note</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
</head>
<body class="bg-slate-100 text-gray-800">

<header class="fixed w-full z-50 bg-white/80 backdrop-blur shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-extrabold text-blue-900 tracking-wide">Gestion Notes & Relevés</h1>
        <nav class="flex items-center gap-3 nav"></nav>
    </div>
</header>

<section class="min-h-screen pt-32 pb-16">
    <div class="max-w-3xl mx-auto px-6">

        <div class="mb-6">
            <a href="./afficher.php" class="p-3 text-sm font-semibold text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">

            <h2 class="text-2xl font-extrabold text-blue-900 mb-6 text-center">Modifier une Note</h2>

            <form method="POST" class="space-y-6">

                <!-- Étudiant (disabled) -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Étudiant</label>
                    <select disabled class="w-full border border-gray-300 rounded-lg px-4 py-2 h-[50px]">
                        <?php foreach($etudiants as $e): ?>
                            <option value="<?= $e['id'] ?>" <?= $e['id'] == $note['id_etudiant'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($e['nom'].' '.$e['prenom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Module (disabled) -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Module</label>
                    <select disabled class="w-full border border-gray-300 rounded-lg px-4 py-2 h-[50px]">
                        <?php foreach($modules as $m): ?>
                            <option value="<?= $m['id'] ?>" <?= $m['id'] == $note['id_module'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['intitule']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Note CC -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Note CC</label>
                    <input type="number" step="0.01" min="0" max="20" name="note_cc" required
                           value="<?= $note['note_cc'] ?>"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Note Exam -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Note Exam</label>
                    <input type="number" step="0.01" min="0" max="20" name="note_exam" required
                           value="<?= $note['note_exam'] ?>"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <button type="submit" class="w-full bg-blue-700 text-white py-2 rounded-lg font-semibold hover:bg-blue-800 transition">
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
        {name:"modules", url:"../modules/afficher.php"},
        {name:"notes", url:"./afficher.php"}
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
