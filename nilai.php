<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$conn = new mysqli("localhost", "root", "", "portal_kampus_rentan");
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Logika Dosen: Memberi Nilai
if ($role === 'dosen' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $course = $_POST['course_name'];
    $score = $_POST['score'];
    
    $stmt = $conn->prepare("INSERT INTO grades (student_id, course_name, score) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $student_id, $course, $score);
    $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="id">
<head><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-[#F5F5DC] p-8">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md border-t-4 border-[#800020]">
        <h2 class="text-2xl font-bold mb-6 text-[#800020]">Data Nilai Akademik</h2>

        <?php if ($role === 'dosen'): ?>
            <!-- Tampilan Dosen: Form Input Nilai -->
            <form method="POST" class="mb-8 p-4 bg-[#F5FFFA] rounded-lg border border-[#98FF98]">
                <h3 class="font-bold mb-2">Input Nilai Mahasiswa</h3>
                <div class="grid grid-cols-3 gap-4">
                    <input type="number" name="student_id" placeholder="ID Mahasiswa (Misal: 1)" class="border p-2 rounded" required>
                    <input type="text" name="course_name" placeholder="Mata Kuliah" class="border p-2 rounded" required>
                    <input type="number" name="score" placeholder="Nilai (0-100)" class="border p-2 rounded" required>
                </div>
                <button type="submit" class="mt-4 bg-[#E6E6FA] hover:bg-[#D8BFD8] font-bold py-2 px-4 rounded">Simpan Nilai</button>
            </form>
        <?php endif; ?>

        <!-- Tampilan Tabel Nilai (Untuk Dosen dan Mahasiswa) -->
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#E6E6FA]">
                    <th class="p-3 border">Mahasiswa (ID)</th>
                    <th class="p-3 border">Mata Kuliah</th>
                    <th class="p-3 border">Nilai</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Jika mahasiswa, hanya lihat nilainya sendiri. Jika dosen, lihat semua.
                $query = "SELECT users.username, grades.course_name, grades.score FROM grades JOIN users ON grades.student_id = users.id";
                if ($role === 'mahasiswa') {
                    $query .= " WHERE grades.student_id = $user_id";
                }
                $result = $conn->query($query);
                while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="p-3 border"><?= htmlspecialchars($row['username']) ?></td>
                        <td class="p-3 border"><?= htmlspecialchars($row['course_name']) ?></td>
                        <td class="p-3 border font-bold text-[#800020]"><?= $row['score'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="block mt-6 text-sm text-gray-500 hover:underline">&larr; Kembali ke Dashboard</a>
    </div>
</body>
</html>