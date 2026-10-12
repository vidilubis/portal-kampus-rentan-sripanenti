<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { 
    die("Akses ditolak. Hanya admin yang dapat mengakses halaman ini."); 
}

$conn = new mysqli("localhost", "root", "", "portal_kampus_rentan");

// Tambah Akun Baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tambah') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();
}

// Hapus Akun
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: kelola_akun.php");
}

$users = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="id">
<head><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-[#F5FFFA] p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md border-t-4 border-[#800020]">
        <h2 class="text-2xl font-bold mb-6 text-[#800020]">Manajemen Akun (Admin)</h2>

        <!-- Form Tambah User -->
        <form method="POST" class="mb-8 p-4 bg-gray-50 rounded border">
            <input type="hidden" name="action" value="tambah">
            <div class="grid grid-cols-4 gap-4">
                <input type="text" name="username" placeholder="Username" class="border p-2 rounded" required>
                <input type="text" name="password" placeholder="Password" class="border p-2 rounded" required>
                <select name="role" class="border p-2 rounded">
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="dosen">Dosen</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" class="bg-[#E6E6FA] hover:bg-[#D8BFD8] font-bold rounded">Tambah Akun</button>
            </div>
        </form>

        <!-- Tabel User -->
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 border">ID</th>
                    <th class="p-3 border">Username</th>
                    <th class="p-3 border">Role</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $users->fetch_assoc()): ?>
                    <tr>
                        <td class="p-3 border"><?= $row['id'] ?></td>
                        <td class="p-3 border"><?= htmlspecialchars($row['username']) ?></td>
                        <td class="p-3 border uppercase"><?= $row['role'] ?></td>
                        <td class="p-3 border">
                            <?php if($row['id'] !== $_SESSION['user_id']): ?>
                                <a href="?hapus=<?= $row['id'] ?>" class="text-red-500 hover:underline" onclick="return confirm('Hapus akun ini?')">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="block mt-6 text-sm text-gray-500 hover:underline">&larr; Kembali ke Dashboard</a>
    </div>
</body>
</html>