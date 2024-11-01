<?php
/**
 * Plugin Name: Tailored Lightbox
 * Description: Add a lightbox script (using <a href="https://fancyapps.com/fancybox/3/" target="_blank">Fancybox</ * a>).
 * Version:     3.5.7.2
 * Author:      Tailored Media
 * Author URI:  https://www.tailoredmedia.com.au
 *
 * @package TailoredLightbox
 */

/**
 * Load Plugin CLass
 */
new Tailored_Lightbox();

/**
 * Plugin class
 */
class Tailored_Lightbox {
	/**
	 * Fancybox Version
	 *
	 * @var string
	 */
	public $version = '3.5.7';


	/**
	 *  Constructor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 *  Enqueue script
	 */
	public function enqueue_scripts() {
		$script_url = plugins_url( 'fancybox-' . $this->version . '/', __FILE__ );

		wp_enqueue_script( 'fancybox', $script_url . 'jquery.fancybox.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_style( 'fancybox', $script_url . 'jquery.fancybox.min.css', array(), $this->version, 'screen' );

		$inline = $this->get_inline_script();
		$inline = str_replace( array( '<script>', '</script>' ), '', $inline ); // tags really only there for source formatting in my code.
		wp_add_inline_script( 'fancybox', $inline );
	}

	/**
	 *  Inline script to prepare the loader
	 */
	public function get_inline_script() {
		ob_start();
		?>
<script>
// Loader added for Fancybox
jQuery(document).ready(function($) {
	// Apply to links to images.
	$('a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".gif"]').attr('rel','fancybox');
	// Captions.
	$('a[rel="fancybox"]').each(function(i) {
		var caption = false;
		caption_text = $(this).closest('.gallery-item').find('.wp-caption-text').text();
		if (!caption && caption_text) caption = caption_text;
		if (!caption)	caption = $(this).attr('title');
		if (!caption)	caption = $(this).children('img:first').attr('title');
		if (!caption)	caption = $(this).children('img:first').attr('alt');
		if (caption)	$(this).attr('data-caption', caption);
	});
	// Group them so you can look prev/next.
	$('a[rel="fancybox"]').attr('data-fancybox', 'gallery-all');
	$("[data-fancybox]").fancybox({ loop: true });
});
</script>
		<?php
		return ob_get_clean();
	}


}

