<?php
/**
 * Template Name: User Dashboard Membership Info
 */
if ( !is_user_logged_in() ) {
    wp_redirect(  home_url() );
}

global $houzez_local;
$userID         = get_current_user_id();
$dashboard_membership = houzez_get_template_link_2('template/user_dashboard_membership.php');
$packages_page_link = houzez_get_template_link('template/template-packages.php');
$package_id = houzez_get_user_package_id( $userID );

get_header(); ?>

<header class="header-main-wrap dashboard-header-main-wrap">
    <div class="dashboard-header-wrap">
        <div class="d-flex align-items-center">
            <div class="dashboard-header-left flex-grow-1">
                <h1><?php echo houzez_option('dsh_membership', 'Membership'); ?></h1>         
            </div><!-- dashboard-header-left -->
            <div class="dashboard-header-right">
                
            </div><!-- dashboard-header-right -->
        </div><!-- d-flex -->
    </div><!-- dashboard-header-wrap -->
</header><!-- .header-main-wrap -->
<section class="dashboard-content-wrap">
    <div class="dashboard-content-inner-wrap">
        <div class="dashboard-content-block-wrap">
            
           
            
                
                <div class="dashboard-content-block">
                    
                    <?php echo  do_shortcode('[elementor-template id="18212"]'); ?>

                </div>

                <?php
                $packages_page_link="www.whooking.com/precios/";
                echo '<a href="' . esc_url($packages_page_link) . '" class="btn btn-primary mb-2"> ' . esc_html__('Actualiza el plan de membresía', 'houzez') . ' </a>';
            
            ?>                
        </div><!-- dashboard-content-block-wrap -->
    </div><!-- dashboard-content-inner-wrap -->
</section><!-- dashboard-content-wrap -->
<section class="dashboard-side-wrap">
    <?php get_template_part('template-parts/dashboard/side-wrap'); ?>
</section>

<?php get_footer(); ?>