<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$error = '';

/* ======================
   PROSES LOGIN
====================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];

        // WAJIB ADA EXIT
        header("Location: ../index.php");
        exit;

    } else {
        $error = "Email atau password salah";
    }
}

/* ======================
   BARU LOAD HEADER
====================== */
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <h2>Login</h2>

    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" autocomplete="off">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
    <p>Belum punya akun? <a href="register.php">register</a></p>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
