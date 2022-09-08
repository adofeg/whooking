<?php

defined ('ABSPATH') or exit;

if ( !class_exists('Puc_v4p9_Update', false) ):

	abstract class Puc_v4p9_Update extends Puc_v4p9_Metadata {
		public $slug;
		public $version;
		public $download_url;
		public $translations = array();
		
		protected function getFieldNames() {
			return array('slug', 'version', 'download_url', 'translations');
		}

		public function toWpFormat() {
			$update = new stdClass();

			$update->slug = $this->slug;
			$update->new_version = $this->version;
			$update->package = $this->download_url;

			return $update;
		}
	}

endif;
