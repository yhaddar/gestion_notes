<?php
require_once "../config/db.php";
session_start();

$id = $_GET['id'];
$etud_prepare = $pdo->prepare("SELECT * FROM etudiant WHERE id = ?");
$etud_prepare->execute([$id]);
$etudiant = $etud_prepare->fetch(PDO::FETCH_ASSOC);


$module_prepare = $pdo->prepare("SELECT id, intitule FROM module");
$module_prepare->execute();
$modules = $module_prepare->fetchAll(PDO::FETCH_ASSOC);

$etud_mod_prepare = $pdo->prepare("SELECT id_module FROM etudiant_module WHERE id_etudiant = ?");
$etud_mod_prepare->execute([$id]);
$etud_modules = $etud_mod_prepare->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $modules_post = $_POST["modules"] ?? [];

    /* update étudiant */
    $update_prepare = $pdo->prepare(
        "UPDATE etudiant SET nom = ?, prenom = ?, email = ? WHERE id = ?"
    );
    $update_prepare->execute([$nom, $prenom, $email, $id]);

    /* supprimer anciens modules */
    $delete_prepare = $pdo->prepare(
        "DELETE FROM etudiant_module WHERE id_etudiant = ?"
    );
    $delete_prepare->execute([$id]);

    /* insérer nouveaux modules */
    $insert_prepare = $pdo->prepare(
        "INSERT INTO etudiant_module(id_etudiant, id_module) VALUES(?, ?)"
    );

    foreach ($modules_post as $m) {
        $insert_prepare->execute([$id, $m]);
    }

    header("Location: afficher.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier Étudiant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-slate-100 min-h-screen pt-32 px-6">

    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg">

        <div class="">
            <a href="./afficher.php"
                class="p-3 text-sm font-semibold text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>

        <div class="text-center">
            <h2 class="text-2xl text-center font-extrabold text-blue-900 mb-6">
                Modifier un Étudiant
            </h2>
        </div>

        <form method="POST" class="space-y-6">

            <div>
                <label class="font-semibold">Nom</label>
                <input type="text" name="nom" value="<?= $etudiant['nom'] ?>" required
                    class="w-full border rounded px-4 py-2">
            </div>

            <div>
                <label class="font-semibold">Prénom</label>
                <input type="text" name="prenom" value="<?= $etudiant['prenom'] ?>" required
                    class="w-full border rounded px-4 py-2">
            </div>

            <div>
                <label class="font-semibold">Email</label>
                <input type="email" name="email" value="<?= $etudiant['email'] ?>" required
                    class="w-full border rounded px-4 py-2">
            </div>

            <div>
                <label class="font-semibold">Modules</label>
                <select id="moduleSelect"
                    class="w-full border rounded px-4 h-[45px]">
                    <option value="-" disabled selected>Choisir module</option>
                    <?php foreach ($modules as $m): ?>
                        <option value="<?= $m['id'] ?>">
                            <?= $m['intitule'] ?>
                        </option>
                    <?php endforeach ?>
                </select>

                <div id="selectedModules" class="flex flex-wrap gap-2 mt-4"></div>
                <div id="hiddenInputs"></div>
            </div>

            <button class="w-full bg-blue-700 text-white py-2 rounded font-semibold">
                Modifier
            </button>
        </form>
    </div>

    <script>
        const select = document.getElementById('moduleSelect');
        const selectedContainer = document.getElementById('selectedModules');
        const hiddenInputs = document.getElementById('hiddenInputs');

        let list = <?= json_encode($etud_modules) ?>;

        function render() {
            selectedContainer.innerHTML = '';
            hiddenInputs.innerHTML = '';

            list.forEach(id => {
                const text = select.querySelector(`option[value="${id}"]`)?.textContent;

                const span = document.createElement('span');
                span.className = 'text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full cursor-pointer';
                span.textContent = text;
                span.onclick = () => {
                    list = list.filter(x => x != id);
                    render();
                };
                selectedContainer.appendChild(span);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'modules[]';
                input.value = id;
                hiddenInputs.appendChild(input);
            });
        }

        select.addEventListener('change', () => {
            if (select.value !== "-" && !list.includes(select.value)) {
                list.push(select.value);
                render();
            }
        });

        render();
    </script>

</body>

</html>