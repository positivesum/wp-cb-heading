<?php

if (!class_exists('cfct_module_heading')) {
	class cfct_module_oheading extends cfct_build_module {
		
		/**
		 * Set up the module
		 */
		public function __construct() {
			$this->pluginDir		= basename(dirname(__FILE__));
			$this->pluginPath		= WP_PLUGIN_DIR . '/' . $this->pluginDir;
			$this->pluginUrl 		= WP_PLUGIN_URL.'/'.$this->pluginDir;	
			$opts = array(
				'description' => __('Add headings into the page layout wherever needed with more options.', 'carrington-build'),
				'icon' => $this->pluginUrl.'/icon.png'
			);
			
			// use if this module is to have no user configurable options
			// Will suppress the module edit button in the admin module display
			# $this->editable = false 
			
			parent::__construct('cfct-oheading', __('New Heading', 'carrington-build'), $opts);
		}

		/**
		 * Display the module content in the Post-Content
		 * 
		 * @param array $data - saved module data
		 * @return array string HTML
		 */
		public function display($data) {
			$h_tag = $this->h_tag($data);
			$title = esc_html($data[$this->get_field_id('content')]);
			$class = esc_html($data['h_tag_class']);
			echo '<'. $h_tag.' class="cfct-mod-title '. $class.'">'. $title.'</'. $h_tag.'>';
			
		}

		/**
		 * Build the admin form
		 * 
		 * @param array $data - saved module data
		 * @return string HTML
		 */
		public function admin_form($data) {
			$tags = $this->h_tags();
			$output = '
<div>
	<label for="'.$this->get_field_id('content').'">'.__('Heading Text', 'carrington-build').'</label>
	<input type="text" name="'.$this->get_field_name('content').'" id="'.$this->get_field_id('content').'" value="'.(!empty($data[$this->get_field_id('content')]) ? esc_attr($data[$this->get_field_id('content')]) : '').'" />
</div>
<p><a href="#cfct-header-adv-options" id="cfct-header-adv-options-toggle">'.__('Advanced Options', 'carrington-build').'</a></p>
<div id="cfct-header-adv-options" class="cfct-post-layout-controls hidden">
	<p>
		<label for="'.$this->get_field_id('h_tag').'">'.__('HTML Tag', 'carrington-build').'</label>
		<select name="'.$this->get_field_id('h_tag').'" id="'.$this->get_field_id('h_tag').'">
			';
			foreach ($tags as $tag => $display) {
				$output .= '
			<option value="'.esc_attr($tag).'" '.selected($tag, $this->h_tag($data), false).'>'.esc_html($display).'</option>
				';
			}
			$output .= '
		</select> <br/>
		<label for="h_tag_class">'.__('Select Class', 'carrington-build').'</label>
		<select name="h_tag_class" id="h_tag_class">
		<option value="red" '.selected('red', $data['h_tag_class'], false).'>Red</option>
		<option value="gray" '.selected('gray', $data['h_tag_class'], false).' >Gray</option>
		</select>
	</p>
</div>
			'; //selected($tag, $this->h_tag($data), false)
			return $output;
		}
		
		/**
		 * Default value of h2 if no tag has been selected
		 *
		 * @param array $data - saved module data
		 * @return string text
		 */
		private function h_tag($data = array()) {
			return (!empty($data[$this->get_field_id('h_tag')]) ? $data[$this->get_field_id('h_tag')] : 'h2');
		}
		
		/**
		 * Filter and return list of available tags
		 *
		 * @return array
		 */
		private function h_tags() {
			$tags = array(
				'h1' => '&lt;H1&gt;',
				'h2' => '&lt;H2&gt;',
				'h3' => '&lt;H3&gt;',
				'h4' => '&lt;H4&gt;',
				'h5' => '&lt;H5&gt;',
				'h6' => '&lt;H6&gt;',
			);
			return apply_filters('cfct_module_heading_h_tags', $tags);
		}

		/**
		 * Return a textual representation of this module.
		 *
		 * @param array $data - saved module data
		 * @return string text
		 */
		public function text($data) {
			return strip_tags($data[$this->get_field_id('content')]);
		}

		/**
		 * Modify the data before it is saved, or not
		 *
		 * @param array $new_data 
		 * @param array $old_data 
		 * @return array
		 */
		public function update($new_data, $old_data) {
			if (isset($new_data[$this->get_field_id('h_tag')]) && !in_array($new_data[$this->get_field_id('h_tag')], array_keys($this->h_tags()))) {
				$new_data[$this->get_field_id('h_tag')] = $this->h_tag();
			}
			return $new_data;
		}
		
		/**
		 * Add custom javascript to the post/page admin
		 * OPTIONAL: omit this method if you're not using it
		 *
		 * @return string JavaScript
		 */
		public function admin_js() {
			return '
// perform an action when the module admin screen loads
cfct_builder.addModuleLoadCallback("'.$this->id_base.'", function(form) {
	jQuery("#cfct-header-adv-options-toggle").click(function() {
		jQuery(jQuery(this).attr("href")).slideToggle("fast");
		return false;
	});
});
			';
		}
		
		/**
		 * Add custom css to the post/page admin
		 * OPTIONAL: omit this method if you're not using it
		 *
		 * @return string CSS
		 */
		public function admin_css() {
			return '';
		}
	}
	// register the module with Carrington Build
	cfct_build_register_module('cfct-oheading', 'cfct_module_oheading');
}
	