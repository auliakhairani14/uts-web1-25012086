<?php
require_once 'data/menu.php';
require_once 'includes/header.php';

// 1. Logika Menyimpan Cookie Kategori Pilihan Pengguna Selama 7 Hari
if (isset($_GET['set_kategori'])) {
    $kategori_pilihan = trim($_GET['set_kategori']);
    // Validasi agar kategori sesuai dengan data yang tersedia di kantin
    if ($kategori_pilihan === 'Makanan' || $kategori_pilihan === 'Minuman') {
        setcookie('kategori_favorit', $kategori_pilihan, time() + (7 * 24 * 60 * 60), "/");
        $_SESSION['flash_message'] = "Sukses: Kategori favorit Anda berhasil disimpan ke Cookie: " . $kategori_pilihan;
        header("Location: index.php");
        exit();
    }
}

// 2. Proteksi XSS (Escaping) untuk Kata Kunci Pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 3. Logika Prioritas Penggunaan Filter Kategori (GET mengalahkan Cookie)
$kategori_filter = '';
if (isset($_GET['kategori'])) {
    $kategori_filter = trim($_GET['kategori']); // Prioritas 1: Berdasarkan Parameter GET
} elseif (isset($_COOKIE['kategori_favorit'])) {
    $kategori_filter = $_COOKIE['kategori_favorit']; // Prioritas 2: Berdasarkan Preferensi Cookie
}

// 4. Proses Penyaringan Data Menu
$menu_tampil = array_filter($daftar_menu, function($item) use ($search, $kategori_filter) {
    $match_search = true;
    $match_kategori = true;

    if ($search !== '') {
        $match_search = stripos($item['nama'], $search) !== false;
    }
    if ($kategori_filter !== '') {
        $match_kategori = $item['kategori'] === $kategori_filter;
    }

    return $match_search && $match_kategori;
});
?>

<h2 style="color: #343a40;">📋 Daftar Menu Kantin</h2>

<!-- Kotak Preferensi Pilihan Kategori Cookie -->
<div style="background-color: #e2e3e5; padding: 12px; margin-bottom: 20px; border-radius: 4px; border-left: 4px solid #6c757d; font-size: 14px;">
    <strong>🍪 Set Preferensi Cookie Kategori Favorit Anda:</strong>
    <a href="index.php?set_kategori=Makanan" style="margin-left: 10px; color: #007bff; text-decoration: none; font-weight: bold;">Makanan</a> | 
    <a href="index.php?set_kategori=Minuman" style="color: #007bff; text-decoration: none; font-weight: bold;">Minuman</a>
    <p style="margin: 5px 0 0 0; color: #495057;">Status Cookie Saat Ini: <strong style="color: #dc3545;"><?php echo isset($_COOKIE['kategori_favorit']) ? $_COOKIE['kategori_favorit'] : 'Belum Diatur'; ?></strong></p>
</div>

<!-- Navigasi Cepat Filter Kategori Menggunakan Parameter GET -->
<div style="margin-bottom: 20px; font-size: 14px;">
    <span>Filter Tampilan: </span>
    <a href="index.php" style="margin-left: 5px; font-weight: bold; text-decoration: none; color: #495057;">Semua Menu</a> | 
    <a href="index.php?kategori=Makanan" style="font-weight: bold; text-decoration: none; color: #007bff;">Khusus Makanan (GET)</a> | 
    <a href="index.php?kategori=Minuman" style="font-weight: bold; text-decoration: none; color: #007bff;">Khusus Minuman (GET)</a>
</div>

<!-- Form Pencarian Menggunakan Metode GET -->
<form method="GET" action="index.php" style="margin-bottom: 20px;">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Cari nama menu..." style="padding: 8px; width: 250px; border: 1px solid #ccc; border-radius: 4px;">
    <button type="submit" style="padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Cari</button>
    <?php if ($search !== '' || $kategori_filter !== ''): ?>
        <a href="index.php" style="margin-left: 10px; color: #dc3545; text-decoration: none;">Reset</a>
    <?php endif; ?>
</form>

<?php if (empty($menu_tampil)): ?>
    <p style="color: #dc3545; font-weight: bold; background-color: #f8d7da; padding: 10px; border-radius: 4px;">Menu tidak ditemukan atau kategori kosong.</p>
<?php else: ?>
    <div class="menu-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        <?php foreach ($menu_tampil as $menu): ?>
            <div class="menu-item" style="border: 1px solid #dee2e6; padding: 15px; border-radius: 8px; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="margin: 0 0 10px 0; color: #007bff;"><?php echo htmlspecialchars($menu['nama'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p style="margin: 5px 0; font-size: 14px; color: #6c757d;">Kode: <strong><?php echo $menu['kode']; ?></strong> | Kategori: <?php echo $menu['kategori']; ?></p>
                <p style="margin: 10px 0; font-weight: bold; color: #28a745;">Harga: Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?></p>
                
                <!-- Form Input Jumlah Pesanan -->
                <form method="POST" action="proses.php">
                    <input type="hidden" name="aksi" value="tambah">
                    <input type="hidden" name="kode_menu" value="<?php echo $menu['kode']; ?>">
                    <label style="font-size: 14px;">Jumlah: </label>
                    <input type="number" name="jumlah" value="1" min="1" max="10" style="width: 50px; padding: 4px; border: 1px solid #ccc; border-radius: 4px;">
                    <button type="submit" style="padding: 5px 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 5px;">Tambah</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>