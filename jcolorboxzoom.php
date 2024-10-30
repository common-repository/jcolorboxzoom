<?php
/*
Plugin Name: JColorboxZoom
Plugin URI: http://wordpress.org/extend/plugins/jcolorboxzoom/
Description: Add a checkbox to media window for showing large image with jQuery Colorbox javascript
Author: Andrea Bersi
Version: 1.0
Author URI: http://www.andreabersi.com/
*/


// constants for adding JColorboxZoom to ADD MEDIA WINDOW
define ('HI_TEXTDOMAIN', 'JColorboxZoom');
define ('HI_FOLDERNAME', 'JColorboxZoom');
define ('WIDTH_MAX','85%');
define ('HEIGHT_MAX','85%');
// load translation
load_plugin_textdomain(HI_TEXTDOMAIN, PLUGINDIR . '/' . HI_FOLDERNAME);

// add highslide option to media dialog
function JColorboxZoom_attachment_fields_to_edit( $form_fields, $post ) {
   if(get_option('jcolorbox_enabledForDefault') == 1)
      $checkedHighslide = 'checked="checked" ';
   	$my_form_fields = array(
      'highslide' => array(
         'label'     => __('Zoom automatico', HI_TEXTDOMAIN),
         'input'     => 'html',
         'html'      => "
            <input type='checkbox' name='jcolorbox-{$post->ID}' id='jcolorbox-{$post->ID}' value='1' $checkedHighslide/>
            <label for='jcolorbox-{$post->ID}'>" . __('attiva', HI_TEXTDOMAIN) . "</label>" )
    );
    if( $post->post_mime_type == 'image/jpeg' OR  $post->post_mime_type == 'image/gif' OR $post->post_mime_type == 'image/png' OR $post->post_mime_type == 'image/tiff')
      return array_merge( $form_fields, $my_form_fields );
    else
      return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'JColorboxZoom_attachment_fields_to_edit', 66, 2 );

// filter and modify html code send to editor

function JColorboxZoom_send_to_editor( $html, $send_id, $attachment ) {
   if( isset($_POST["jcolorbox-$send_id"]) )
      return str_replace('<a', '<a class="cboxElement" title="'.get_bloginfo('description').'"', $html);
   else
      return $html;
}

add_filter( 'media_send_to_editor', 'JColorboxZoom_send_to_editor', 66, 3 );

// activating the plugin

function JColorboxZoom_activate() {

   // save plugin options to database
   add_option('jcolorbox_enabledForDefault', 1);
}

register_activation_hook( __FILE__, 'JColorboxZoom_activate' );

function JColorboxZoom_wp_head() {
?>
	<script type='text/javascript' src='<?php echo WP_PLUGIN_URL?>/JColorboxZoom/js/jquery.colorbox-min.js'></script>
	<script type='text/javascript' src='<?php echo WP_PLUGIN_URL?>/JColorboxZoom/js/jquery-colorbox-auto-min.js'></script>
	<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL?>/JColorboxZoom/themes/colorbox.css"  />
<script>
   // <![CDATA[
		jQuery(document).ready(function(){
			//assign the ColorBox event to elements
			jQuery("a[class='cboxElement']").colorbox({maxWidth:"<?echo WIDTH_MAX;?>", maxHeight:"<?echo HEIGHT_MAX;?>"});
		});
	// ]]>
	</script>
<?php
}// end function
add_action('wp_head', 'JColorboxZoom_wp_head');