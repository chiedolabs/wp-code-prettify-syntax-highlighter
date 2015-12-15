<?php
/*
Plugin Name: WP Code Prettify Ultra
Version: 1.0.0
Author: Chiedo Labs
Author URI: https://labs.chie.do
License: GPLv2 or later
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
include "moonwalk.php";

add_action( 'wp_enqueue_scripts', 'wp_code_prettify_ultra_scripts' );
function wp_code_prettify_ultra_scripts() {
  wp_enqueue_script('wp-code-prettify-ultra', 'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js', null, "0.1", false);
}

function wp_code_prettify_ultra_clean_the_body($content) {
  return preg_replace_callback(
    '#(<pre.*?prettyprint.*?>)(.*?)(</pre>)#imsu',
    create_function(
      '$i',
      'return $i[1].htmlentities(moonWalk($i[2])).$i[3];'
    ),
    $content
  );
}

add_filter( 'the_content', 'wp_code_prettify_ultra_clean_the_body');
?>
