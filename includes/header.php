<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kantin Kampus - Politeknik Ganesha Medan</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 20px; line-height: 1.6;">
    <header style="background-color: #f8f9fa; padding: 15px; border-bottom: 2px solid #dee2e6; margin-bottom: 20px;">
        <h1 style="margin: 0; color: #343a40;">🏪 Kantin Kampus Ganesha</h1>
        <p style="margin: 5px 0 10px 0; font-size: 14px; color: #6c757d;">UTS Web I - Program Studi D3 Manajemen Informatika</p>
        <nav style="margin-top: 10px;">
            <a href="index.php" style="text-decoration: none; color: #007bff; font-weight: bold; margin-right: 15px;">📋 Daftar Menu</a> 
            <a href="keranjang.php" style="text-decoration: none; color: #007bff; font-weight: bold;">🛒 Keranjang Belanja</a>
        </nav>
    </header>
    <main>