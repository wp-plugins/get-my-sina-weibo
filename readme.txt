=== Get My Sina Weibo ===
Contributors: Gyan Liu
Donate link: http://code.google.com/p/get-my-sina-weibo/
Tags: sina weibo, tweets, curl, wordpress, plugin
Requires at least: 2.7
Tested up to: 2.7
Stable tag: 0.1

Simple plugin to return a user defined number of tweets from Sina Weibo and parse any URLs in the tweet.
This plugin is based on getMyTweets plugin and weibo-basic-auth-class weibo.class.php.

== Description ==

Using PHP 5, this plugin will load a user definable number of Tweets from Sina Weibo.  It requires that the Sina Weibo user name & password & user screen name be supplied to the plugin, and the desired number of Tweets to retrieve. The plugin will parse links within Tweets.

== Installation ==

1. Upload `getMySinaWeibo.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. You can add the widget through the 'Widgets' menu under 'Appearance'
1. If you are not using themes, you can add `<?php get_my_tweets();?>` to your theme's files where you want your tweets to show up.

== Frequently Asked Questions ==

= Is there a specific PHP version? =

Yes, Get My Tweets requires PHP version 5 or higher with the curl extension loaded

