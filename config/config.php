<?php
//　開発時のエラー出力
ini_set("display_errors", 1);
error_reporting(E_ALL);

// 文字化け対策
header("Content-type: text/html; charset=UTF-8");

define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);

session_start();
