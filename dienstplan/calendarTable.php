<?php

require_once('config.php');

function buildCalendarTable($pUser) {
	global $dbServer, $dbUser, $dbPassword, $dbName;

	if (!$pUser) {
		return;
	}

	 // Add new service day form
	echo "<h1>Öffentlicher Kalendar</h1>";
	echo "<h2>Neuer Eintrag</h2>";
  echo "<form action='calendarModify.php' method='get'>";
    echo "<label>Datum: </label>";
    echo "<input type='hidden' name='op' value='add' />";
    echo "<input type='text' id='datepicker' name='date' placeholder='JJJJ-MM-TT'> <br/>";
    echo "<label>Uhrzeit: </label>";
    echo "<input type='text' id='timepicker' name='time' placeholder='Zeit, Dauer oder leer'> <br/>";
    echo "<label>Eintrag: </label>";
    echo "<input type='text' id='subject' name='entry'> <br/>";
    echo "<input type='submit' value='Anlegen'/>";
  echo "</form>";

  echo "<h2>Alle Einträge</h2>";

  $sqlConnetion = User::connect();
  $stmt = $sqlConnetion->prepare("SELECT * FROM calendarEntry ORDER BY date DESC");
  $stmt->execute();
  $results = $stmt->get_result();
  $stmt->close();

  if ($results->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Datum</th><th>Uhrzeit</th><th>Eintrag</th></tr>";
      while ($row = $results->fetch_assoc()) {
        echo "<tr>";
          echo "<td>";
            echo "<p>".$row['date']."</p>";
          echo "</td>";
            
          echo "<td>";
            if ($row['time'] == "") {
              echo "--";
            } else {
              echo "<p>".$row['time']."</p>";
            }
          echo "</td>";

          echo "<td>";
            echo "<p>".$row['entry']."</p>";
            echo "<p><a class='redButton' href='calendarModify.php?op=delete&id=".$row['entryId']."'>Löschen</a></p>";
          echo "</td>";
        echo "</tr>";
      }
    echo "</table>";
  } else {
    echo "<p>Noch keine Kalendereinträge vorhanden.</p>";
  }

  // Include scripts and build date picker
  echo "<script src='js/jquery-ui/external/jquery/jquery.js'></script>";
  echo "<script src='js/jquery-ui/jquery-ui.min.js'></script>";
  echo "<script>$(function() { $('#datepicker').datepicker({dateFormat:'yy-mm-dd'}); });</script>";
}

?>