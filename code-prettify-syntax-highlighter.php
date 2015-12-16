<?php
/*
Plugin Name: Code Prettify Syntax Highlighter
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
add_action( 'wp_enqueue_scripts', 'code_prettify_syntax_highlighter_scripts' );
function code_prettify_syntax_highlighter_scripts() {
  wp_enqueue_script('code-prettify-syntax-highlighter', 'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js', null, "0.1", false);
}

/*
 * The filter to handle processing of the prettyprint pre tags
 */
add_filter( 'the_content', 'code_prettify_syntax_highlighter_clean_the_body');
function code_prettify_syntax_highlighter_clean_the_body($content) {
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
add_action('admin_menu', 'code_prettify_syntax_highlighter_settings');
function code_prettify_syntax_highlighter_settings() {
  add_menu_page('Code Prettify', 'Code Prettify', 'administrator', 'code_prettify_syntax_highlighter_settings', 'code_prettify_syntax_highlighter_display_settings');
}

function code_prettify_syntax_highlighter_display_settings() {
  $options = array("Default","Desert", "Sunburst","Doxy", "Monokai");
  $options_dom = "";
  foreach($options as $option) {
    if(get_option('code_prettify_syntax_highlighter_theme') == $option) { 
      $options_dom = $options_dom . "<option value='$option' selected>$option</option>";
    } else {
      $options_dom = $options_dom . "<option value='$option'>$option</option>";
    }
  }

  ?>
  </pre>
  <div class="wrap">
    <form action="options.php" method="post" name="options">
      <h2>Code Prettify Syntax Highlighter Options</h2>
      <?php echo wp_nonce_field('update-options') ?>

      <label>Choose a built-in theme</label>
      <select name="code_prettify_syntax_highlighter_theme">
        <?php echo $options_dom ?>
      </select>
      <br />
      <br />
      <div>
        <label>Add custom css:&nbsp;</label>
        <br/>
        <br/>
        <textarea style="width: 50%; min-width: 200px; height: 150px;" name="code_prettify_syntax_highlighter_custom_styles"><?php echo get_option('code_prettify_syntax_highlighter_custom_styles') ?></textarea>
      </div>
      <br/>
      <br/>
      <input type="submit" name="Submit" value="Update" />
      <input type="hidden" name="action" value="update" />
      <input type="hidden" name="page_options" value="code_prettify_syntax_highlighter_theme, code_prettify_syntax_highlighter_custom_styles" />
    </form>
  </div>
  <pre>
  <?php
}

/*
 *Load styles based on settings pages value
 */
switch(get_option('code_prettify_syntax_highlighter_theme')) {
  case 'Desert':
    wp_enqueue_style("code-prettify-syntax-highlighter-theme", plugin_dir_url( __FILE__ )."/styles/desert.css");
    break;
  case 'Sunburst':
    wp_enqueue_style("code-prettify-syntax-highlighter-theme", plugin_dir_url( __FILE__ )."/styles/sunburst.css");
    break;
  case 'Doxy':
    wp_enqueue_style("code-prettify-syntax-highlighter-theme", plugin_dir_url( __FILE__ )."/styles/doxy.css");
    break;
  case 'Monokai':
    wp_enqueue_style("code-prettify-syntax-highlighter-theme", plugin_dir_url( __FILE__ )."/styles/monokai.css");
    break;
  default:
    wp_enqueue_style("code-prettify-syntax-highlighter-theme", plugin_dir_url( __FILE__ )."/styles/default.css");
}
wp_enqueue_style("code-prettify-syntax-highlighter-theme-base", plugin_dir_url( __FILE__ )."/styles/base.css");

/*
 * Custom styles
 */
function code_prettify_syntax_highlighter_add_styles()
{
  ?>
  <style type="text/css">
    <?php echo get_option('code_prettify_syntax_highlighter_custom_styles') ?>
  </style>
  <?php
}
add_action('wp_head', 'code_prettify_syntax_highlighter_add_styles');

?>
