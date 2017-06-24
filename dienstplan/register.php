<!DOCTYPE html>

<head>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
  <div id='content'>
    <h1>Nightline Karlsruhe - Registrieren</h1>
    <form action='createAccount.php' method='post'>
      <a class='fieldLabel'>Benutzername (Wichtig: Andere Nightliner sollten dich unter diesem erkennen können):</a><br>
      <input type="text" name="username"><br>
      <a class='fieldLabel'>Emailadresse:</a><br>
      <input type="text" name="email"><br>
      <a class='fieldLabel'>Passwort (mindestens 6 Zeichen):</a><br>
      <input type="password" name="password"><br>
      <input type= "submit" value="Anmelden">
    </form>
  </div>

  <?php
    require_once('footer.php');
    global $footer;
    echo $footer;
  ?>
</body>