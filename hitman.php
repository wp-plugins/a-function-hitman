<?php
/*
Plugin Name: Fuction Tweaker
Plugin URI: http://www.thulasidas.com/adsense
Description: A plugin to redefine plugin url functions using APD
Version: 1.10
Author: Manoj Thulasidas
Author URI: http://www.thulasidas.com
*/

/*
Copyright (C) 2008 www.thulasidas.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// TODO: Wrap hitman in a class to avoid potential name-collisions.

function whack($fn) {
  $hitman = 'rename_function' ;
  if (function_exists($fn) && function_exists($hitman)) {
    $hitman($fn, $fn.'_old') ;
    $ret .= $fn . ' is KILLDED <br />' ;
  }
  else return false ;
}
function whack_em($funs, $revive=false) {
  $hitman = 'rename_function' ;
  $ret = '' ;
  if (!function_exists($hitman)) {
    $ret .= $hitman .  '  <b>Not found!!</b><br />You need the <code>apd</code> package for this plugin to work. Please install it by the command <code>pecl install apd</code>.' ;
    return $ret ;
  }
  foreach ($funs as $fn) {
    if ($revive) {
      $oldName = $fn.'_old' ;
      $newName = $fn ;
    }
    else {
      $oldName = $fn ;
      $newName = $fn.'_old' ;
    }
    if (function_exists($oldName)) {
      @$hitman($oldName, $newName) ;
      $ret .= "$oldName is renamed to $newName <br />" ;
    }
    else $ret .= $oldName . ' is not found <br />' ;
  }
  return $ret ;
}
function test_one($fn) {
  if (function_exists($fn)) {
    $fnout = $fn(__FILE__) ;
    $ret .= "Output of <b><code>" . $fn . "()</code></b>: is <code>" . print_r($fnout, true) ."</code><br />";
  }
  else $ret .= $fn . ' is not found <br />' ;
  return $ret ;
}
function test_functions($funs) {
  foreach ($funs as $fn) {
    $ret .= test_one($fn) ;
    $fnOld = $fn . "_old" ;
    $ret .= test_one($fnOld) ;
  }
  return $ret ;
}
function hitman_admin() {
  $mOptions = "hitman" ;
  $hmOptions =  get_option($mOptions);
  $funs = array('plugin_dir_url', 'plugins_url', 'plugin_basename') ;
  $hmOptions['funList'] = $funs ; // TODO: expose the list to the end-user
  if (isset($_POST['kill_functions'])) {
    $status = whack_em($funs) ;
    echo "<div class='updated'><p>Function Tweaker activated: $status</p></div>" ;
    $hmOptions['kill'] = true ;
    update_option($mOptions, $hmOptions) ;
  }
  if (isset($_POST['save_functions'])) {
    $status = whack_em($funs, true) ;
    echo "<div class='updated'><p>Function Tweaker revoked: $status</p></div>" ;
    $hmOptions['kill'] = false ;
    update_option($mOptions, $hmOptions) ;
  }
  if (isset($_POST['test_functions'])) {
    $status = test_functions($funs) ;
    echo "<div class='updated'><p>Function output:<br /> $status</p></div>" ;
  }
?>
<div class="wrap" style="width:800px">
<h2>Function Tweaker
<a href="http://validator.w3.org/" target="_blank"><img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" title="Easy AdSense Admin Page is certified Valid XHTML 1.0 Transitional" height="31" width="88" class="alignright"/></a>
</h2>
<hr />

<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

<div class="submit">
<input type="submit" name="kill_functions" value="Kill Functions" />
<input type="submit" name="save_functions" value="Revive Them" />
&nbsp;
<input type="submit" name="test_functions" value="Test Functions" />
</div>
</form>
<br />
<hr />

</div>
<?php
}

function hitman_ap() {
  if (function_exists('add_options_page')) {
    $mName = 'Function Tweaker' ;
    add_options_page($mName, $mName, 9, basename(__FILE__), 'hitman_admin' );
  }
}
function this_plugin_first() {
  // May be necessary to load this plugin first.
  // See http://wordpress.org/support/topic/how-to-change-plugins-load-order.
  // Ensure path to this file is via main wp plugin path
  $wp_path_to_this_file =
    preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
  $this_plugin = plugin_basename(trim($wp_path_to_this_file));
  $active_plugins = get_option('active_plugins');
  $this_plugin_key = array_search($this_plugin, $active_plugins);
  if ($this_plugin_key) { // If 0, alread the first plugin. No need to continue.
    array_splice($active_plugins, $this_plugin_key, 1);
    array_unshift($active_plugins, $this_plugin);
    update_option('active_plugins', $active_plugins);
  }
}
add_action("activated_plugin", "this_plugin_first");
add_action('admin_menu', 'hitman_ap');
$mOptions = "hitman" ;
$hmOptions =  get_option($mOptions);
if (!empty($hmOptions) && $hmOptions['kill'] && !empty($hmOptions['funList'])) {
  whack_em($hmOptions['funList']) ;
  $redefinedFunctions = 'redefinedFunctions1.php' ;
  @include($redefinedFunctions) ;
}
?>
