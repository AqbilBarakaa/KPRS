/* assets/js/script.js */

document.addEventListener("DOMContentLoaded", function() {
    
    // --- 1. Auto-Close Alert (Notifikasi Hijau) ---
    // Mencari elemen dengan id 'success-alert'
    var successAlert = document.getElementById('success-alert');
    
    // Jika ditemukan, set waktu 3 detik lalu tutup
    if (successAlert) {
        setTimeout(function() {
            // Menggunakan Bootstrap Alert instance untuk menutup
            var bsAlert = new bootstrap.Alert(successAlert);
            bsAlert.close();
        }, 3000); // 3000 ms = 3 detik
    }

    // --- 2. Konfirmasi Delete (Opsional Global) ---
    // Jika ingin memindahkan 'return confirm' dari inline HTML ke sini,
    // Anda bisa menambahkan event listener untuk semua tombol ber-class 'btn-delete'
    // Tapi untuk saat ini, inline onsubmit="return confirm..." masih efisien.
});