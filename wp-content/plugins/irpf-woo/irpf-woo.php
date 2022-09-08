<?php

/**
 *
 * Plugin Name: 			Retención del IRPF en WooCommerce
 * Description: 			Aplica la retención del IRPF en el checkout de WooCommerce en función de si el cliente es particular o profesional/empresa
 * Plugin URI: 				https://www.enriquejros.com/plugins/retencion-irpf-woocommerce/
 * Author: 					Enrique J. Ros
 * Author URI: 				https://www.enriquejros.com/
 * Version: 				1.4.2
 * License: 				Copyright 2018 - 2021 Enrique J. Ros (email: enrique@enriquejros.com)
 * Text Domain: 			woo-irpf
 * Domain Path: 			/lang/
 * Requires at least:		5.0
 * Tested up to:			5.7
 * Requires PHP: 			7.0
 * WC requires at least:	3.0
 * WC tested up to: 		5.0
 *
 * @author 			Enrique J. Ros
 * @link			https://www.enriquejros.com
 * @since			1.0.0
 * @package			WooIRPF
 *
 */

defined ('ABSPATH') or exit;

if (!class_exists ('Plugin_Woo_IRPF')) :

	Class Plugin_Woo_IRPF {

		private static $instancia;

		private function __construct () {

			$this->nombre   = __('Retención del IRPF en WooCommerce', 'woo-irpf');
			$this->domain   = 'woo-irpf';
			$this->json     = 'irpf-woo';
			$this->gestor   = 'admin.php?page=wc-settings&tab=tax';
			$this->archivos = array('clase', 'checkout', 'nif', 'opciones', 'usuario');
			$this->clases   = array('Clase_Woo_IRPF', 'Checkout_Woo_IRPF', 'NIF_Woo_IRPF', 'Opciones_Woo_IRPF', 'Usuario_Woo_IRPF');
			$this->dirname  = dirname (__FILE__);

			$this->carga_archivos();
			$this->actualizaciones();
			$this->carga_traducciones();

			$this->gestor and register_activation_hook (__FILE__, function () {
				set_transient ($this->domain . '-activado', true, 5);
				}, 10 );

			add_action ('init', [$this, 'arranca_plugin'], 10);
			add_action ('admin_notices' , [$this, 'aviso_ayuda'], 10);
			add_filter ('plugin_action_links', [$this, 'enlaces_accion'], 10, 2);
			}

		public function __clone () {

			_doing_it_wrong (__FUNCTION__, sprintf (__('No puedes clonar instancias de %s.', 'text-domain'), get_class ($this)), '1.3.0');
			}

		public function carga_archivos () {

			foreach ($this->archivos as $archivo)
				require ($this->dirname . '/' . $archivo . '.php');
			}

		public function arranca_plugin () {

			if ($this->woocommerce_activo())
				foreach ($this->clases as $clase)
					new $clase;
			}

		private function woocommerce_activo () {

			if (!class_exists ('WooCommerce')) {

				add_action ('admin_notices', function () {
					?>
						<div class="notice notice-error is-dismissible">
							<p><?php printf (__('El plugin %s necesita que WooCommerce esté activado. Por favor, activa WooCommerce primero.', 'woo-irpf'), '<i>' . $this->nombre . '</i>'); ?></p>
						</div>
					<?php
					}, 10);

				return false;
				}

			return true;
			}

		public function aviso_ayuda () {

			if (get_transient ($this->domain . '-activado')) {

				?>
					<div class="updated notice is-dismissible woocommerce-message">
						<p><?php printf (__('Gracias por usar %s. Ve a la página de ajustes para configurar el plugin.', 'woo-irpf'), '<i>' . $this->nombre . '</i>'); ?></p>
						<p><?php printf ('<a href="%s" class="button button-primary">%s</a>', $this->gestor, __('Settings')); ?></p>
					</div>
				<?php
				}
			}

		public function carga_traducciones () {

			$locale = function_exists ('determine_locale') ? determine_locale() : (is_admin() && function_exists ('get_user_locale') ? get_user_locale() : get_locale());
			$locale = apply_filters ('plugin_locale', $locale, $this->domain);

			unload_textdomain ($this->domain);
			load_textdomain ($this->domain, $this->dirname . '/lang/' . $this->domain . '-' . $locale . '.mo');
			load_plugin_textdomain ($this->domain, false, $this->dirname . '/lang');
			}

		public function enlaces_accion ($damelinks, $plugin) {

			static $irpfwoo;
			isset ($irpfwoo) or $irpfwoo = plugin_basename (__FILE__);

			if ($irpfwoo == $plugin) {

				$enlaces['settings'] = '<a href="' . $this->gestor . '">' . __('Settings') . '</a>';
				$enlaces['support']  = '<a target="_blank" href="https://www.enriquejros.com/soporte/">' . __('Soporte', 'woo-irpf') . '</a>';
				$damelinks = array_merge ($enlaces, $damelinks);
				}
			
			return $damelinks;
			}

		public function actualizaciones () {

			include_once ($this->dirname . '/includes/updates/plugin-update-checker.php');
			$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker('https://www.enriquejros.com/wp-content/descargas/updates/' . $this->json . '.json', __FILE__, $this->json);
			}

		public static function instancia () {

			if (null === self::$instancia)
				self::$instancia = new self();

			return self::$instancia;
			}

		}

endif;

Plugin_Woo_IRPF::instancia();