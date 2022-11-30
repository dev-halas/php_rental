<?php 
    
    include '../../admin/sql_connect.php';
    include 'functions.php';
    $conn = OpenConnDB();

    if(isset($_POST["submit"])) {
        $adminName = $_POST["name"];
        $adminEmail = $_POST["email"];
        $adminPassword = $_POST["password"];

        createAdmin($conn, $adminName, $adminEmail, $adminPassword);

    }

    else {
        header("location: ../../admin/register.php");
        exit();
    }

    