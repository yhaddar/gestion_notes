<?php 

    require_once "../config/db.php";
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $login = $pdo->prepare("SELECT * FROM users WHERE `username` = ?");
        $login->execute([$username]);
        if($login->fetch() > 0){
            $_SESSION["username"] = $username;
            $_SESSION["isLogin"] = true;
            header("Location: ../index.php");
        }
        else {
            $add = $pdo->prepare("INSERT INTO users(`username`, `password`) VALUES(?,?)");
            $add->execute([$username, md5($password)]);
            $_SESSION["username"] = $username;
            $_SESSION["isLogin"] = true;
            header("Location: ../index.php");
        }
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Login - Gestion Académique</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white rounded-xl shadow-lg p-10 w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6">Connexion</h2>

    <form action="login.php" method="POST" class="space-y-5">
      <div>
        <label for="username" class="block text-gray-700 mb-2">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" placeholder="Votre nom d'utilisateur"
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label for="password" class="block text-gray-700 mb-2">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="Votre mot de passe"
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <button type="submit"
                class="w-full bg-blue-700 text-white py-2 rounded-lg font-semibold hover:bg-blue-800 transition">
          Se connecter
        </button>
      </div>
    </form>
  </div>

</body>
</html>
