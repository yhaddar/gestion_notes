<?php
require_once "../config/db.php";
session_start();

$module_id = $_GET['id'] ?? null;

$module_prepare = $pdo->prepare("SELECT * FROM module WHERE id = ?");
$module_prepare->execute([$module_id]);
$module = $module_prepare->fetch(PDO::FETCH_ASSOC);

$coeff_prepare = $pdo->prepare("SELECT * FROM coefficient WHERE id_module = ?");
$coeff_prepare->execute([$module_id]);
$coefficients = $coeff_prepare->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Coefficients du module <?= htmlspecialchars($module['intitule']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        <a href="../authentication//logout.php" class="capitalize px-4 py-2 rounded-lg cursor-pointer text-sm font-semibold text-blue-900 hover:bg-blue-100 hover:text-blue-800 transition">logout</a>
      <?php endif ?>
    </div>
  </header>

    <section class="min-h-screen pt-32 pb-16">
        <div class="max-w-7xl mx-auto px-6">



            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-extrabold text-blue-900">
                    Module : <?= $module['intitule'] ?>
                </h2>
                <?php if (count($coefficients) == 0): ?>
                    <a href="./ajouter.php?module=<?= $module_id ?>"
                        class="p-3 text-sm font-semibold text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                <?php endif ?>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                <table class="w-full text-sm text-left">
                    <thead class="bg-blue-50 text-blue-900">
                        <tr>
                            <th class="px-6 py-4 text-center font-semibold">ID</th>
                            <th class="px-6 py-4 text-center font-semibold">Coefficient</th>
                            <th class="px-6 py-4 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($coefficients as $c): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center"><?= $c['id'] ?></td>
                                <td class="px-6 py-4 text-center"><?= $c['coefficient'] ?></td>
                                <td class="px-6 py-4 text-center flex justify-center gap-2">
                                    <a href="./modifier.php?id=<?= $c['id'] ?>&module_id=<?= $module_id ?>"
                                        class="px-3 py-1 text-sm font-semibold text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <a href="./delete.php?id=<?= $c['id'] ?>&module_id=<?= $module_id ?>"
                                        onclick="return confirm('Supprimer ce coefficient ?')"
                                        class="px-3 py-1 text-sm font-semibold text-red-600 bg-red-100 rounded-md hover:bg-red-200 transition">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($coefficients)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">Aucun coefficient trouvé.</td>
                            </tr>
                        <?php endif; ?>
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