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

/*
 * Load the script
 */
add_action( 'wp_enqueue_scripts', 'wp_code_prettify_ultra_scripts' );
function wp_code_prettify_ultra_scripts() {
  wp_enqueue_script('wp-code-prettify-ultra', 'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js', null, "0.1", false);
}

/*
 * The filter to handle processing of the prettyprint pre tags
 */
add_filter( 'the_content', 'wp_code_prettify_ultra_clean_the_body');
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

/*
 * Configure the settings page
 */
add_action('admin_menu', 'wp_code_prettify_ultra_settings');
function wp_code_prettify_ultra_settings() {
  add_menu_page('Code Prettify', 'Code Prettify', 'administrator', 'wp_code_prettify_ultra_settings', 'wp_code_prettify_ultra_display_settings');
}

function wp_code_prettify_ultra_display_settings() {
  $options = array("Default","Desert");
  $options_dom = "";
  foreach($options as $option) {
    if(get_option('wp_code_prettify_ultra_theme') == $option) { 
      $options_dom = $options_dom . "<option value='$option' selected>$option</option>";
    } else {
      $options_dom = $options_dom . "<option value='$option'>$option</option>";
    }
  }
  ?>
  </pre>
  <div class="wrap">
    <form action="options.php" method="post" name="options">
      <h2>Select Your Settings</h2>
      <?php echo wp_nonce_field('update-options') ?>
      <?php echo get_option('wp_code_prettify_ultra_theme') ?>
      <label>Display Themes</label>
      <select name="wp_code_prettify_ultra_theme">
        <?php echo $options_dom ?>
      </select>
      <input type="submit" name="Submit" value="Update" />
      <input type="hidden" name="action" value="update" />
      <input type="hidden" name="page_options" value="wp_code_prettify_ultra_theme" />
    </form>
  </div>
  <pre>
  <?php
}
?>
