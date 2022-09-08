<?php



/**

 * Funciones del checkout

 * copyright Enrique J. Ros - enrique@enriquejros.com

 *

 * @author 			Enrique J. Ros

 * @link 			https://www.enriquejros.com

 * @since 			1.0.0

 * @package 		WooIRPF

 *

 */



defined ('ABSPATH') or exit;



if (!class_exists ('Checkout_Woo_IRPF')) :



	Class Checkout_Woo_IRPF {



		public function __construct () {



			add_filter ('wp_head', [$this, 'imprime_meta'], 10);

			add_filter ('woocommerce_before_checkout_billing_form', [$this, 'add_selector'], 10, 1);

			add_action ('woocommerce_checkout_process', [$this, 'valida_opcion'], 10);

			add_action ('wp_enqueue_scripts', [$this, 'scripts_estilos'], 10);

			}



		public function imprime_meta () {



			if (!is_checkout() || 'yes' !== get_option ('irpf_nif'))

				return;



			$textos = array(

				'nif'	=> __('NIF <abbr class="required" title="obligatorio">*</abbr>', 'woo-irpf'),

				'cif'	=> __('CIF <abbr class="required" title="obligatorio">*</abbr>', 'woo-irpf'),

				'ambos'	=> __('NIF/CIF <abbr class="required" title="obligatorio">*</abbr>', 'woo-irpf'),

				);



			printf ('<meta id="nif-irpf" data="%s" />',  htmlspecialchars (json_encode ($textos)));

			}



		public function add_selector ($checkout) {



			$campo = array(

				'type'          => 'select',

				'options'		=> array(

					''				=> __('Elige una opción', 'woo-irpf'),

					'particular'	=> __('Soy particular', 'woo-irpf'),

					'autonomo'		=> __('Soy autónomo', 'woo-irpf'),

					'empresa'		=> __('Soy empresa', 'woo-irpf'),

					),

				'required'      => true,

				'label'       	=> __('Actividad', 'woo-irpf'),

				'class'			=> ['form-row-wide', 'billing_irpf'],

				'input_class'	=> ['select2-selection'],

				'default'		=> get_user_meta (get_current_user_id(), 'actividad', true),

				);


			echo "<div class='woocommerce-billing-fields__field-wrapper'>";
			woocommerce_form_field ('irpf', $campo, $checkout->get_value('irpf'));
			echo "</div>";	
			}



		public function valida_opcion () {



			if (!$_POST['irpf'])       

				wc_add_notice (__('Por favor, selecciona una actividad', 'woo-irpf'), 'error');

			}



		public function scripts_estilos () {



			if (is_checkout()) {



				wp_enqueue_script ('irpf', plugins_url ('assets/js/checkout.min.js', __FILE__), array('jquery'));

				wp_enqueue_style ('irpf', plugins_url ('assets/css/checkout.min.css', __FILE__));



				if ('yes' == get_option ('irpf_nif'))

					wp_enqueue_script ('nif-irpf', plugins_url ('assets/js/nif.min.js', __FILE__));

				}

			}



		}



endif;