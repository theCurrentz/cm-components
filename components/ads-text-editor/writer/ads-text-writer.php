<?php
// Load WordPress.
// require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
// //load phpseclib
// require __DIR__ . '/vendor/autoload.php';
// //use phpseclib namespace for SSH2 connections
// use phpseclib\Net\SSH2;
// //connect
// $ssh = new SSH2('www.domain.tld');
// if (!$ssh->login(U_AD, P_AD)) {
//     exit('Login Failed');
// }
//
// echo $ssh->exec('/bin/bash ls -al');
//
// if(isset($_COOKIE['ads-txt'])) {
//   http_response_code(200);
//   echo "Writing...";
//   write_ads_text_exe();
// } else
//   echo "You are not allowed to be here!";
// //execution
// function write_ads_text_exe() {
//   if (get_option('ads_text')) {
//     $ads_text_data = get_option('ads_text');
//     $write_file = "/var/www/html/Parker_TEST.txt";
//     $file_opened = fopen($write_file, "w") or die("Unable to open file!");;
//     if ($file_opened != false) {
//       fwrite($file_opened, get_option('ads_text'));
//       fclose($file_opened);
//     } else
//       exit();
//   }
// }
