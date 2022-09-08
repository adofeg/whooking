<?php

/**
 * Genera el campo para el NIF, guarda el dato y lo inserta en la factura
 * copyright Enrique J. Ros - enrique@enriquejros.com
 *
 * @author 			Enrique J. Ros
 * @link 			https://www.enriquejros.com
 * @since 			1.1.0
 * @package 		WooIRPF
 *
 */

defined ('ABSPATH') or exit;

if (!class_exists ('NIF_Woo_IRPF')) :

	Class NIF_Woo_IRPF {

		public function __construct () {

			if ('yes' !== get_option ('irpf_nif'))
				return;

			add_filter ('woocommerce_checkout_fields', [$this, 'ordena_campos'], 2, 1);
			add_action ('wpo_wcpdf_after_document_label', [$this, 'pdf_invoices'], 10, 2);
			}

		//Añade el campo en el checkout
		public function ordena_campos ($campos) {

			$posicion = isset ($campos['billing']['billing_last_name']) ? 'billing_last_name' : 'billing_company';
			$orden    = [];

			foreach ($campos['billing'] as $key => $datos)
				$orden[] = $key;

			$nif = array(
				'type'		=> 'text',
				'required'	=> true,
				'label'		=> __('NIF/CIF', 'woo-irpf'),
				'class'		=> ['form-row-wide', 'billing_nif'],
				'default'	=> get_user_meta (get_current_user_id(), 'nif', true),
				'priority'	=> 25,
				);

			foreach ($orden as $campo) {

				if (isset ($campos['billing'][$campo])) {

					$campos_ordenados[$campo] = $campos['billing'][$campo];

					if ($campo == $posicion)
						$campos_ordenados['nif'] = $nif;
					}
				}

			$campos['billing'] = $campos_ordenados;
			return $campos;
			}

		//También en la factura de WooCommerce PDF Invoices & Packing Slips
		public function pdf_invoices ($template_type, $order) {

			if ('yes' == get_option ('irpf_pdf') && $nif = get_post_meta ($order->get_id(), 'NIF', true)) {

				$texto = get_option ('irpf_texto', __('NIF/CIF: ', 'woo-irpf'));
				printf ('%s %s', $texto, $nif);
				}
			}

		}

endif;