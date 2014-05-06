<?php
/**
*
* Facebook feed shortcode
*
* Add shortcode functionality to include the feed of a Facebook page on your website
*
*
* @param array $atts Array of shortcode attributes
* @return string
*
*/
function get_facebook_updates( $atts ) {

	extract( shortcode_atts( array( 
		'facebook_access_token' => '',
		'facebook_page_username' => '',
		'post_count' => ''
	), $atts ) );

	// Extract the page ID from the Facebook Page 
	$facebook_page_data = file_get_contents( 'https://graph.facebook.com/' . $facebook_page_username );
	$facebook_page_data = json_decode( $facebook_page_data );
	$facebook_page_id = $facebook_page_data->id;

	// Get the contents of the Facebook page
	$facebook_page_content = file_get_contents( 'https://graph.facebook.com/' . $facebook_page_id . '/feed?access_token=' . $facebook_access_token );

	// Interpret data with JSON
	$facebook_data = json_decode($facebook_page_content);
	$html = '<h2 class="facebook-feed-title">Facebook Feed</h2>';
	$html .= '<ul class="facebook-feed">';

	// Loop through data for each feed item
	$i = 1;
	foreach ($facebook_data->data as $news ) {
		if($i < $post_count+1) { 
			if (!empty($news->message)) { 
				$content = substr($news->message, 0, 160); 
				$html .= '<li>'. $content . '...<br />';
				$html .= '<a href="http://www.facebook.com/' . $facebook_page_username . '">View the full entry</a></li>';
			}
		}
		$i++;
	}
	$html .= '</ul>';
	$html .= '<p><span class="facebook-feed-button"><a href="http://www.facebook.com/' . $facebook_page_username . '">Connect on Facebook</a></span></p>';
	return $html;
}

add_shortcode('facebook_feed', 'get_facebook_updates');

?>