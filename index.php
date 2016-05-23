<?php
session_start();
$page = isset($_SESSION['auth'])?"admin":"auth";
header("Location:".$page.'.php');