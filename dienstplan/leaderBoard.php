<?php

require_once('user.php');

function buildLeaderBoard($pUser) {
	if (!$pUser) {
        return;
    }

	// Build admin page
	echo "<h2 id='leaderBoard'>Highscore</h2>";

	$sqlConnetion = User::connect();
    $stmt = $sqlConnetion->prepare("SELECT user, COUNT(*) AS score FROM serviceDayStaff WHERE user NOT LIKE 'DummyUser' GROUP BY user ORDER BY score desc");
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    $lastScore = -1;
    $count = 1;

    // Proceed until count 2 + 1
    while (($row = $results->fetch_assoc())  && $count <= 2) {
        if ($lastScore != $row['score'] && $lastScore != -1) {
            $count++;
        }

        echo "<a>".$count.". ".$row['user']." (Score: ".$row['score'].")</a>";
        if ($count == 1) {
            echo " <a>🎉</a>";
        }
        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
        
        $lastScore = $row['score'];
    }
}

?>