<?php
/*
Plugin Name: WP Code Prettify Ultra
Version: 1.0.0
Author: Chiedo Labs
Author URI: https://labs.chie.do
License: GPLv2 or later
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
include "shortcode-wpautop-control.php";
include "moonwalk.php";

add_action( 'wp_enqueue_scripts', 'wp_code_prettify_ultra_scripts' );
function wp_code_prettify_ultra_scripts() {
  wp_enqueue_script('wp-code-prettify-ultra', 'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js', null, "0.1", false);
}

// Create the shortcode
add_shortcode( 'code', 'code' );
function code( $atts , $content = null ) {
  $atts = shortcode_atts( array(
    'lang' => '',
  ), $atts, 'code' );

  if($atts['lang']) {
    $lang = 'lang-'.$atts['lang'];
  } else {
    $lang = '';
  }

  ob_start();
?>
  <pre class="prettyprint <?php echo $lang?>"><?php echo htmlentities(moonWalk($content)) ?></pre>
<?php
  $result = ob_get_contents ();
  ob_end_clean();
  return $result;
}

chiedolabs_shortcode_wpautop_control(array('code'));
?>
