<?php 
$localhost="mysql";
$dbname="gestion_notes";
$username="root";
$password="root";

try {

    $pdo = new PDO("mysql:host=$localhost;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // $pdo->exec("USE $dbname");

    // $pdo->exec("CREATE TABLE users(
    //     id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
    //     username VARCHAR(50) UNIQUE, 
    //     password VARCHAR(255)
    // )");

    // $pdo->exec("CREATE TABLE enseignant(
    //     id INT AUTO_INCREMENT PRIMARY KEY UNIQUE, 
    //     nom VARCHAR(20), 
    //     prenom VARCHAR(20), 
    //     email VARCHAR(30) UNIQUE
    // )");

    // $pdo->exec("CREATE TABLE module(
    //     id INT AUTO_INCREMENT PRIMARY KEY UNIQUE, 
    //     code VARCHAR(20) UNIQUE, 
    //     intitule VARCHAR(255), 
    //     id_enseignant INT,
    //     FOREIGN KEY (id_enseignant) REFERENCES enseignant(id)
    // )");

    // $pdo->exec("CREATE TABLE etudiant(
    //     id INT AUTO_INCREMENT PRIMARY KEY UNIQUE, 
    //     nom VARCHAR(20), 
    //     prenom VARCHAR(20), 
    //     email VARCHAR(30), 
    //     id_module INT,
    //     FOREIGN KEY (id_module) REFERENCES module(id)
    // )");

    // $pdo->exec("CREATE TABLE etudiant_module(
    //     id_etudiant INT,
    //     id_module INT,
    //     FOREIGN KEY (id_etudiant) REFERENCES etudiant(id),
    //     FOREIGN KEY (id_module) REFERENCES module(id)
    // )");

    // $pdo->exec("CREATE TABLE coefficient(
    //     id INT AUTO_INCREMENT PRIMARY KEY UNIQUE, 
    //     id_module INT,
    //     FOREIGN KEY (id_module) REFERENCES module(id)
    // )");

    // $pdo->exec("CREATE TABLE notes(
    //     id INT AUTO_INCREMENT PRIMARY KEY UNIQUE, 
    //     id_etudiant INT,
    //     id_module INT,
    //     note_cc INT,
    //     note_exam INT,
    //     moyenne_module FLOAT,
    //     FOREIGN KEY (id_etudiant) REFERENCES etudiant(id),
    //     FOREIGN KEY (id_module) REFERENCES module(id)
    // )");

    // $pdo->exec("CREATE TABLE mentions(
    //     id INT AUTO_INCREMENT PRIMARY KEY UNIQUE, 
    //     min_val FLOAT, 
    //     max_val FLOAT,
    //     libelle VARCHAR(50)
    // )");

}catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>