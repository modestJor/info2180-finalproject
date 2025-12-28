<?php
session_start();
session_destroy();
header('Location: dolphin_crm.html');
exit;
?>