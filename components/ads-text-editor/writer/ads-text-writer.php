<?php
//load phpseclib
require dirname(dirname(__FILE__)) . '/vendor/autoload.php';
//execution
function write_ads_text_exe() {
  if (get_option('ads_text')) {
    $ads_text_data = get_option('ads_text');
    $write_file = "/var/www/html/ads.txt";
    $file_opened = fopen($write_file, "w") or die("Unable to open file!");;
    if ($file_opened != false) {
      fwrite($file_opened, get_option('ads_text'));
      fclose($file_opened);
    } else
      exit();
  }
}
//write ads.txt from database to file, conditional logic for writing to idrop servers
function write_ads_text() {
  //execute write
  write_ads_text_exe();
  //connect via ssh to idrop servers
  if(get_bloginfo('name') == 'iDrop News') {
    foreach(IDN_IP_S as $an_ip) {
      //connect
      $ssh = new phpseclib\Net\SSH2($an_ip);
      if (!$ssh->login(U_AD, P_AD)) {
          exit('Login Failed');
      }
      //copys
      $scp = new phpseclib\Net\SCP($ssh);
      if (!$scp->put('/var/www/html/ads.txt',
               '/var/www/html/ads.txt',
               1))
      {
        throw new Exception("Failed to send file");
      }
    }
  }
}

if(isset($_POST['ads_text'])) {
  write_ads_text();
}
