<header class="header-main-wrap dashboard-header-main-wrap">

    <div class="dashboard-header-wrap">



        <div class="d-flex align-items-center">

            <div class="dashboard-header-left flex-grow-1">

                <h1><?php echo houzez_option('dsh_leads', 'Leads'); ?></h1>

                <div class="search-bar">
                    <form method="GET" action="https://www.whooking.com/board/">
                        <input type="hidden" name="hpage" value="leads">
                        <div class="row">
                            <div class="col-sm-5 col-md-5 col-xl-3">
                                <div class="form-group">
                                    <input type="text" name="search-param" placeholder="Nombre, Apellido o E-mail" id="search-param" class="form-control" value="<?= isset($_GET["search-param"]) ? trim($_GET["search-param"]) : "" ?>" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-5 col-xl-4">
                                <div class="form-group">

                                    <select name="tipo" id="tipo" class="form-control">

                                        <?php
                                        $miType = isset($_GET["tipo"]) ? trim($_GET["tipo"]) : "";
                                        $types = Houzez_leads::get_leads_types();
                                        echo "<option value=''" . ($miType == "" ? "selected" : "") . ">Todos</option>";
                                        foreach ($types as $type) {
                                            if (trim($type['type']) != "") {
                                                echo "<option value='" . $type['type'] . "' " . ($miType == $type["type"] ? "selected" : "") . ">" . strtoupper($type['type']) . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3 col-xl-3">
                                <input class="btn btn-primary" type="submit" value="Filtrar">
                            </div>
                        </div>
                    </form>


                </div>

            </div><!-- dashboard-header-left -->

            <div class="dashboard-header-right">

                <a class="btn btn-primary open-close-slide-panel" href="#"><?php esc_html_e('Add New Lead', 'houzez'); ?></a>

            </div><!-- dashboard-header-right -->



        </div><!-- d-flex -->

    </div><!-- dashboard-header-wrap -->

</header><!-- .header-main-wrap -->



<section class="dashboard-content-wrap">

    <div class="dashboard-content-inner-wrap p-0">

        <div class="dashboard-content-block-wrap leads-padding p-4">



            <?php

            $dashboard_crm = houzez_get_template_link_2('template/user_dashboard_crm.php');



            $leads = Houzez_leads::get_leads();



            if (!empty($leads['data']['results'])) { ?>



                <table class="dashboard-table table-lined responsive-table">

                    <thead>

                        <tr>

                            <th><?php esc_html_e('Name', 'houzez'); ?></th>

                            <th><?php esc_html_e('Email', 'houzez'); ?></th>

                            <th><?php esc_html_e('Phone', 'houzez'); ?></th>

                            <th><?php esc_html_e('Type', 'houzez'); ?></th>

                            <th><?php esc_html_e('Date', 'houzez'); ?></th>

                            <th class="action-col"><?php esc_html_e('Actions', 'houzez'); ?></th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        foreach ($leads['data']['results'] as $result) {

                            $detail_link = add_query_arg(

                                array(

                                    'hpage' => 'lead-detail',

                                    'lead-id' => $result->lead_id,

                                    'tab' => 'enquires',

                                ),
                                $dashboard_crm

                            );



                            $datetime = $result->time;



                            $datetime_unix = strtotime($datetime);

                            $get_date = houzez_return_formatted_date($datetime_unix);

                            $get_time = houzez_get_formatted_time($datetime_unix);

                        ?>



                            <tr class="table-responsive-contact">

                                <td class="table-wrap" data-label="<?php esc_html_e('Name', 'houzez'); ?>">

                                    <?php echo esc_attr($result->display_name); ?>

                                </td>

                                <td class="table-wrap" data-label="<?php esc_html_e('Email', 'houzez'); ?>">

                                    <a href="mailto:<?php echo esc_attr($result->email); ?>">

                                        <strong><?php echo esc_attr($result->email); ?></strong>

                                    </a>

                                </td>

                                <td class="table-wrap" data-label="<?php esc_html_e('Phone', 'houzez'); ?>">

                                    <?php echo esc_attr($result->mobile); ?>

                                </td>

                                <td class="table-wrap" data-label="<?php esc_html_e('Type', 'houzez'); ?>">

                                    <?php

                                    $type = stripslashes($result->type);

                                    $type = htmlentities($type);

                                    echo esc_attr($type); ?>

                                </td>

                                <td class="table-wrap" data-label="<?php esc_html_e('Date', 'houzez'); ?>">

                                    <?php echo esc_attr($get_date); ?><br>

                                    <?php echo esc_html__('at', 'houzez'); ?> <?php echo esc_attr($get_time); ?>

                                </td>

                                <td class="table-wrap">

                                    <div class="dropdown property-action-menu">

                                        <button class="btn btn-primary-outlined dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                            <?php esc_html_e('Actions', 'houzez'); ?>

                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                                            <a class="dropdown-item" href="<?php echo esc_url($detail_link); ?>"><?php esc_html_e('Details', 'houzez'); ?></a>



                                            <?php //@adolph edit-lead 

                                            ?>



                                            <a href="" class="delete-lead dropdown-item" data-id="<?php echo intval($result->lead_id); ?>" data-nonce="<?php echo wp_create_nonce('delete_lead_nonce') ?>"><?php esc_html_e('Delete', 'houzez'); ?></a>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        <?php

                        } ?>

                    </tbody>

                    <tfoot>

                        <tr>

                            <td colspan="5">

                                <?php get_template_part('template-parts/dashboard/board/records-html'); ?>

                            </td>

                            <td class="text-right">

                                <div class="crm-pagination">

                                    <?php

                                    echo paginate_links(array(

                                        'base' => add_query_arg('cpage', '%#%'),

                                        'format' => '',

                                        'prev_text' => __('&laquo;'),

                                        'next_text' => __('&raquo;'),

                                        'total' => ceil($leads['data']['total_records'] / $leads['data']['items_per_page']),

                                        'current' => $leads['data']['page']

                                    ));

                                    ?>

                                </div>

                            </td>

                        </tr>

                    </tfoot>

                </table>

            <?php

            } else { ?>

                <div class="dashboard-content-block">

                    <?php esc_html_e("You don't have any contact at this moment.", 'houzez'); ?> <a class="open-close-slide-panel" href="#"><strong><?php esc_html_e('Add New Lead', 'houzez'); ?></strong></a>

                </div><!-- dashboard-content-block -->

            <?php } ?>





        </div><!-- dashboard-content-block-wrap -->

    </div><!-- dashboard-content-inner-wrap -->

</section><!-- dashboard-content-wrap -->

<section class="dashboard-side-wrap">

    <?php get_template_part('template-parts/dashboard/side-wrap'); ?>

</section>