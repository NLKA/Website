<?php

require_once('user.php');

session_start();

// Destory php session
session_destroy();

header("Location: login.php");

?>