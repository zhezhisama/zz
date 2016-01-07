<?php 
session_start();
setcookie("count",$_GET['count'],time()+(3600*24*365*10));
?>