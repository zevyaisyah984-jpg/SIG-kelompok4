<?php
session_start();
include '../db.php';

// Jika admin sudah login, langsung ke dashboard
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

// Proses login
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username dan password tidak boleh kosong!";
    } else {
        // Query login aman
        $stmt = $koneksi->prepare("SELECT username, password FROM admin WHERE username = ?");
        if (!$stmt) {
            die("Kesalahan query: " . $koneksi->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Cek password (gunakan MD5)
            if ($row['password'] === md5($password)) {
                $_SESSION['admin'] = $username;
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin | GIS SMA Padang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0d6efd 30%, #007bff 100%);
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        h3 {
            text-align: center;
            color: #0d6efd;
            margin-bottom: 25px;
            font-weight: 700;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-primary {
            width: 100%;
            background-color: #0d6efd;
            border: none;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
        }
        .btn-secondary {
            width: 100%;
            border-radius: 8px;
        }
        .alert {
            font-size: 0.9rem;
            border-radius: 8px;
        }
        .footer-text {
            text-align: center;
            font-size: 0.85rem;
            color: #888;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h3><i class="fas fa-user-lock me-2"></i> Login Admin </h3>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <input type="text" name="username" class="form-control"
                   value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                   placeholder="Masukkan username admin" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary mb-2">
            <i class="fas fa-sign-in-alt me-1"></i> Masuk
        </button>

        <a href="../index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Halaman Utama
        </a>
    </form>

    <div class="footer-text mt-3">
        © <?= date('Y') ?> GIS SMA Negeri Kota Padang
    </div>
</div>

</body>
</html>
