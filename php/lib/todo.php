<?php
namespace  uss-hopper\assessment;

use 
require_once("/etc/apache2/capstone-mysql/Secrets.php");
require_once ("autoload.php");
require_once (dirname(__DIR__, 2) . "/vendor/autoload.php");

//absolute path to YOUR ini file on the server 
$secrets =  new Secrets("/etc/apache2/capstone-mysql/assessment.ini");
$pdo = $secrets->getPdoObject();



