<?php

/**
 * Datos en el perfil de usuario
 * copyright Enrique J. Ros - enrique@enriquejros.com
 *
 * @author 			Enrique J. Ros
 * @link 			https://www.enriquejros.com
 * @since 			1.2.0
 * @package 		WooIRPF
 *
 */

defined ('ABSPATH') or exit;

if (!class_exists ('Usuario_Woo_IRPF')) :

	Class Usuario_Woo_IRPF {

		public function __construct () {

			add_action ('woocommerce_customer_meta_fields', [$this, 'campos_perfil_cliente'], 10, 1);
			}

		public function campos_perfil_cliente ($campos) {

			$campos ['billing']['fields']['actividad'] = array(
				'label'			=> __('Actividad', 'woo-irpf'),
				'description'	=> '',
				'type'			=> 'select',
				'options'		=> array(
					''				=> __('Selecciona...', 'woo-irpf'),
					'particular'	=> __('Particular', 'woo-irpf'),
					'autonomo'		=> __('Autónomo', 'woo-irpf'),
					'empresa'		=> __('Empresa', 'woo-irpf'),
					),
				);

			$campos ['billing']['fields']['nif'] = array(
				'label'			=> strlen ($label = get_option ('irpf_texto')) ? $label : __('NIF/CIF', 'woo-irpf'),
				'description'	=> '',
				'type'			=> 'text',
				);

			return $campos;
			}

		}

endif;