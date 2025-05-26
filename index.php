<?php
require 'db.php';
require 'header.php';
loadHeader("Scoreboard");

$scores = $pdo->query("SELECT participants.name, 
                       COALESCE(AVG(scores.points), 0) AS avg_score 
                       FROM participants 
                       LEFT JOIN scores ON participants.id = scores.participant_id 
                       GROUP BY participants.id 
                       ORDER BY avg_score DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h2>Public Scoreboard</h2>
    <p class="refresh-info">Auto-refreshes every 30 seconds</p>
    <table class="table table-striped">
        <thead><tr><th>Participant</th><th>Average Score</th></tr></thead>
        <tbody>
            <?php foreach ($scores as $score) : ?>
                <tr>
                    <td><?= htmlspecialchars($score["name"]) ?></td>
                    <td><?= number_format($score["avg_score"], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<meta http-equiv="refresh" content="30">