<?php 
require_once "../config/db.php";
session_start();
$id = $_GET['id'];
$prepare = $pdo->prepare("DELETE FROM coefficient WHERE id = ?");
$prepare->execute([$id]);
header("Location: ../../../modules/afficher.php");
?>