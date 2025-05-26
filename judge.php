<?php
require 'db.php';
require 'header.php';
loadHeader("Judges Portal");

//session_start();
if (!isset($_SESSION["judge_id"])) {
    header("Location: judge_login.php");
    exit;
}

// Fetch participants & existing scores
$participants = $pdo->query("SELECT participants.id, participants.name, 
                             COALESCE(AVG(scores.points), 0) AS avg_score 
                             FROM participants 
                             LEFT JOIN scores ON participants.id = scores.participant_id 
                             GROUP BY participants.id 
                             ORDER BY avg_score DESC")->fetchAll(PDO::FETCH_ASSOC);

// Submit scores
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["participant_id"]) && isset($_POST["points"])) {
    $stmt = $pdo->prepare("INSERT INTO scores (judge_id, participant_id, points) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION["judge_id"], $_POST["participant_id"], $_POST["points"]]);
    //header("Refresh:0"); // Auto-refresh page after submission
}
?>

<div class="container mt-4">
    <h2>Assign Scores</h2>
    <form method="POST">
        <select name="participant_id" class="form-control mb-2">
            <?php foreach ($participants as $participant) : ?>
                <option value="<?= $participant['id'] ?>"><?= htmlspecialchars($participant['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="points" min="1" max="100" placeholder="Assign Points" class="form-control mb-2" required>
        <button type="submit" class="btn btn-success">Submit Score</button>
    </form>

    <h3>Participants & Scores</h3>
    <table class="table table-striped mt-4">
        <thead><tr><th>Name</th><th>Average Score</th></tr></thead>
        <tbody>
            <?php foreach ($participants as $participant) : ?>
                <tr>
                    <td><?= htmlspecialchars($participant["name"]) ?></td>
                    <td><?= number_format($participant["avg_score"], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>