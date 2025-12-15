<?php
require_once "../config/db.php";
session_start();

$query = "SELECT e.*, GROUP_CONCAT(m.intitule SEPARATOR ', ') AS modules FROM enseignant e
            LEFT JOIN module m ON e.id = m.id_enseignant
            GROUP BY e.id, e.nom, e.prenom, e.email
            ORDER BY e.id;";
$prepare = $pdo->prepare($query);
$prepare->execute();
$etudiants = $prepare->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Enseignants</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-slate-100 text-gray-800">

  <header class="fixed w-full z-50 bg-white/80 backdrop-blur shadow-sm ">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-xl font-extrabold text-blue-900 tracking-wide">
        Gestion Notes & Relevés
      </h1>

      <?php if (isset($_SESSION["isLogin"]) && $_SESSION["isLogin"]): ?>
        <nav class="nav flex items-center gap-3">
        </nav>
        <a href="../authentication/logout.php" class="capitalize px-4 py-2 rounded-lg cursor-pointer text-sm font-semibold text-blue-900 hover:bg-blue-100 hover:text-blue-800 transition">logout</a>
      <?php endif ?>
    </div>
  </header>

    <section class="min-h-screen pt-32 pb-16">
        <div class="max-w-7xl mx-auto px-6">

            <div class="flex justify-between items-center">
                <h2 class="text-3xl font-extrabold text-blue-900 mb-8">
                    Liste des Enseignants
                </h2>
                <a href="./ajouter.php"
                    class="p-3 text-sm font-semibold text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                <table class="w-full text-sm text-left">
                    <thead class="bg-blue-50 text-blue-900 thead">
                        <tr>

                        </tr>
                    </thead>

                    <tbody id="studentsTable" class="divide-y divide-gray-200">
                        <?php foreach ($etudiants as $etudiant): ?>
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900 text-center">
                                    <?= $etudiant["id"] ?>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 text-center">
                                    <?= $etudiant["nom"] ?>
                                </td>
                                <td class="px-6 py-4 text-gray-700 text-center">
                                    <?= $etudiant["prenom"] ?>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-center">
                                    <?= $etudiant["email"] ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <?php
                                        $modules = [];
                                        if (!empty($etudiant["modules"])) {
                                            $modules = explode(',', $etudiant["modules"]);
                                            $modules = array_map('trim', $modules);
                                        }
                                        ?>
                                        <?php foreach ($modules as $module): ?>
                                            <span class="text-xs font-semibold text-blue-700 bg-blue-100 px-3 py-1 rounded-full">
                                                <?= $module ?>
                                            </span>
                                        <?php endforeach ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="./modifier.php?id=<?= $etudiant['id'] ?>"
                                            class="px-3 py-1 text-sm font-semibold text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>

                                        <a href="./delete.php?id=<?= $etudiant['id'] ?>"
                                            onclick="return confirm('Supprimer cet étudiant ?')"
                                            class="px-3 py-1 text-sm font-semibold text-red-600 bg-red-100 rounded-md hover:bg-red-200 transition">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

            </div>

        </div>
    </section>


    <script>
        const pages = [{
                name: "accueil",
                url: "../index.php"
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
                { name: "notes", url: "../notes/afficher.php" }
        ]

        let nav = document.querySelector(".nav");

        pages.map(page => {
            let links = document.createElement("a");
            links.href = page.url;
            links.textContent = page.name;
            links.className =
                "capitalize px-4 py-2 rounded-lg text-sm font-semibold text-blue-900 hover:bg-blue-100 hover:text-blue-800 transition";
            nav.appendChild(links);
        })

        const thead = document.querySelector(".thead tr");
        const theadData = ["ID", "Nom", "Prénom", "Email", "Modules", "Actions"];

        theadData?.map(data => {
            let th = document.createElement("th");
            th.className = "px-6 py-4 font-semibold text-center";
            th.textContent = data;
            thead.appendChild(th);
        });
    </script>

</body>

</html>