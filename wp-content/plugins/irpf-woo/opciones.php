<?php

/**
 * Opciones de usuario dentro de los ajustes de WooCommerce
 * copyright Enrique J. Ros - enrique@enriquejros.com
 *
 * @author 			Enrique J. Ros
 * @link 			https://www.enriquejros.com
 * @since 			1.0.0
 * @package 		WooIRPF
 *
 */

defined ('ABSPATH') or exit;

if (!class_exists ('Opciones_Woo_IRPF')) :

	Class Opciones_Woo_IRPF {

		public function __construct () {

			add_filter ('woocommerce_tax_settings', [$this, 'add_opciones_irpf'], 10, 2);
			}

		public function add_opciones_irpf ($opciones) {

			$opciones[] = array(
				'name'		=> __('Retención del IRPF', 'woo-irpf'),
				'type'		=> 'title',
				'id'		=> 'irpf',
				);

			$opciones[] = array(
				'name'		=> __('Impuesto a aplicar', 'woo-irpf'),
				'id'		=> 'irpf_class',
				'type'		=> 'select',
				'css'		=> '',
				'desc_tip'	=> __('Selecciona cuál es la clase de impuestos en la que se aplica la retención', 'woo-irpf'),
				'options'	=> WC_Tax::get_tax_classes(),
				'default'	=> 'IRPF',
				);

			$opciones[] = array(
				'name'		=> __('Pedir el NIF/CIF', 'woo-irpf'),
				'id'		=> 'irpf_nif',
				'type'		=> 'checkbox',
				'css'		=> '',
				'desc'		=> __('Selecciona para añadir un campo para el NIF/CIF en el checkout', 'woo-irpf'),
				'default'	=> 'no',
				);

			if (class_exists ('WPO_WCPDF')) {

				$opciones[] = array(
					'name'		=> __('Insertarlo en las facturas', 'woo-irpf'),
					'id'		=> 'irpf_pdf',
					'type'		=> 'checkbox',
					'css'		=> '',
					'desc'		=> __('Selecciona para añadir el NIF/CIF en las facturas de WooCommerce PDF Invoices & Packing Slips', 'woo-irpf'),
					'default'	=> 'no',
					);

				$opciones[] = array(
					'name'		=> __('Texto antes del dato', 'woo-irpf'),
					'id'		=> 'irpf_texto',
					'type'		=> 'text',
					'css'		=> '',
					'desc_tip'	=> __('Texto que se antepondrá al dato en la factura', 'woo-irpf'),
					'default'	=> __('NIF/CIF') . ': ',
					);
				}
				
			$opciones[] = array(
				'type'		=> 'sectionend',
				'id'		=> 'irpf',
				);

			return $opciones;
			}

		}

endif;