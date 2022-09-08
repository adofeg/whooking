<?php

/**
 * Clase principal: Aplica la retención al contenido del carrito
 * copyright Enrique J. Ros - enrique@enriquejros.com
 *
 * @author 			Enrique J. Ros
 * @link 			https://www.enriquejros.com
 * @since 			1.0.0
 * @package 		WooIRPF
 *
 */

defined ('ABSPATH') or exit;

if (!class_exists ('Clase_Woo_IRPF')) :

	Class Clase_Woo_IRPF {

		public function __construct () {

			$this->aplica_irpf();

			add_action ('woocommerce_checkout_update_order_meta', [$this, 'detalles_pedido'], 11, 1);
			add_action ('woocommerce_admin_order_data_after_billing_address', [$this, 'actividad_edicion_pedido'], 10, 1);
			add_action ('woocommerce_admin_order_items_after_shipping', [$this, 'retencion_edicion_pedido'], 10, 1);
			}

		public function aplica_irpf () {

			if (null === ($clase = get_option ('irpf_class')))
				return;

			if (!$_POST || (is_admin() && (!defined ('DOING_AJAX') || !DOING_AJAX)) || !array_key_exists ($clase, $tax_clases = WC_Tax::get_tax_classes()))
				return;

			$this->irpf = $tax_clases[$clase];
			//@adolph
			$this->iva = $tax_clases['IVA'];
			//@adolph->end

			if (isset ($_POST['post_data']))
				parse_str ($_POST['post_data'], $post_data);

			else
				$post_data = $_POST;

			$aplicar = apply_filters ('irpf_woo_aplicar' , ['autonomo', 'empresa']);

			/**
			 * Ejemplo. Para no aplicar retención a empresas:
			 *
			 * add_filter ('irpf_woo_aplicar', function ($aplicar) {
			 *
			 * 		unset ($aplicar[array_search ('empresa', $aplicar)]);
			 *		return $aplicar;
			 * 		}, 10, 1);
			 *
			 */

			if (isset ($post_data['billing_country']) && 'ES' == $post_data['billing_country'] && (isset ($post_data['irpf']) && in_array ($post_data['irpf'], $aplicar))) {

				foreach ($this->decide_hooks() as $hook) {

					add_filter ($hook, function ($impuesto, $producto) {
						return $this->irpf;
						}, 1, 2);
					}
				}
				//@adolph

				if (isset ($post_data['billing_country'])  && (isset ($post_data['irpf'])) && ($post_data['irpf']=='particular')) {

				foreach ($this->decide_hooks() as $hook) {

					add_filter ($hook, function ($impuesto, $producto) {
						return $this->iva;
						}, 1, 2);
					}
				}
				////@adolph->end
			}

		private function decide_hooks () {

			global $woocommerce;
			return version_compare ($woocommerce->version, '3.0', '>=') ? ['woocommerce_product_get_tax_class', 'woocommerce_product_variation_get_tax_class'] : ['woocommerce_product_tax_class', 'woocommerce_product_variation_tax_class'];
			}


		//Incluimos el dato en los detalles del pedido y en el perfil de usuario
		//@adolph


		public function detalles_pedido ($id_pedido) {

			if (!empty ($actividad = $_POST['irpf'])) {

				update_post_meta ($id_pedido, 'actividad', $actividad);
				update_user_meta (get_current_user_id(), 'actividad', $actividad);
				}

			if (!empty ($_POST['nif'])) {

				update_post_meta ($id_pedido, 'NIF', sanitize_text_field ($_POST ['nif']));
				update_user_meta (get_current_user_id(), 'nif', $_POST['nif']);
				}

			if ('ES' == $_POST['billing_country'] && ('autonomo' == $actividad || 'empresa' == $actividad)) {

				$pedido   = wc_get_order ($id_pedido);
				$subtotal = $pedido->get_subtotal();
				$envio    = get_post_meta ($id_pedido, '_order_shipping', true);
				$tax      = new WC_Tax();

				foreach ($tax->get_rates($this->irpf) as $impuesto)
					if ($impuesto['rate'] < 0)
						$retencion = number_format (round (($subtotal + $envio) * $impuesto['rate'] / 100, 2), 2);

				update_post_meta ($id_pedido, '_order_retencion', $retencion);
				}
			//@adolph
			/*
			if ('particular' == $actividad) {

				$pedido   = wc_get_order ($id_pedido);
				$subtotal = $pedido->get_subtotal();
				$envio    = get_post_meta ($id_pedido, '_order_shipping', true);
				$tax      = new WC_Tax();

				foreach ($tax->get_rates($this->iva) as $impuesto)
					if ($impuesto['rate'] < 0)
						$iva = number_format (round (($subtotal + $envio) * $impuesto['rate'] / 100, 2), 2);

				//update_post_meta ($id_pedido, '_order_retencion', $retencion);
				}
				//@adolph-->end*/
			}

		//Incluimos NIF y actividad en la pantalla de edición del pedido
		public function actividad_edicion_pedido ($pedido) {

			if ($actividad = get_post_meta ($pedido->get_id(), 'actividad', true)) {

				echo '<p><b>' . __('Actividad', 'woo-irpf') . ':</b> ' . ucwords ($actividad);

				if ($nif = get_post_meta ($pedido->get_id(), 'NIF', true))
					echo '<br /><b>' . __('NIF/CIF', 'woo-irpf') . ':</b> ' . $nif;

				echo '</p>';
				}
			}

		//Incluimos la retención en la pantalla de edición del pedido
		public function retencion_edicion_pedido ($id_pedido) {

			if (empty ($retencion = get_post_meta ($id_pedido, '_order_retencion', true)))
				return;

			$tax    = new WC_Tax();
			$clases = $tax->get_tax_classes();

			foreach ($tax->get_rates($clases[get_option ('irpf_class')]) as $impuesto)
				if ($impuesto['rate'] < 0)
					$etiqueta = $impuesto['label'];

			?>

				<tbody id="order_fee_line_items">

					<tr class="fee">

						<td class="thumb"></td>
						<td class="name"><?php echo $etiqueta; ?></td>
						<td class="item_cost"></td>
						<td class="quantity"></td>
						<td class="line_cost">
							<span class="woocommerce-Price-amount amount"><?php echo $retencion; ?><span class="woocommerce-Price-currencySymbol">&euro;</span></span>
						</td>
						<td class="line_tax"></td>
						<td class="wc-order-edit-line-item"></td>

					</tr>

				</tbody>

			<?php
			}

		}

endif;