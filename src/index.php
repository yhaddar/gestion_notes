<?php session_start() ?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Gestion Académique</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
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
        <a href="./authentication/logout.php" class="capitalize px-4 py-2 rounded-lg cursor-pointer text-sm font-semibold text-blue-900 hover:bg-blue-100 hover:text-blue-800 transition">logout</a>
      <?php endif ?>
    </div>
  </header>

  <section
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white relative overflow-hidden">

    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.1),transparent_60%)]"></div>

    <div class="relative max-w-4xl mx-auto text-center px-6">
      <h2 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight">
        Application Web de  
        <span class="text-blue-300">Gestion des Notes</span>
      </h2>

      <p class="text-lg md:text-xl text-blue-100 mb-10 leading-relaxed">
        Gestion complète des étudiants, modules et enseignants avec
        calcul automatique des moyennes, génération des relevés
        et attribution des mentions.
      </p>

      <?php if (!(isset($_SESSION["isLogin"]) && $_SESSION["isLogin"])): ?>
        <a href="./authentication/login.php"
          class="inline-flex items-center gap-2 bg-white text-blue-900 font-semibold px-8 py-4 rounded-xl shadow-lg hover:scale-105 hover:bg-blue-50 transition duration-300">
          Commencer
        </a>
      <?php endif ?>
    </div>
  </section>

</body>

<script>
  const pages = [
    { name: "accueil", url: "./index.php" },
    { name: "étudiants", url: "./etudiants/afficher.php" },
    { name: "enseignants", url: "./enseignants/afficher.php" },
    { name: "modules", url: "./modules/afficher.php" },
    { name: "notes", url: "./notes/afficher.php" }
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
</script>

</html>
