<?php
require 'db.php';
require 'header.php';
loadHeader("Admin Panel");

//session_start();

// Set session timeout (5 minutes)
$timeout = 300; // 300 seconds = 5 minutes

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: logout.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity timestamp

// Authenticate admin
if (!isset($_SESSION["admin_authenticated"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["admin_code"])) {
        $stmt = $pdo->prepare("SELECT id FROM admins WHERE admin_code = ?");
        $stmt->execute([$_POST["admin_code"]]);

        if ($stmt->rowCount() > 0) {
            $_SESSION["admin_authenticated"] = true;
            header("Location: admin.php"); // ✅ Redirect after successful login
            exit;
        } else {
            echo '<p class="alert alert-danger">Invalid Admin Code.</p>';
        }
    }

    echo '<div class="container mt-4 text-center">
        <h2>Admin Login</h2>
        <form method="POST">
            <input type="text" name="admin_code" placeholder="Enter Admin Code" class="form-control mb-2" required>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>';
    exit;
}

// Add judge
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_judge"])) {
    $stmt = $pdo->prepare("INSERT INTO judges (username, display_name, access_code) VALUES (?, ?, ?)");
    $stmt->execute([$_POST["username"], $_POST["display_name"], $_POST["access_code"]]);
}

// Add participant
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_participant"])) {
    $stmt = $pdo->prepare("INSERT INTO participants (name) VALUES (?)");
    $stmt->execute([$_POST["participant_name"]]);
}

// Fetch data
$judges = $pdo->query("SELECT * FROM judges")->fetchAll(PDO::FETCH_ASSOC);
$participants = $pdo->query("SELECT * FROM participants")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h2>Admin Panel - Manage Judges & Participants</h2>

    <!-- Judges Table -->
    <h3>Judges</h3>
    <table class="table table-striped">
        <thead><tr><th>Display Name</th><th>Access Code</th></tr></thead>
        <tbody>
            <?php foreach ($judges as $judge) : ?>
                <tr>
                    <td><?= htmlspecialchars($judge["display_name"]) ?></td>
                    <td><?= htmlspecialchars($judge["access_code"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button class="btn btn-success mb-3" onclick="document.getElementById('judge-form').style.display='block'">Add Judge</button>

    <form id="judge-form" style="display:none;" method="POST">
        <input type="text" name="username" placeholder="Judge Username" class="form-control mb-2" required>
        <input type="text" name="display_name" placeholder="Judge Display Name" class="form-control mb-2" required>
        <input type="text" name="access_code" placeholder="Access Code" class="form-control mb-2" required>
        <button type="submit" name="add_judge" class="btn btn-success">Confirm Add</button>
    </form>

    <!-- Participants Table -->
    <h3>Participants</h3>
    <table class="table table-striped">
        <thead><tr><th>Name</th></tr></thead>
        <tbody>
            <?php foreach ($participants as $participant) : ?>
                <tr><td><?= htmlspecialchars($participant["name"]) ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button class="btn btn-primary mb-3" onclick="document.getElementById('participant-form').style.display='block'">Add Participant</button>

    <form id="participant-form" style="display:none;" method="POST">
        <input type="text" name="participant_name" placeholder="Participant Name" class="form-control mb-2" required>
        <button type="submit" name="add_participant" class="btn btn-primary">Confirm Add</button>
    </form>
</div>

<script>
    // Auto logout when admin leaves the page
    window.addEventListener("beforeunload", function () {
        fetch("logout.php"); // ✅ Calls logout.php when leaving
    });

    // Hide forms after submission
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", () => form.style.display = "none");
    });
</script>