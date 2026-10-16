<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'data/menu.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = isset($_POST['aksi']) ? $_POST['aksi'] : '';

    // ==========================================
    // AKSI 1: TAMBAH PESANAN KE KERANJANG
    // ==========================================
    if ($aksi === 'tambah') {
        $kode_menu = isset($_POST['kode_menu']) ? trim($_POST['kode_menu']) : '';
        $jumlah_input = isset($_POST['jumlah']) ? $_POST['jumlah'] : '';

        // 1. Validasi Kode Menu di Server
        $menu_dipilih = null;
        foreach ($daftar_menu as $menu) {
            if ($menu['kode'] === $kode_menu) {
                $menu_dipilih = $menu;
                break;
            }
        }

        if (!$menu_dipilih) {
            $_SESSION['flash_message'] = "Error: Kode menu tidak dikenal!";
            header("Location: index.php");
            exit();
        }

        // 2. Validasi Jumlah Input (Harus Bilangan Bulat)
        if ($jumlah_input === '' || !is_numeric($jumlah_input) || strpos($jumlah_input, '.') !== false) {
            $_SESSION['flash_message'] = "Error: Jumlah harus berupa bilangan bulat!";
            header("Location: index.php");
            exit();
        }

        $jumlah = (int)$jumlah_input;

        // 3. Validasi Batas Input Awal (1 - 10)
        if ($jumlah < 1 || $jumlah > 10) {
            $_SESSION['flash_message'] = "Error: Jumlah pembelian harus di antara 1 sampai 10!";
            header("Location: index.php");
            exit();
        }

        // Inisialisasi session keranjang jika belum ada
        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        // 4. Logika Penggabungan Item & Validasi Batas Maksimal 10 di Session
        if (isset($_SESSION['keranjang'][$kode_menu])) {
            $total_gabung = $_SESSION['keranjang'][$kode_menu]['jumlah'] + $jumlah;
            
            if ($total_gabung > 10) {
                $_SESSION['flash_message'] = "Error: Total jumlah item ini di keranjang melebihi batas maksimal 10!";
            } else {
                $_SESSION['keranjang'][$kode_menu]['jumlah'] = $total_gabung;
                $_SESSION['flash_message'] = "Berhasil menambahkan jumlah pesanan untuk " . htmlspecialchars($menu_dipilih['nama']);
            }
        } else {
            // Jika menu baru pertama kali dimasukkan
            $_SESSION['keranjang'][$kode_menu] = [
                'kode' => $menu_dipilih['kode'],
                'nama' => $menu_dipilih['nama'],
                'harga' => $menu_dipilih['harga'],
                'jumlah' => $jumlah
            ];
            $_SESSION['flash_message'] = "Berhasil memasukkan " . htmlspecialchars($menu_dipilih['nama']) . " ke keranjang.";
        }

        // Menggunakan Pola PRG (Post/Redirect/Get) agar aman dari double refresh
        header("Location: index.php");
        exit();
    }

    // ==========================================
    // AKSI 2: HAPUS SATU ITEM DARI KERANJANG
    // ==========================================
    if ($aksi === 'hapus_item') {
        $kode_menu = isset($_POST['kode_menu']) ? trim($_POST['kode_menu']) : '';
        if (isset($_SESSION['keranjang'][$kode_menu])) {
            unset($_SESSION['keranjang'][$kode_menu]);
            $_SESSION['flash_message'] = "Item berhasil dihapus dari keranjang.";
        }
        header("Location: keranjang.php");
        exit();
    }

    // ==========================================
    // AKSI 3: KOSONGKAN SELURUH KERANJANG
    // ==========================================
    if ($aksi === 'kosongkan') {
        unset($_SESSION['keranjang']);
        $_SESSION['flash_message'] = "Seluruh isi keranjang belanja telah dikosongkan.";
        header("Location: keranjang.php");
        exit();
    }
}