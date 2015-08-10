=== Function Tweaker ===
Contributors: manojtd
Donate link: http://buy.thulasidas.com
Tags: developer tool, functions, redefine, plugin_dir_url, plugins_url, plugin_basename
Requires at least: 3.1
Tested up to: 4.3
Stable tag: 2.20
License: GPLv2 or later

Developer Tool: Redefines the functions plugin_dir_url, plugins_url to work properly if you have symbolic links in your WordPress installation.

== Description ==

Some of the built in functions (e.g., `plugin_dir_url` and `plugins_url`) may not work properly if you have non-standard WP installation with `wp-content` as a symbolic link. This plugin lets you redefine such functions without having to edit the WP core files.

This plugin is meant for advanced users or plugin developers. The reason for developing it was that some of my favorite plugins (term-management-tools, subscribe2, wp-dtree-30) didn't work properly when I had a non-standard WP installation. My installation consists of a blog assets folder containing all the stuff that I want to keep unchanged acrross multiple WP versions, and it contains `wp-content` as well. But unfortunately, the functions `plugin_dir_url` and `plugins_url` that many plugin authors make use of give unexpected results in this setup. If I modified the plugin code, it would be impossible to updrade the plugins automatically (without obliterating my modifications). If I modified the functions in WP core files to make the plugins work again, it would be impossible to painlessly upgrade my WP installation. Redefining the offending functions using a separate plugin was the right choice.

Note that this plugin uses a `pecl` package called `apd` which provides the `rename_function`. You could easily change the code to make it use `runkit`, but it didn't work for me. If all that is Greek to you, perhaps you shouldn't use this plugin.

Once again, this plugin is meant for advanced users and may make your blog installation unstable or even unusable. It gives a very niche, non-standard functionality that may be useful for some plugin or PHP developers.

== Upgrade Notice ==

Compatibility with WP4.3.

== Screenshots ==

1. Admin page for Function Tweaker

== Installation ==

= Searching =

The easiest way to install this plugin is to use the WordPress Admin interface. Go to your admin dashboard, find the "Plugins" menu, and click on "Add New". Search for this plugin and click on "Install Now" and follow the WordPress instructions.

= Uploading =

If you want to download it and manually install, you can again use the WordPress dashboard interface. First download the plugin zip file to your local computer. Then go to your admin dashboard, find the "Plugins" menu, and click on "Add New". After clicking on the "Add New" menu item as above, click on "Upload" (below the title "Install Plugins" near the top). Browse for your downloaded zip file, upload it and activate the plugin.

= FTP =

1. Upload the Function Tweaker plugin (the whole `a-function-hitman` folder) to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the Setup -> Function Tweaker and kill or revive the functions.

If you have other functions you would like to modify, please edit the plugin code `hitman.php` and look for the variable `$funs` around line 82. Add to the list the name of the function you want to redefine.

You the provide your own defintions of the function in the file `redefinedFunctions.php`.

== Frequently Asked Questions ==

= What does this plugin do? =

Function Tweaker helps you troubleshoot and redefine the functions `plugin_dir_url`, `plugins_url` and `plugin_basename` to work properly. These functions may not work as expected if you have symbolic links in your WordPress installation.

Here is an example of the wrong output:

 - Output of `plugin_dir_url()`: `http://localhost/dev/blog/wp-content/plugins/Applications/XAMPP/xamppfiles/htdocs/dev/blog-assets/wp-content/plugins/hitman/`
 - Output of `plugins_url()`: http://localhost/dev/blog/wp-content/plugins/Applications/XAMPP/xamppfiles/htdocs/dev/blog-assets/wp-content/plugins/hitman/hitman.php
 - Output of `plugin_basename()`: `Applications/XAMPP/xamppfiles/htdocs/dev/blog-assets/wp-content/plugins/hitman/hitman.php`

After correcting the issue, the right output is:

 - Output of `plugin_dir_url()`: `http://localhost/dev/blog/wp-content/plugins/hitman/`
 - Output of `plugins_url()`: `http://localhost/dev/blog/wp-content/plugins/hitman/hitman.php`
 - Output of `plugin_basename()`: `hitman/hitman.php`

= How do I use it? =

Go to the Function Tweaker admin page. You will see the following help text message:

1. First hit the "Test Functions" button.
2. Look at the output above. Especially the one against plugins_url()
3. If it looks incorrect, you may be able to fix it in two ways.
 - If the plugin is offering to define `WP_CONTENT_DIR`, please do so.
 - If the plugin is advising you to define it in your wp_config.php, please do so.
4. Hit the "Test Functions" button again.  Does the output look fine?
5. If the function output still does not look right, click on the "Kill Functions" button and then hit the "Test Functions" button again.
6. The output should have the right values now.
7. If not, sorry, I am out of ideas. You may look at the plugin code in redefinedFunctions*.php and may be able to fix your issues.
8. If you do, please leave a comment here so that others can benefit from your efforts.

= How does it do it? =

In most cases, defining your `WP_CONTENT_DIR` properly is good enough to solve the issue. The "Kill Functions" button basically removes the current definitions of the functions and replaces them with what is in the `redefinedFunctions1.php`. For it to work, your PHP installation will need to have a function called `rename_function`, which is part of the APD package. If this package is not found, the plugin will gracefully exit.

== Change Log ==

* V2.20: Compatibility with WP4.3. [Aug 10, 2015]
* V2.10: Compatibility with WP4.0. [Sep 8, 2014]
* V2.00: Removing tail-text for compatibility with other plugins. [Mar 24, 2014]
* V1.51: Minor admin page changes. [Dec 21, 2013]
* V1.50: Compatibility checks for WordPress V3.8. [Dec 20, 2013]
* V1.40: Compatibility checks for WordPress V3.7. [Nov 11, 2013]
* V1.30: Admin interface modifications. [Sep 30, 2012]
* V1.21: Minor documentation change. [Sep 9, 2012]
* V1.20: Quite a bit of improvements and documentation changes. [Sep 9, 2012]
* V1.10: Renaming the plugin to Function Tweaker since Hitman probably did not make any sense. [Mar 6. 2012]
* V1.00: Initial release. [Mar 5, 2012]
