<?php
require_once "../config/db.php";
session_start();

$query = "
    SELECT 
        n.*,
        CONCAT(e.nom, ' ', e.prenom) AS etudiant,
        m.intitule AS module
    FROM notes n
    JOIN etudiant e ON n.id_etudiant = e.id
    JOIN module m ON n.id_module = m.id
    ORDER BY n.id DESC
";
$prepare = $pdo->prepare($query);
$prepare->execute();
$notes = $prepare->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Notes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
</head>

<body class="bg-slate-100 text-gray-800">

    <header class="fixed w-full z-50 bg-white/80 backdrop-blur shadow-sm ">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-extrabold text-blue-900 tracking-wide">
                Gestion Notes & Relevés
            </h1>

            <?php if (isset($_SESSION["isLogin"]) && $_SESSION["isLogin"]): ?>
                <nav class="nav flex items-center gap-3"></nav>
                <a  href="../authentication/logout.php"class="capitalize px-4 py-2 rounded-lg cursor-pointer text-sm font-semibold text-blue-900 hover:bg-blue-100 hover:text-blue-800 transition">logout</a>
            <?php endif ?>
        </div>
    </header>

    <section class="min-h-screen pt-32 pb-16">
        <div class="max-w-7xl mx-auto px-6">

            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-extrabold text-blue-900">
                    Liste des Notes
                </h2>
                <div class="flex items-center gap-1">
                    <a href="./ajouter.php"
                        class="p-3 text-sm font-semibold text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                    <a href="../mentions/afficher.php"
                        class="p-3 text-sm font-semibold text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition">
                        <i class="fa-regular fa-note-sticky"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                <table class="w-full text-sm text-left">
                    <thead class="bg-blue-50 text-blue-900">
                        <tr id="theadRow"></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($notes as $note): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center font-medium text-gray-900"><?= $note['id'] ?></td>
                                <td class="px-6 py-4 text-center text-gray-900"><?= $note['etudiant'] ?></td>
                                <td class="px-6 py-4 text-center text-gray-700"><?= $note['module'] ?></td>
                                <td class="px-6 py-4 text-center"><?= $note['note_cc'] ?></td>
                                <td class="px-6 py-4 text-center"><?= $note['note_exam'] ?></td>
                                <td class="px-6 py-4 text-center text-gray-700"><?= $note['moyenne_module'] ?></td>
                                <td class="px-6 py-4 text-center flex justify-center gap-2">
                                    <a href="./modifier.php?id=<?= $note['id'] ?>"
                                        class="px-3 py-1 text-sm font-semibold text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <a href="./delete.php?id=<?= $note['id'] ?>"
                                        onclick="return confirm('Supprimer cette note ?')"
                                        class="px-3 py-1 text-sm font-semibold text-red-600 bg-red-100 rounded-md hover:bg-red-200 transition">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($notes)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">Aucune note trouvée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>

        </div>
    </section>

    <script>
        const theadRow = document.getElementById("theadRow");
        const headers = ["ID", "Étudiant", "Module", "CC", "Exam", "Moyenne", "Actions"];
        headers.forEach(h => {
            const th = document.createElement("th");
            th.textContent = h;
            th.className = "px-6 py-4 font-semibold text-center";
            theadRow.appendChild(th);
        });

        const pages = [{
                name: "accueil",
                url: "/index.php"
            },
            {
                name: "étudiants",
                url: "../etudiants/afficher.php"
            },
            {
                name: "enseignants",
                url: "../enseignants/afficher.php"
            },
            {
                name: "modules",
                url: "../modules/afficher.php"
            },
            {
                name: "notes",
                url: "./afficher.php"
            }
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