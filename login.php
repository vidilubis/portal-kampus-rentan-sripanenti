<?php
session_start();
$conn = new mysqli("localhost", "root", "", "portal_kampus_rentan");

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // VULNERABILITY: Tidak ada rate limiting, captcha, atau jeda waktu.
    // Penyerang bisa melakukan brute-force dengan ribuan request per menit.
    $stmt = $conn->prepare("SELECT id, role FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $row['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Portal Kampus (Lab)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F5F5DC] min-h-screen flex items-center justify-center p-4 font-sans"> <!-- Champagne Beige Background -->
    
    <!-- Container Utama -->
    <div class="bg-white rounded-3xl shadow-2xl flex flex-col md:flex-row w-full max-w-4xl overflow-hidden border border-gray-100">
        
        <!-- Sisi Kiri: Branding Kampus (Warna Burgundy) -->
        <div class="md:w-1/2 bg-[#800020] text-white p-10 flex flex-col justify-center relative overflow-hidden">
            <!-- Ornamen Dekoratif Geometris -->
            <div class="absolute top-[-50px] left-[-50px] w-32 h-32 bg-white opacity-10 rounded-full"></div>
            <div class="absolute bottom-[-80px] right-[-50px] w-48 h-48 bg-[#D8BFD8] opacity-20 rounded-full"></div>
            
            <div class="relative z-10">
                <div class="text-6xl mb-6">🎓</div>
                <h1 class="text-3xl font-extrabold mb-4 leading-tight">Sistem Informasi Akademik</h1>
                <p class="text-[#E6E6FA] text-sm leading-relaxed mb-8">
                    Selamat datang di Portal Kampus Terpadu. Akses layanan akademik, forum diskusi, dan kelola tugas Anda dalam satu tempat.
                </p>
                <div class="inline-block bg-white/20 px-4 py-2 rounded-lg text-xs font-semibold backdrop-blur-sm border border-white/30">
                    ⚠️ Lab Praktikum Keamanan Siber
                </div>
            </div>
        </div>

        <!-- Sisi Kanan: Form Login -->
        <div class="md:w-1/2 p-10 flex flex-col justify-center bg-white">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang Kembali! 👋</h2>
                <p class="text-gray-500 text-sm">Silakan masuk menggunakan kredensial Anda.</p>
            </div>

            <!-- Pesan Error -->
            <?php if($error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg animate-pulse">
                    <p class="text-red-700 text-sm font-semibold"><?= $error ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                    <input type="text" id="username" name="username" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#98FF98] focus:border-transparent transition duration-200" 
                        placeholder="Contoh: budi_mhs" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#98FF98] focus:border-transparent transition duration-200" 
                        placeholder="••••••••" required>
                </div>
                
                <button type="submit" 
                    class="w-full mt-4 bg-[#E6E6FA] hover:bg-[#D8BFD8] text-[#800020] font-bold py-3 px-4 rounded-xl shadow-sm transition duration-300 transform hover:-translate-y-1">
                    Masuk ke Portal
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100 text-center text-xs text-gray-400">
                <p>Tidak bisa masuk? Hubungi Administrator BKM/IT.</p>
            </div>
        </div>
    </div>

</body>
</html>