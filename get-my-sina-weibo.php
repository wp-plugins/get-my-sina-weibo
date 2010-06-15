<?php
/*
Plugin Name: GetMySinaWeibo
Plugin URI: http://code.google.com/p/get-my-sina-weibo/
Description: Simple plugin to return a user defined number of tweets from Sina Weibo.
This plugin is based on getMyTweets plugin and weibo-basic-auth-class weibo.class.php.
Version: 0.1
Author: Gyan Liu
Author URI: http://www.hapblue.com
Copyright 2010  Gyan Liu  (email : liuzhgyan@sina.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Version Changes:

0.1.1 - Initial version, no OAuth, need to input password and specify screen name.
*/

require('weibo.class.php');

define( "WB_AKEY" , '3729072186' );
define( "WB_SKEY" , '38f2e21d89c67028b9b08fd19a21307e' );

function get_my_tweets_menu() {
  add_options_page( 'Get My Sina Weibo', 'Get My Sina Weibo', 8, __FILE__, 'get_my_tweets_options' );
}

function get_my_tweets() {
	$twit_usr   = get_option( 'weibo_user_name'   );
	$twit_pass  = get_option( 'weibo_password'    );
	$twit_name  = get_option( 'weibo_screen_name' );
	$num_tweets = get_option( 'num_tweets_retrieve' );

	$w = new weibo( WB_AKEY, WB_SKEY );
	$w->setUser( $twit_usr , $twit_pass );
	$post = $w->user_timeline($twit_name);
	$result = '<ul>';

    for ($i = 0; $i < $num_tweets; $i++)
    {
    	$text = $post[$i][text];
		$pattern = "/http:\/\/(.*?)\/(\w+)/";
		$replacement = "<a href=\"$0\" target=\"_blank\">$0</a>";
		$finalString = preg_replace( $pattern, $replacement, $text );
		$result .= '<li>'.$finalString . ' ';

		if ( $post[$i][retweeted_status] )
		{
			$via_name = $post[$i][retweeted_status][user][name];
			$via_url = 'http://t.sina.com.cn/' . $post[$i][retweeted_status][user][id];
			$via = "<a href = " . $via_url . " target = '_blank'>@" . $via_name . "</a>";
			$via_text = $post[$i][retweeted_status][text];
			$pattern = "/http:\/\/(.*?)\/(\w+)/";
			$replacement = "<a href=\"$0\" target=\"_blank\">$0</a>";
			$via_finalstring = preg_replace( $pattern, $replacement, $via_text);
			$via .= ' '. $via_finalstring . ' ';
			$result .= $via;
		}

		$created_at = $post[$i]['created_at'];
		$tDate = date_parse($created_at);
		if($tDate['minute'] < 10)
		{
			$tDate['minute'] = '0'.$tDate['minute'];
		}
		$tweetdate = $tDate['month'].'/'.$tDate['day'].'/'.$tDate['year'].' '.$tDate['hour'].':'.$tDate['minute'];
		$result .= $tweetdate . '</li>';
    }
    $result .= '</ul>';
	return $result;
}//end function

function get_my_tweets_options() {
	?>
	<div class="wrap">
	<h2>Get My Sina Weibo</h2>
	<form method="post" action="options.php">
	<table class="form-table">

	<tr valign="top">
	<th scope="row">Sina Weibo User Name</th>
	<td><input type="text" name="weibo_user_name" value="<?php echo get_option( 'weibo_user_name' ); ?>" /></td>
	</tr>

	<tr valign="top">
	<th scope="row">Sina Weibo Password</th>
	<td><input type="password" name="weibo_password" value="<?php echo get_option( 'weibo_password' ); ?>"/></td>
	</tr>

	<tr valign="top">
	<th scope="row">Number Of Tweets To Retrieve</th>
	<td><input type="text" name="num_tweets_retrieve" value="<?php echo get_option( 'num_tweets_retrieve' ); ?>" /></td>
	</tr>

	<tr valign="top">
	<th scope="row">Sina Weibo Screen Name</th>
	<td><input type="text" name="weibo_screen_name" value="<?php echo get_option( 'weibo_screen_name' ); ?>" /></td>
	</tr>

	</table>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="weibo_user_name,weibo_password,num_tweets_retrieve,weibo_screen_name" />
	<?php wp_nonce_field( 'update-options' ); ?>
	<p class="submit">
	<input type="submit" name="Submit" value="<?php _e( 'Save Changes' ) ?>" />
	</p>
	</form>
	</div>
	<?php
	}//end get_my_tweets_options

	function get_my_tweets_widget_init() {

		function widget_get_my_tweets( $args )
		{
		    extract( $args );
		    echo $before_widget;
		    echo $before_title . 'Recent Sina Weibo' . $after_title;
		    echo get_my_tweets();
		    echo $after_widget;
		}
		if ( !function_exists('register_sidebar_widget') ) return;
			register_sidebar_widget( array( 'Get My Sina Weibo', 'widgets' ), 'widget_get_my_tweets' );
	}
add_action ( 'admin_menu', 'get_my_tweets_menu');
add_action( 'plugins_loaded',  'get_my_tweets_widget_init' );
?>