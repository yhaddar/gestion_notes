<?php
    if (!isset($_SESSION['isLogin'])) {
        header("Location: ../authentication/login.php");
        exit();
    }
