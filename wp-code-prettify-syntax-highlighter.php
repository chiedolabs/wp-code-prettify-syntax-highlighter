<?php
/*
Plugin Name: WP Code Prettify Syntax Highlighter
Version: 1.0.0
Author: Chiedo Labs
Description: The best Google Code Prettify WordPress plugin. 
Author URI: https://labs.chie.do
License: GPLv2 or later
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
include "moonwalk.php";

/*
 * Load the script
 */
add_action( 'wp_enqueue_scripts', 'wp_code_prettify_syntax_highlighter_scripts' );
function wp_code_prettify_syntax_highlighter_scripts() {
  wp_enqueue_script('wp-code-prettify-syntax-highlighter', plugin_dir_url( __FILE__ )."/js/run_prettify.js", null, "0.1", false);
}

/*
 * The filter to handle processing of the prettyprint pre tags
 */
add_filter( 'the_content', 'wp_code_prettify_syntax_highlighter_clean_the_body');
function wp_code_prettify_syntax_highlighter_clean_the_body($content) {
  return preg_replace_callback(
    '#(<pre.*?prettyprint.*?>)(.*?)(</pre>)#imsu',
    create_function(
      '$i',
      'return $i[1].htmlentities(wp_code_prettify_syntax_highlighter_moonWalk($i[2])).$i[3];'
    ),
    $content
  );
}

/*
 * Configure the settings page
 */
add_action('admin_menu', 'wp_code_prettify_syntax_highlighter_settings');
function wp_code_prettify_syntax_highlighter_settings() {
  add_menu_page('Code Prettify', 'Code Prettify', 'administrator', 'wp_code_prettify_syntax_highlighter_settings', 'wp_code_prettify_syntax_highlighter_display_settings');
}

function wp_code_prettify_syntax_highlighter_display_settings() {
  $options = array("Default","Desert", "Sunburst","Doxy", "Monokai");
  $options_dom = "";
  foreach($options as $option) {
    if(get_option('wp_code_prettify_syntax_highlighter_theme') == $option) { 
      $options_dom = $options_dom . "<option value='$option' selected>$option</option>";
    } else {
      $options_dom = $options_dom . "<option value='$option'>$option</option>";
    }
  }

  ?>
  </pre>
  <div class="wrap">
    <form action="options.php" method="post" name="options">
      <h2>WP Code Prettify Syntax Highlighter Options</h2>
      <?php echo wp_nonce_field('update-options') ?>

      <label>Choose a built-in theme</label>
      <select name="wp_code_prettify_syntax_highlighter_theme">
        <?php echo $options_dom ?>
      </select>
      <br/>
      <br/>
      You can also use a custom theme by setting the built-in theme to "Default" and adding one of the themes from <a href="http://jmblog.github.io/color-themes-for-google-code-prettify/" target="_blank">here</a> in the textarea below.
      <br />
      <br />
      <div>
        <label>Add custom css:&nbsp;</label>
        <br/>
        <br/>
        <textarea style="width: 50%; min-width: 200px; height: 150px;" name="wp_code_prettify_syntax_highlighter_custom_styles"><?php echo get_option('wp_code_prettify_syntax_highlighter_custom_styles') ?></textarea>
      </div>
      <br/>
      <br/>
      <input type="submit" name="Submit" value="Update" />
      <input type="hidden" name="action" value="update" />
      <input type="hidden" name="page_options" value="wp_code_prettify_syntax_highlighter_theme, wp_code_prettify_syntax_highlighter_custom_styles" />
    </form>
  </div>
  <pre>
  <?php
}

/*
 * Load styles based on settings pages value
 * Get the selcted stylesheet's name by replacing spaces with dashes and making it lowercase.
 */
$selected_style_sheet = str_replace(" ","-",strtolower(get_option('wp_code_prettify_syntax_highlighter_theme')));
// Load the selected theme
wp_enqueue_style("wp-code-prettify-syntax-highlighter-theme", plugin_dir_url( __FILE__ )."/styles/$selected_style_sheet.css");
// Load the base style
wp_enqueue_style("wp-code-prettify-syntax-highlighter-theme-base", plugin_dir_url( __FILE__ )."/styles/base.css");

// Add the custom styles to the page header
function wp_code_prettify_syntax_highlighter_add_styles() {
  ?>
  <style type="text/css">
    <?php echo get_option('wp_code_prettify_syntax_highlighter_custom_styles') ?>
  </style>
  <?php
}
add_action('wp_head', 'wp_code_prettify_syntax_highlighter_add_styles');

?>
