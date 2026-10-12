<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') { 
    die("Akses ditolak. Hanya mahasiswa yang dapat mengakses halaman ini."); 
}

$conn = new mysqli("localhost", "root", "", "portal_kampus_rentan");
$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_tugas'])) {
    $target_dir = "uploads/";
    $file_name = basename($_FILES["file_tugas"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["file_tugas"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO tasks (student_id, file_path) VALUES (?, ?)");
        $stmt->bind_param("is", $_SESSION['user_id'], $target_file);
        $stmt->execute();
        $pesan = "Tugas berhasil diunggah!";
    } else {
        $pesan = "Gagal mengunggah tugas. Pastikan folder 'uploads' sudah dibuat.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-[#F5F5DC] p-8">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md border-t-4 border-[#98FF98]">
        <h2 class="text-2xl font-bold mb-4 text-[#800020]">Unggah Tugas</h2>
        
        <?php if($pesan): ?>
            <p class="mb-4 text-green-600 font-bold"><?= $pesan ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block mb-2 font-bold text-gray-700">Pilih File Tugas</label>
                <input type="file" name="file_tugas" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="bg-[#E6E6FA] hover:bg-[#D8BFD8] font-bold py-2 px-4 rounded">Upload</button>
        </form>
        <a href="dashboard.php" class="block mt-6 text-sm text-gray-500 hover:underline">&larr; Kembali ke Dashboard</a>
    </div>
</body>
</html>