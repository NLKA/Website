<?php

require_once('config.php');

function usersHash($pValue) {
  global $dbSalt;
  return addslashes(crypt($pValue, $dbSalt));
}

?>