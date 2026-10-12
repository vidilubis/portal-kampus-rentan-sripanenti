<?php
session_start();
// Cek apakah user sudah login. Jika belum, tendang kembali ke login.php
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

// Data simulasi (sintetis) untuk mempercantik UI
$username = htmlspecialchars($_SESSION['role'] === 'mahasiswa' ? 'Budi Pratama (Mhs)' : ($_SESSION['role'] === 'dosen' ? 'Pak Andi (Dosen)' : 'Admin Portal'));
$nim_nip = $_SESSION['role'] === 'mahasiswa' ? 'NIM: 12345678' : ($_SESSION['role'] === 'dosen' ? 'NIP: 19870102' : 'ID: ADMIN');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Portal Kampus (Lab)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F5F5DC] min-h-screen flex flex-col font-sans"> <!-- Champagne Beige -->

    <!-- TOP HEADER / NAV BAR -->
    <header class="bg-[#800020] text-white py-4 px-6 md:px-12 flex justify-between items-center shadow-md"> <!-- Burgundy -->
        <div class="flex items-center space-x-3">
            <span class="text-2xl">🎓</span>
            <h1 class="text-lg md:text-xl font-bold tracking-wide">Sistem Informasi Akademik - Portal Kampus (Lab)</h1>
        </div>
        <div class="flex items-center space-x-4">
            <div class="hidden md:block text-right">
                <p class="text-sm font-semibold">Halo, <?= $username ?></p>
                <p class="text-xs text-gray-300"><?= $nim_nip ?></p>
            </div>
            <a href="login.php" class="bg-red-700 hover:bg-red-800 text-white text-xs md:text-sm font-bold py-2 px-4 rounded-lg transition duration-200">
                Keluar
            </a>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow max-w-7xl w-full mx-auto p-6 md:p-12 grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- SISI KIRI: KARTU PROFIL & INFORMASI -->
        <section class="lg:col-span-1 space-y-6">
            <!-- Kartu Profil -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
                <div class="w-24 h-24 bg-[#E6E6FA] rounded-full mx-auto flex items-center justify-center text-4xl mb-4 border-4 border-white shadow-inner">👤</div>
                <h3 class="text-xl font-bold text-gray-800"><?= $username ?></h3>
                <p class="text-sm text-gray-500 mb-2"><?= $nim_nip ?></p>
                <span class="inline-block bg-[#98FF98] text-green-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                    <?= htmlspecialchars($_SESSION['role']) ?> Aktif
                </span>
            </div>

            <!-- Pengumuman Singkat -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h4 class="font-bold text-gray-800 border-b pb-2 mb-3">📢 Pengumuman</h4>
                <ul class="text-xs text-gray-600 space-y-3">
                    <li class="border-b pb-2">
                        <span class="font-bold text-[#800020]">12 Okt 2026</span>
                        <p class="mt-1">Jadwal UAS Semester Ganjil 2026/2027 telah dirilis. Harap periksa kartu ujian Anda.</p>
                    </li>
                    <li>
                        <span class="font-bold text-[#800020]">09 Okt 2026</span>
                        <p class="mt-1">Praktikum Mandiri Keamanan Siber: Pengujian Login dan Forum Diskusi masih berlangsung.</p>
                    </li>
                </ul>
            </div>
        </section>

        <!-- SISI KANAN: MENU UTAMA -->
        <section class="lg:col-span-3 space-y-6">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-800 mb-2">Selamat Datang, <?= $username ?>! 👋</h2>
                <p class="text-gray-500 mb-8">Silakan pilih layanan akademik fiktif yang ingin Anda akses atau uji coba di bawah ini.</p>

                <!-- GRID KARTU MENU -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    
                    <!-- Kartu: Forum Diskusi (Semua Role) -->
                    <a href="forum.php" class="group flex flex-col justify-between p-6 rounded-2xl bg-[#E6E6FA] hover:bg-[#D8BFD8] transition duration-300 transform hover:-translate-y-1 shadow-sm border border-purple-100">
                        <div>
                            <span class="text-3xl block mb-4">💬</span>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Forum Diskusi Kampus</h3>
                            <p class="text-xs text-gray-600 leading-relaxed">Diskusikan materi kuliah, tugas, atau sekadar berbagi cerita dengan civitas akademika lainnya.</p>
                        </div>
                        <span class="mt-6 text-xs font-bold text-purple-800 group-hover:underline">Buka Forum &rarr;</span>
                    </a>

                    <!-- Kartu: Data Nilai (Dosen & Mahasiswa) -->
                    <a href="nilai.php" class="group flex flex-col justify-between p-6 rounded-2xl bg-[#FFE4E1] hover:bg-[#FAD02C]/10 transition duration-300 transform hover:-translate-y-1 shadow-sm border border-red-500/10"> <!-- Muted red/pink -->
                        <div>
                            <span class="text-3xl block mb-4">📊</span>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Data Nilai Akademik</h3>
                            <p class="text-xs text-gray-600 leading-relaxed">Pantau hasil belajar Anda di semester ini atau kelola nilai mahasiswa (akses khusus Dosen).</p>
                        </div>
                        <span class="mt-6 text-xs font-bold text-red-800 group-hover:underline">Lihat Nilai &rarr;</span>
                    </a>

                    <!-- Kartu: Unggah Tugas (Khusus Mahasiswa) -->
                    <?php if ($_SESSION['role'] === 'mahasiswa'): ?>
                    <a href="tugas.php" class="group flex flex-col justify-between p-6 rounded-2xl bg-[#F5FFFA] hover:bg-[#98FF98]/40 transition duration-300 transform hover:-translate-y-1 shadow-sm border border-[#98FF98]/30">
                        <div>
                            <span class="text-3xl block mb-4">📤</span>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Unggah Tugas Kuliah</h3>
                            <p class="text-xs text-gray-600 leading-relaxed">Kirim tugas kuliah Anda dalam berbagai format file sesuai tenggat waktu yang ditentukan.</p>
                        </div>
                        <span class="mt-6 text-xs font-bold text-emerald-800 group-hover:underline">Unggah File &rarr;</span>
                    </a>
                    <?php endif; ?>

                    <!-- Kartu: Manajemen Akun (Khusus Admin) -->
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="kelola_akun.php" class="group flex flex-col justify-between p-6 rounded-2xl bg-[#FFF5EE] hover:bg-orange-100 transition duration-300 transform hover:-translate-y-1 shadow-sm border border-orange-100">
                        <div>
                            <span class="text-3xl block mb-4">🛠️</span>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Manajemen Akun</h3>
                            <p class="text-xs text-gray-600 leading-relaxed">Kelola semua kredensial pengguna, tambahkan mahasiswa baru, dosen, atau administrator sistem.</p>
                        </div>
                        <span class="mt-6 text-xs font-bold text-orange-800 group-hover:underline">Kelola &rarr;</span>
                    </a>
                    <?php endif; ?>

                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-200 py-4 text-center text-xs text-gray-500">
        © 2026 Portal Kampus Praktikum Keamanan Siber. Semua Data Fiktif.
    </footer>

</body>
</html>