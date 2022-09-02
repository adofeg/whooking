<?php global $deal_settings;
$agency_id = get_user_meta(get_current_user_id(), 'fave_author_agency_id', true);
?>
<div class="dashboard-slide-panel-wrap deal-panel-wrap-js">
	<form id="deal-form">
		<h2><?php esc_html_e('Add New Deal', 'houzez'); ?></h2>
		<button type="button" class="btn open-close-slide-panel">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="form-group">
			<label><?php esc_html_e('Group', 'houzez'); ?></label>
			<select name="deal_group" class="selectpicker form-control bs-select-hidden" title="<?php esc_html_e('Select', 'houzez'); ?>" data-live-search="false">
				<option value="active"><?php esc_html_e('Active Deals', 'houzez'); ?></option>
				<option value="won"><?php esc_html_e('Won Deals', 'houzez'); ?></option>
				<option value="lost"><?php esc_html_e('Lost Deals', 'houzez'); ?></option>
			</select><!-- selectpicker -->
		</div><!-- form-group -->
		<div class="form-group">
			<label><?php esc_html_e('Title', 'houzez'); ?></label>
			<input class="form-control" name="deal_title" placeholder="<?php esc_html_e('Enter the deal title', 'houzez'); ?>" type="text">
		</div>
		<div class="form-group">
			<label><?php esc_html_e('Contact Name', 'houzez'); ?></label>
			<select name="deal_contact" class="selectpicker form-control bs-select-hidden" title="<?php esc_html_e('Select', 'houzez'); ?>" data-live-search="true">
				<option value=""><?php esc_html_e('Select', 'houzez'); ?></option>
				<?php
				$all_leads = Houzez_leads::get_all_leads();
				foreach ($all_leads as $lead) {
					echo '<option value="'.intval($lead->lead_id).'">'.$lead->display_name.'</option>';
				}
				?>

			</select><!-- selectpicker -->
		</div><!-- form-group -->



		<div class="form-group">

			<input class="form-control" name="deal_value" placeholder="<?php esc_html_e('Enter the deal value', 'houzez'); ?>" type="hidden" value="1">
		</div>
		<button id="add_deal" type="button" class="btn btn-primary btn-full-width mt-2">
			<?php get_template_part('template-parts/loader'); ?>
			<?php esc_html_e('Save', 'houzez'); ?>
		</button>

		<?php get_template_part('template-parts/overlay-loader'); ?>

		<input type="hidden" name="action" value="houzez_crm_add_deal">
		<br/>
		<div id="deal-msgs"></div>
	</form>
</div><!-- dashboard-slide-panel-wrap -->