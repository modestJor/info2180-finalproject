<?php
session_start();

    $username = 'admin@project2.com';
    $password = $_POST['password123'];
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';

    if 

if ($_POST['username'] == "admin@project2.com" && $_POST['password'] == "password123") {
    header("Location : main.html");
    exit();
} else {
    echo "invalid username or password";
}
?>