<?php 
require_once("/etc/apache2/capstone-mysql/Secrets.php");

//absolute path to YOUR ini file on the server 
$secrets =  new Secrets("/etc/apache2/capstone-mysql/assessment.ini");
$pdo = $secrets->getPdoObject();
