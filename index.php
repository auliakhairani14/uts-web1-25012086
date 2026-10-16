<?php
require_once 'data/menu.php';
require_once 'includes/header.php';

// Proteksi XSS (Escaping) untuk kata kunci pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Proses penyaringan (filtering) data menu berdasarkan kata kunci cari
$menu_tampil = array_filter($daftar_menu, function($item) use ($search) {
    if ($search !== '') {
        return stripos($item['nama'], $search) !== false;
    }
    return true;
});
?>

<h2 style="color: #343a40;">📋 Daftar Menu Kantin</h2>

<!-- Form Pencarian Menggunakan Metode GET -->
<form method="GET" action="index.php" style="margin-bottom: 20px;">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Cari nama menu..." style="padding: 8px; width: 250px; border: 1px solid #ccc; border-radius: 4px;">
    <button type="submit" style="padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Cari</button>
    <?php if ($search !== ''): ?>
        <a href="index.php" style="margin-left: 10px; color: #dc3545; text-decoration: none;">Reset Pencarian</a>
    <?php endif; ?>
</form>

<?php if (empty($menu_tampil)): ?>
    <!-- Pesan jika hasil pencarian tidak ditemukan sesuai Aturan UTS -->
    <p style="color: #dc3545; font-weight: bold; background-color: #f8d7da; padding: 10px; border-radius: 4px;">Menu dengan kata kunci "<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" tidak ditemukan.</p>
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