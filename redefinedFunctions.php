<?php
if (!function_exists('plugin_dir_url')) {
  function plugin_dir_url($file = __FILE__) {
    $plugin_dir_url = get_option('siteurl') . '/' . PLUGINDIR . '/' .
      basename(dirname($file)) . '/';
    return $plugin_dir_url ;
  }
}
if (!function_exists('plugins_url')) {
  function plugins_url($path = '' , $plugin = '') {
    $plugin_base = get_option('siteurl') . '/' . PLUGINDIR ;
    if (empty($path) && empty($plugin)) return $plugin_base ;
    if (empty($plugin)) return $plugin_base . '/' . $path ;
    else return $plugin_base . '/' . $plugin . '/' . basename($path) ;
  }
}
if (!function_exists('plugin_basename')) {
  function plugin_basename($file = __FILE__) {
    $pluginPath = realpath(plugin_dir_path(dirname($file))) ;
    $filePath = realpath($file) ;
    $diffPos = strspn($pluginPath ^ $filePath, "\0"); // XOR and look for \0
    $baseName = substr($file, $diffPos+1) ;
    return $baseName ;
  }
}
