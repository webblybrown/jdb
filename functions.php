<?php
define('PUBLIC_FOLDER', get_template_directory_uri());

if(!function_exists('theme_setup')) {
  function theme_setup() {
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
      'search-form',
      'gallery',
      'caption',
    ]);

    register_nav_menus([
      'main_menu' => 'Main Menu'
    ]);
  }
}
add_action('after_setup_theme', 'theme_setup');

if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
function my_jquery_enqueue() {
   wp_deregister_script('jquery');
   wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js", false, null);
   wp_enqueue_script('jquery');
}

function theme_scripts() {
  $template_uri = PUBLIC_FOLDER;
  $stylesheet_uri = get_stylesheet_uri();
  wp_enqueue_script('owl-js', $template_uri . '/scripts/owl.carousel.min.js', array('jquery'), '2.2.1', true );
  wp_enqueue_script('owl-js');
  wp_enqueue_script('animate-js', $template_uri . '/scripts/css3-animate-it.js', array('jquery'), '0.1.0', true );
  wp_enqueue_script('animate-js');
  wp_register_script('scripts', $template_uri . '/js/scripts.js', ['jquery'], '', true);
  wp_enqueue_script('scripts');
  wp_register_style('theme-style', $stylesheet_uri);
  wp_enqueue_style('theme-style');
}
add_action('wp_enqueue_scripts', 'theme_scripts');

function async_script_load($tag, $handle) {
  if(is_admin()) return $tag;

  $excluded_scripts = [
    'jquery-core'
  ];

  if(!is_admin()) {
    if(in_array($handle, $excluded_scripts)) {
      return $tag;
    } else {
      return str_replace(' src', ' defer src', $tag);
    }
  }
}
add_filter('script_loader_tag', 'async_script_load', 10, 2);

function include_additional_files() {
  $template_url = get_template_directory();

  require_once $template_url . '/includes/acf-options.php';
}
add_action('init', 'include_additional_files', 1);

// Register Custom Navigation Walker
require_once('wp-bootstrap-navwalker.php');

register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'THEMENAME' ),
) );
