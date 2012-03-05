=== Function Tweaker ===
Contributors: manojtd
Donate link: http://buy.ads-ez.com/
Tags: functions, redefine, plugin_dir_url, plugins_url
Requires at least: 3.1
Tested up to: 3.3
Stable tag: 1.10

Redefines the functions plugin_dir_url, plugins_url to work properly if you have symbolic links in your WordPress installation.

== Description ==

Some of the built in functions (e.g., `plugin_dir_url` and `plugins_url`) may not work properly if you have non-standard WP installation with `wp-content` as a symbolic link. This plugin lets you redefine such functions without having to edit the WP core files.

This plugin is meant for advanced users or plugin developers. The reason for developing it was that some of my favorite plugins (term-management-tools, subscribe2, wp-dtree-30) didn't work properly when I had a non-standard WP installation. My installation consists of a blog assets folder containing all the stuff that I want to keep unchanged acrross multiple WP versions, and it contains `wp-content` as well. But unfortunately, the functions `plugin_dir_url` and `plugins_url` that many plugin authors make use of give unexpected results in this setup. If I modified the plugin code, it would be impossible to updrade the plugins automatically (without obliterating my modifications). If I modified the functions in WP core files to make the plugins work again, it would be impossible to painlessly upgrade my WP installation. Redefining the offending functions using a separate plugin was the right choice.

Note that this plugin uses a `pecl` package called `apd` which provides the `rename_function`. You could easily change the code to make it use `runkit`, but it didn't work for me. If all that is Greek to you, perhaps you shouldn't use this plugin.

Once again, this plugin is meant for advanced users and may make your blog installation unstable or even unusable. It gives a very niche, non-standard functionality that may be useful for some plugin or PHP developers.

== Screenshots ==
None for now.

== Installation ==

1. Upload the Function Tweaker plugin (the whole `a-function-hitman` folder) to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the Setup -> Function Tweaker and kill or revive the functions.

If you have other functions you would like to modify, please edit the plugin code `hitman.php` and look for the variable `$funs` around line 80. Add to the list the name of the function you want to redefine.

You the provide your own defintions of the function in the file `redefinedFunctions.php`.

== Frequently Asked Questions ==

== Change Log ==

* V1.10: Renaming the plugin to Function Tweaker since Hitman probably did not make any sense. [Mar 6. 2012]
* V1.00: Initial release. [Mar 5, 2012]
