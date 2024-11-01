<?php
/**
 * Module for UI.
 */
if (!defined('ABSPATH')) return;

class SERPPreviewUi {

	const DESCRIPTION_MAX_LENGTH = 160;

	public function add_post_metaboxes() {
		foreach (get_post_types(NULL, 'names') as $post_type) {
			add_meta_box('s', '<span class="dashicons dashicons-visibility"></span> SERP Preview', array($this, 'create_metabox'), $post_type, 'normal', 'high');
		}
	}

	public function create_metabox($post){
		?>

		<div class="serp-preview">
			<div class="serp-preview-title">
			<?php echo get_the_title($post->ID); ?>
			</div>
			<div class="serp-preview-address">
			<?php echo get_permalink($post->ID); ?>
			</div>
			<div class="serp-preview-description">
			<?php 
				$description = $this->get_meta_description($post); 
				if (empty($description)) {
					_e('No description set.', SERPPreview::TEXT_DOMAIN);
				}
				else {
					echo $this->limit_description_length($description);	
				}				
			?>
			</div>
		</div>
		
		<p class="serp-preview-info"><?php _e('<span class="dashicons dashicons-info"></span> Save your content to update SERP Preview.', SERPPreview::TEXT_DOMAIN); ?></p>

		<?php 
	}
	
	/**
	 * Sees if meta description is set by theme or plugin.
	 */
	private function get_meta_description($post) {
		$meta_description_fields = array(
			'_yoast_wpseo_metadesc', //Yoast SEO
			'_aioseop_description', // All in One SEO Pack
			'_su_description', // SEO Ultimate
			'meta_description', // SEO Title Tag
			'_seopressor_meta_description', // SEOpressor
			'description', // Platinum SEO
			'_msp_description', // Meta SEO Pack
			'advanced_seo_description', // Jetpack
			'_wds_metadesc', // Infinite SEO
			'_headspace_description', // Headspace2
			'_ghpseo_alternative_description', // Greg's High Performance SEO
			'_amt_description', // Add Meta Tags
			'_builder_seo_description', // Builder
			'_catalyst_description', // Catalyst
			'_description', // Frugal
			'_genesis_description', // Genesis
			'_description', // Headway
			'Description', // Hybrid
			'thesis_description', // Thesis 1.x
			'_thesis_meta_description', // Thesis 2.x
			'seo_description' // WooFramework			
		);
		
		foreach ($meta_description_fields as $field) {
			$meta_description = get_post_meta($post->ID, $field, TRUE);
			if (!empty($meta_description)) {
				return $meta_description;
			}
		}
		
		$meta_description = get_the_excerpt($post);
		
		if (empty($meta_description)) {
			return $post->post_content;
		}
		
		return $meta_description;
	}
	
	private function limit_description_length($description) {
		if (mb_strlen($description) > self::DESCRIPTION_MAX_LENGTH) {
			return mb_substr($description, 0, self::DESCRIPTION_MAX_LENGTH) . ' ...';
		}
		
		return $description;
	}
}