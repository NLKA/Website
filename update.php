<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Nightline Updater</title>
    <meta name="description" content="Update the NL website">
  </head>
  <body>
    <form action="update.php">
      <input type="hidden" name="pull" value="true" />
      <input type="submit" value="Update Website"/>
    </form>

    <?php
      if ($_GET['pull'] == true) {
        exec("git pull -v origin master 2>&1", $output, $return_var);
        
        if ($return_var == 0) {
          echo "<span style='color: green;'>Command completed successfully!</span>";
        } else {
          echo "<span style='color: red;'>Error $return_var!</span>";
        }

	echo "<br />\n";

        foreach ($output as $line) {
          echo "    $line<br />\n";
        }
      }
    ?>
  </body>
</html>
