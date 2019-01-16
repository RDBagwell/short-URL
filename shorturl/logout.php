<?php
require_once('header.php');
$session->logout();
header("Location: index.php");