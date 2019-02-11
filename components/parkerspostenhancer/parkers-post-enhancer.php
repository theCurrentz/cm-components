<?php

/*
Plugin Name: Parkers Post Enhancer
Author: Parker Westfall
Version: 1.0
*/

function posted_time_stamp() {
// First we create variables for modified time and posted time
  $posted = get_the_time('U');

  $postedcalc = current_time('U') - $posted;

  // Calculate and store the time between posted time and the current time, and then between the modified time and current time
  $ago_posted = human_time_diff($posted,current_time( 'U' ));

  // Check if difference is greater than 24 hours, if not post hours ago.
  // if ($postedcalc > 82800) {
    echo the_time('F j, Y');
  // } else {
  //   echo $ago_posted .' ago';
  //   }
}

//Repeat above steps for date modified
function modified_time_stamp() {

  $lastmodified = get_the_modified_time('U');
  $modifiedcalc = current_time('U') - $lastmodified;
  $ago_modified = human_time_diff($lastmodified,current_time('U'));

  // if ($modifiedcalc > 82800) {
    echo the_time('F j, Y g:i A');
  // } else {
  //   echo $ago_modified .' ago';
  //   }
}
?>
