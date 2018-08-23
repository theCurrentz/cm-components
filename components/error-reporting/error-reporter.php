<?php
if ( function_exists( 'mail' ) )
{
echo 'mail() is available';
//send email error report
  mail(
    "pjwestfall@yahoo.com",
    "Test",
    "Testing 1234",
    "From: parker@timbral.com"
  );
}
else
{
echo 'mail() has been disabled';
}
