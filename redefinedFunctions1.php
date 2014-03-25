<?php
if (!function_exists('plugin_dir_url')) {
  function plugin_dir_url($file = __FILE__) {
    $ret = plugin_dir_url_old($file) ;
    return str_replace(WP_CONTENT_DIR, '', $ret) ;
  }
}
if (!function_exists('plugins_url')) {
  function plugins_url($path = '' , $plugin = '') {
    $ret = plugins_url_old($path, $plugin) ;
    return str_replace(WP_CONTENT_DIR, '', $ret) ;
  }
}
if (!function_exists('plugin_basename')) {
  function plugin_basename($file = __FILE__) {
    $ret = plugin_basename_old($file) ;
    return str_replace(WP_CONTENT_DIR, '', $ret) ;
  }
}
