<?php
// Fungsi untuk menghitung subtotal per baris menu
function hitungSubtotalItem($harga, $jumlah) {
    return $harga * $jumlah;
}

// Fungsi utama perhitungan nota (Subtotal, Diskon, Total Akhir)
function hitungTotalPembayaran($keranjang) {
    $subtotal_seluruh = 0;
    
    foreach ($keranjang as $item) {
        $subtotal_seluruh += hitungSubtotalItem($item['harga'], $item['jumlah']);
    }

    // Ketentuan UTS: Diskon 10% jika subtotal minimal Rp50.000
    $diskon = 0;
    if ($subtotal_seluruh >= 50000) {
        $diskon = 0.10 * $subtotal_seluruh;
    }

    $total_akhir = $subtotal_seluruh - $diskon;

    return [
        'subtotal' => $subtotal_seluruh,
        'diskon' => $diskon,
        'total_akhir' => $total_akhir
    ];
}