<?php
session_start();
$_SESSION = [];
session_destroy();

// Your login page file is dolphin_crm.html
header("Location: dolphin_crm.html");
exit;
