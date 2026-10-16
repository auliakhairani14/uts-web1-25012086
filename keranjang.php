<?php
require_once 'includes/header.php';
require_once 'includes/fungsi.php';

$keranjang = isset($_SESSION['keranjang']) ? $_SESSION['keranjang'] : [];
?>

<h2 style="color: #343a40;">🛒 Keranjang Belanja Anda</h2>

<?php if (empty($keranjang)): ?>
    <div style="background-color: #e2e3e5; padding: 15px; border-radius: 4px; color: #383d41;">
        Keranjang Anda masih kosong. Silakan kembali ke <a href="index.php" style="color: #007bff; font-weight: bold;">Daftar Menu</a> untuk memesan.
    </div>
<?php else: ?>
    <div style="background-color: #fff; border: 1px solid #dee2e6; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <ul style="list-style-type: none; padding: 0; margin: 0;">
            <?php 
            $nota = hitungTotalPembayaran($keranjang);
            foreach ($keranjang as $item): 
                $sub_item = hitungSubtotalItem($item['harga'], $item['jumlah']);
            ?>
                <li style="padding: 10px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="color: #007bff;"><?php echo htmlspecialchars($item['nama'], ENT_QUOTES, 'UTF-8'); ?></strong><br>
                        <span style="font-size: 14px; color: #6c757d;">
                            <?php echo $item['jumlah']; ?> pcs x Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?>
                        </span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <span style="font-weight: bold; color: #333;">Rp <?php echo number_format($sub_item, 0, ',', '.'); ?></span>
                        
                        <!-- Form Tombol Hapus 1 Item -->
                        <form method="POST" action="proses.php" style="margin: 0;">
                            <input type="hidden" name="aksi" value="hapus_item">
                            <input type="hidden" name="kode_menu" value="<?php echo $item['kode']; ?>">
                            <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;">Hapus</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Bagian Perhitungan Diskon Nota Pembayaran -->
        <div style="margin-top: 20px; background-color: #f8f9fa; padding: 15px; border-left: 4px solid #28a745; border-radius: 4px;">
            <p style="margin: 5px 0;">Subtotal Seluruh: <strong>Rp <?php echo number_format($nota['subtotal'], 0, ',', '.'); ?></strong></p>
            <p style="margin: 5px 0; color: #dc3545;">Potongan Diskon (10%): <strong>- Rp <?php echo number_format($nota['diskon'], 0, ',', '.'); ?></strong></p>
            <hr style="border: 0; border-top: 1px solid #ccc; margin: 10px 0;">
            <h3 style="margin: 0; color: #28a745;">Total Akhir: Rp <?php echo number_format($nota['total_akhir'], 0, ',', '.'); ?></h3>
        </div>

        <br>
        <!-- Form Kosongkan Semua Keranjang -->
        <form method="POST" action="proses.php">
            <input type="hidden" name="aksi" value="kosongkan">
            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang?')" style="background-color: #6c757d; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; width: 100%;">🗑️ Kosongkan Keranjang Belanja</button>
        </form>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>