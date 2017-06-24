<?php

require_once('config.php');
require_once('hashing.php');
require_once('userFetch.php');

class User {
  // - - - - - - - - - - - -
  // PROPERTIES, REFERENCES
  // - - - - - - - - - - - -
  
  private $userData;  // user, password, email, sessionStart, sessionID
  private $sqlConnetion;

  // - - - - - - - - -
  // PRIVATE FUNCTIONS
  // - - - - - - - - -
  
  private function __construct($pData, &$pSqlConnection) {
    $this->userData = (array)$pData;
    $this->sqlConnetion = $pSqlConnection;
  }

  // - - - - - - - - -
  // PUBLIC FUNCTIONS
  // - - - - - - - - -

  public function __get($pValue) {
    if (isset($this->userData[$pValue])) {
      return $this->userData[$pValue];
    } else {
      throw new Exception('User: cannot __get('.$pValue.') because it does not exist in $this->data'.print_r($this->userData));
    }
  }

  public function query($pQuery) {
    $this->sqlConnetion->query($pQuery);
  }

  public static function create($pUser, $pPassword, $pEmail) {
    $sqlConnetion = User::connect();

    // Check if user name or email taken
    if (checkUsernameExists($sqlConnetion, $pUser) ||
        checkEmailExists($sqlConnetion, $pEmail)) {
      throw new Exception('Name oder Emailadresse schon vergeben');
    }

    // Check if email is valid
    if (!filter_var($pEmail, FILTER_VALIDATE_EMAIL)) {
      throw new Exception('Keine gültige Emailadresse angegeben');
    }

    // Check if password is long enough
    if (strlen($pPassword) < 6) {
      throw new Exception('Passwort zu kurz (mindestens 6 Zeichen)');
    }

    // Prepare password
    $password = $sqlConnetion->real_escape_string(usersHash($pPassword));

    // Write user to db
    $stmt = $sqlConnetion->prepare("INSERT INTO user (user, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $pUser, $password, $pEmail);
    $stmt->execute();
    $stmt->close();
  }
  
  public static function connect() {
    global $dbName, $dbServer, $dbUser, $dbPassword;
    $sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword, $dbName);
    if ($sqlConnetion->connect_errno) {
      die('Failed to connect to MySQL: '.$mysqli->connect_error);
    }
    
    return $sqlConnetion;
  }

  public static function loginPassword($pUser, $pPassword) {
    $passwordHashed = usersHash($pPassword);

    // Query db for user and password
    $sqlConnetion = User::connect();
    $stmt = $sqlConnetion->prepare("SELECT * FROM user WHERE user = ? AND password = ? AND activated = 1");
    $stmt->bind_param('ss', $pUser, $passwordHashed);
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    // Check query results
    if ($results->num_rows != 1) {
      return false;
    }

    // Begin session
    session_start();
    $_SESSION['sessionStart'] = time();

    return new User($results->fetch_assoc(), $sqlConnetion);
  }

  public static function loginSession() {
    global $sessionTimeout;

    session_start();

    // Query db for activated user
    $sqlConnetion = User::connect();
    $stmt = $sqlConnetion->prepare("SELECT * FROM user WHERE user = ? AND activated = 1");
    $stmt->bind_param('s', $_SESSION['user']);
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    // First, check query results for trivial case
    if ($results->num_rows != 1) {
      return false;
    }

    // Secondly, check if session has timed out
    $resultsFetched = $results->fetch_assoc();
    if ((time() - $_SESSION['sessionStart']) > $sessionTimeout) {
      // Remove session entry 
      $_SESSION['user'] = '';
      return false;
    }
      
    // Successful authentification via session
    return new User($resultsFetched, $sqlConnetion);
  }

  public static function changePassword($pUser, $pNewPw) {
    if ($pNewPw)
    $password = usersHash($pNewPw);

    // Write to db
    $sqlConnetion = User::connect();
    $stmt = $sqlConnetion->prepare("UPDATE user SET password = ? WHERE user = ?");
    $stmt->bind_param('ss', $password, $pUser);
    $stmt->execute();
    $stmt->close();
        
    return true;
  }

  public static function changeEmail($pUser, $pEmail) {
    if (filter_var($pEmail, FILTER_VALIDATE_EMAIL)) {
      $sqlConnetion = User::connect();
      $stmt = $sqlConnetion->prepare("UPDATE user SET email = ? WHERE user = ?;");
      $stmt->bind_param('ss', $pEmail, $pUser);
      $stmt->execute();
      $stmt->close();
      return true;
    } else {
      return false;
    }
  }
  
}

?>