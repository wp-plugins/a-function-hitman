<?php
/*
  Plugin Name: Fuction Tweaker
  Plugin URI: http://www.thulasidas.com/adsense
  Description: A plugin to redefine plugin url functions using APD
  Version: 1.50
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
  $hitman = 'rename_function';
  if (function_exists($fn) && function_exists($hitman)) {
    $hitman($fn, $fn . '_old');
    $ret .= $fn . ' is KILLDED <br />';
  }
  else {
    return false;
  }
}

function whack_em($funs, $revive = false) {
  $hitman = 'rename_function';
  $ret = '';
  if (!function_exists($hitman)) {
    $ret .= $hitman . '  <b>Not found!!</b><br />You need the <code>apd</code> package for this plugin to work. Please install it by the command <code>pecl install apd</code>.';
    return $ret;
  }
  foreach ($funs as $fn) {
    if ($revive) {
      $oldName = $fn . '_old';
      $newName = $fn;
    }
    else {
      $oldName = $fn;
      $newName = $fn . '_old';
    }
    if (function_exists($oldName)) {
      @$hitman($oldName, $newName);
      $ret .= "$oldName is renamed to $newName <br />";
    }
    else {
      $ret .= $oldName . ' is not found <br />';
    }
  }
  return $ret;
}

function test_one($fn) {
  if (function_exists($fn)) {
    $fnout = $fn(__FILE__);
    $ret = "Output of <b><code>" . $fn . "()</code></b>: is <code>" . print_r($fnout, true) . "</code><br />";
  }
  else {
    $ret = $fn . ' is not found <br />';
  }
  return $ret;
}

function test_functions($funs) {
  $ret = '';
  foreach ($funs as $fn) {
    $ret .= test_one($fn);
    $fnOld = $fn . "_old";
    $ret .= test_one($fnOld);
  }
  return $ret;
}

function hitman_admin() {
  $mOptions = "hitman";
  $hmOptions = get_option($mOptions);
  $funs = array('plugin_dir_url', 'plugins_url', 'plugin_basename');
  $hmOptions['funList'] = $funs; // TODO: expose the list to the end-user
  if (isset($_POST['kill_functions'])) {
    $status = whack_em($funs);
    echo "<div class='updated'><p>Function Tweaker activated: $status</p></div>";
    $hmOptions['kill'] = true;
    update_option($mOptions, $hmOptions);
  }
  if (isset($_POST['save_functions'])) {
    $status = whack_em($funs, true);
    echo "<div class='updated'><p>Function Tweaker revoked: $status</p></div>";
    $hmOptions['kill'] = false;
    update_option($mOptions, $hmOptions);
  }
  if (isset($_POST['test_functions'])) {
    $status = test_functions($funs);
    echo "<div class='updated'><p>Function output:<br /> $status</p></div>";
  }
  if (isset($_POST['set_wp_content_dir'])) {
    $hmOptions['wpPluginDir'] = $_POST['wpContentDir'];
    echo "<div class='updated'><p>Function Tweaker will define WP_CONTENT_DIR as {$hmOptions['wpPluginDir']}</p></div>";
    update_option($mOptions, $hmOptions);
  }
  if (isset($_POST['unset_wp_content_dir'])) {
    $wpContentDir = $hmOptions['wpPluginDir'];
    echo "<div class='updated'><p>Function Tweaker will <em><strong>NOT</strong></em>define WP_CONTENT_DIR any more.</p><p>You may want to add this line to your <code>wp_config.php</code><br>
<code>define('WP_CONTENT_DIR', $wpContentDir);</code><p></div>";
    $hmOptions['wpPluginDir'] = '';
    update_option($mOptions, $hmOptions);
  }
  echo '<script type="text/javascript" src="' . get_option('siteurl') . '/' . PLUGINDIR . '/' . basename(dirname(__FILE__)) . '/wz_tooltip.js"></script>';
  ?>
  <div class="wrap" style="width:800px">
    <h2>Function Tweaker</h2>
    <hr />

    <form method="post" action="#">
      <?php
      $wpContentDir = dirname(dirname(dirname(__FILE__)));
      if (defined('WP_CONTENT_DIR')) {
        echo "<div class='updated'><p>WP_CONTENT_DIR is defined as <code>" . WP_CONTENT_DIR . "</code>";
        if ($wpContentDir != WP_CONTENT_DIR) {
          echo "<br>But it looks like it should be <code>$wpContentDir</code>";
          echo "<p>You may want to add this line to your <code>wp_config.php</code><br>
<code>define('WP_CONTENT_DIR', $wpContentDir);</code><p>";
        }
        if (!empty($hmOptions['wpPluginDir'])) {
          echo "I defined it. Want to undefine it? (You should probably define it in your <code>wp_config.php</code> instead.)&nbsp;";
          echo "<input type='submit' name='unset_wp_content_dir' value='Undefine it' />";
        }
        echo "</p></div>";
      }
      else {
        echo "<div class='updated'><p><b>WP_CONTENT_DIR is not defined.</b><br /> Defining it may solve all your problems regarding functions like <code>plugin_dir_url(), plugins_url()</code> and <code> plugin_basename()</code></div>";
        echo "Ok to define WP_CONTENT_DIR as <code>{$wpContentDir}</code>?&nbsp;";
        echo "<input type='submit' name='set_wp_content_dir' value='Define it' />";
        echo "<input type='hidden' name='wpContentDir' value='$wpContentDir' />";
      }
      ?>
      <h3>Help/Information</h3>
      <ol>
        <li>First hit the "Test Functions" button.</li>
        <li>Look at the output above. Especially the one against <code>plugins_url()</code></li>
        <li>If it looks incorrect, you may be able to fix it in two ways.</li>
        <ol>
          <li>If the plugin is offering to define WP_CONTENT_DIR, please do so.</li>
          <li>If the plugin is advising you to define it in your <code>wp_config.php</code>, please do so.</li>
        </ol>
        <li>Hit the "Test Functions" button again. Does the output look fine?</li>
        <li>If the function output still does not look right, click on the "Kill Functions" button and then hit the "Test Functions" button again.</li>
        <li>The output should have the right values now.</li>
        <li>If not, sorry, I am out of ideas. You may look at the plugin code in <code>redefinedFunctions*.php</code> and may be able to fix your issues.</li>
        <li> If you do, please leave a comment here so that others can benefit from your efforts.</li>
      </ol>
      <div class="submit">
        <input type="submit" name="test_functions" value="Test Functions" />
        &nbsp;
        <input type="submit" name="kill_functions" value="Kill Functions" />
        <input type="submit" name="save_functions" value="Revive Them" />
      </div>
    </form>
    <br />
    <hr />

  </div>
  <?php
  @include(dirname(__FILE__) . '/myPlugins.php');
  @include (dirname(__FILE__) . '/tail-text.php');
}

function hitman_ap() {
  if (function_exists('add_options_page')) {
    $mName = 'Function Tweaker';
    add_options_page($mName, $mName, 'activate_plugins', basename(__FILE__), 'hitman_admin');
  }
}

function this_plugin_first() {
  // May be necessary to load this plugin first.
  // See http://wordpress.org/support/topic/how-to-change-plugins-load-order.
  // Ensure path to this file is via main wp plugin path
  $wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR . "/$2", __FILE__);
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
$mOptions = "hitman";
$hmOptions = get_option($mOptions);
if (!empty($hmOptions)) {
  if (!empty($hmOptions['wpPluginDir'])) {
    define('WP_PLUGIN_DIR', $hmOptions['wpPluginDir']);
  }
  if ($hmOptions['kill'] && !empty($hmOptions['funList'])) {
    whack_em($hmOptions['funList']);
    $redefinedFunctions = 'redefinedFunctions1.php';
    @include($redefinedFunctions);
  }
}

