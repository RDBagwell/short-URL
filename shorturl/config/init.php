<?php

/***** Configurations *****/
ob_start();
require_once("new_config.php");
require_once("database.php");

/***** Objects *****/
require_once("objects/session.php");
require_once("objects/db_object.php");
require_once("objects/users.php");
require_once("objects/comments.php");
require_once("objects/url.php");