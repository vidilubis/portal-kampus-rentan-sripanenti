<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$conn = new mysqli("localhost", "root", "", "portal_kampus_rentan");

// Proses pengiriman pesan baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $user_id = $_SESSION['user_id'];
    
    // VULNERABILITY: Input disimpan ke database tanpa sanitasi (filter_var, dll)
    $stmt = $conn->prepare("INSERT INTO discussions (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
    header("Location: forum.php");
    exit;
}

$discussions = $conn->query("SELECT users.username, discussions.message FROM discussions JOIN users ON discussions.user_id = users.id ORDER BY discussions.created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F5FFFA] p-8"> <!-- Mint Cream Background -->
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4 text-[#800020]">Forum Diskusi Mahasiswa</h2>
        
        <!-- Form Kirim Pesan -->
        <form method="POST" class="mb-8">
            <textarea name="message" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-[#E6E6FA] mb-2" placeholder="Tulis sesuatu..."></textarea>
            <button type="submit" class="bg-[#E6E6FA] hover:bg-[#D8BFD8] text-gray-800 font-bold py-2 px-4 rounded">Kirim Pesan</button>
        </form>

        <!-- Menampilkan Pesan -->
        <div class="space-y-4">
            <?php while($row = $discussions->fetch_assoc()): ?>
                <div class="p-4 bg-gray-50 rounded border-l-4 border-[#98FF98]">
                    <span class="font-bold text-[#800020]"><?= htmlspecialchars($row['username']) ?>:</span>
                    <!-- VULNERABILITY: Menampilkan data mentah tanpa htmlspecialchars() -->
                    <p class="mt-2 text-gray-800"><?= $row['message'] ?></p> 
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>