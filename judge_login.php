<?php
require 'db.php';
require 'header.php';
loadHeader("Judge Acces");

//session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["access_code"])) {
    $stmt = $pdo->prepare("SELECT id FROM judges WHERE access_code = ?");
    $stmt->execute([$_POST["access_code"]]);

    if ($judge = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION["judge_id"] = $judge["id"];
        header("Location: judge.php");
        exit;
    } else {
        echo '<p class="alert alert-danger text-center mt-3">Invalid Judge Access Code.</p>';
    }
}
?>

<div class="container mt-4">
    <h2>Judge Login</h2>
    <form method="POST" class="text-center">
        <input type="text" name="access_code" placeholder="Enter Access Code" class="form-control mb-2" required>
        <button type="submit" class="btn btn-primary">ACCESS</button>
    </form>
</div>