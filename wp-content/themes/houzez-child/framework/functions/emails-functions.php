<?php
/**
 * File Name: Email Functions
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 03/02/16
 * Time: 5:38 PM
 */

if (!function_exists('houzez_email_type')) {
    function houzez_email_type( $email, $email_type, $args ) {

        $value_message = houzez_option('houzez_' . $email_type, '');
        $value_subject = houzez_option('houzez_subject_' . $email_type, '');

        do_action( 'wpml_register_single_string', 'houzez', 'houzez_email_' . $value_message, $value_message );
        do_action( 'wpml_register_single_string', 'houzez', 'houzez_email_subject_' . $value_subject, $value_subject );

        $value_message = apply_filters('wpml_translate_single_string', $value_message, 'houzez', 'houzez_email_' . $value_message );
        $value_subject = apply_filters('wpml_translate_single_string', $value_subject, 'houzez', 'houzez_email_subject_' . $value_subject );


        houzez_emails_filter_replace( $email, $value_message, $value_subject, $args);
    }
}

if (!function_exists('houzez_email_with_reply')) {
    function houzez_email_with_reply( $email, $email_type, $args, $sender_name, $sender_email, $cc_email, $bcc_email ) {

        $value_message = houzez_option('houzez_' . $email_type, '');
        $value_subject = houzez_option('houzez_subject_' . $email_type, '');

        do_action( 'wpml_register_single_string', 'houzez', 'houzez_email_' . $value_message, $value_message );
        do_action( 'wpml_register_single_string', 'houzez', 'houzez_email_subject_' . $value_subject, $value_subject );

        $value_message = apply_filters('wpml_translate_single_string', $value_message, 'houzez', 'houzez_email_' . $value_message );
        $value_subject = apply_filters('wpml_translate_single_string', $value_subject, 'houzez', 'houzez_email_subject_' . $value_subject );


        return houzez_emails_maker( $email, $value_message, $value_subject, $args, $sender_name, $sender_email, $cc_email, $bcc_email);
    }
}

if( !function_exists('houzez_emails_maker')):
    function  houzez_emails_maker( $email, $message, $subject, $args, $sender_name, $sender_email, $cc_email, $bcc_email ) {
        $args ['website_url'] = get_option('siteurl');
        $args ['website_name'] = get_option('blogname');
        $args ['user_email'] = $email;
        $user = get_user_by( 'email',$email );
        $args ['username'] = $user->user_login;

        foreach( $args as $key => $val){
            $subject = str_replace( '%'.$key, $val, $subject );
            $message = str_replace( '%'.$key, $val, $message );
        }

        return houzez_send_emails_with_reply( $email, $subject, $message, $sender_name, $sender_email, $cc_email, $bcc_email );
        
    }
endif;

if( !function_exists('houzez_emails_filter_replace')):
    function  houzez_emails_filter_replace( $email, $message, $subject, $args ) {
        $args ['website_url'] = get_option('siteurl');
        $args ['website_name'] = get_option('blogname');
        $args ['user_email'] = $email;
        $user = get_user_by( 'email',$email );
        $args ['username'] = $user->user_login;

        foreach( $args as $key => $val){
            $subject = str_replace( '%'.$key, $val, $subject );
            $message = str_replace( '%'.$key, $val, $message );
        }

        houzez_send_emails( $email, $subject, $message );
        
    }
endif;

if( !function_exists('houzez_emails_filter_replace_2')):
    function  houzez_emails_filter_replace_2( $email, $message, $subject, $args ) {
        $args ['website_url'] = get_option('siteurl');
        $args ['website_name'] = get_option('blogname');
        $args ['user_email'] = $email;
        $user = get_user_by( 'email',$email );
        $args ['username'] = $user->user_login;

        foreach( $args as $key => $val){
            $subject = str_replace( '%'.$key, $val, $subject );
            $message = str_replace( '%'.$key, $val, $message );
        }

        houzez_send_emails_match_submission( $email, $subject, $message );
        
    }
endif;


if( !function_exists('houzez_send_emails_with_reply') ):
    function houzez_send_emails_with_reply( $user_email, $subject, $message, $sender_name, $sender_email, $cc_email, $bcc_email ){
        $headers = array();
        
        $enable_html_emails = houzez_option('enable_html_emails');
        $enable_email_header = houzez_option('enable_email_header');
        $enable_email_footer = houzez_option('enable_email_footer');

        $cc_header = '';
        if ( ! empty( $cc_email ) ) {
            $cc_email = sanitize_email( $cc_email );
            $cc_email = is_email( $cc_email );
            $cc_header = 'Cc: ' . $cc_email . "\r\n";
        }

        $headers[] = "From: $sender_name <$sender_email>";
        $headers[] = "Reply-To: $sender_name <$sender_email>";
        if( $enable_html_emails != 0 ) {
            $headers[] = "Content-Type: text/html; charset=UTF-8";
        }
        $headers = apply_filters( "houzez_send_mails_header", $headers );// Filter for modify the header in child theme

        $enable_html_emails = houzez_option('enable_html_emails');
        $email_head_logo = houzez_option('email_head_logo', false, 'url');
        $email_head_bg_color = houzez_option('email_head_bg_color');
        $email_foot_bg_color = houzez_option('email_foot_bg_color');
        $email_footer_content = houzez_option('email_footer_content');

        $social_1_icon = houzez_option('social_1_icon', false, 'url');
        $social_1_link = houzez_option('social_1_link');
        $social_2_icon = houzez_option('social_2_icon', false, 'url');
        $social_2_link = houzez_option('social_2_link');
        $social_3_icon = houzez_option('social_3_icon', false, 'url');
        $social_3_link = houzez_option('social_3_link');
        $social_4_icon = houzez_option('social_4_icon', false, 'url');
        $social_4_link = houzez_option('social_4_link');

        $message = wp_kses_post( wpautop( wptexturize( $message ) ) );

        $socials = '';
        if( !empty($social_1_icon) || !empty($social_2_icon) || !empty($social_3_icon) || !empty($social_4_icon) ) {
            $socials = '<div style="font-size: 0; text-align: center; padding-top: 20px;">';
            $socials .= '<p style="margin:0;margin-bottom: 10px; text-align: center; font-size: 14px; color:#777777;">'.esc_html__('Follow us on', 'houzez').'</p>';

            if( !empty($social_1_icon) ) {
                $socials .= '<a href="'.esc_url($social_1_link).'" style="margin-right: 5px"><img src="'.esc_url($social_1_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_2_icon) ) {
                $socials .= '<a href="'.esc_url($social_2_link).'" style="margin-right: 5px"><img src="'.esc_url($social_2_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_3_icon) ) {
                $socials .= '<a href="'.esc_url($social_3_link).'" style="margin-right: 5px"><img src="'.esc_url($social_3_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_4_icon) ) {
                $socials .= '<a href="'.esc_url($social_4_link).'" style="margin-right: 5px"><img src="'.esc_url($social_4_icon).'" width="" height="" alt=""> </a>';
            }

            $socials .= '</div>';
        }

        if( $enable_email_header != 0 ) {
            $email_content = '<div style="text-align: center; background-color: ' . esc_attr($email_head_bg_color) . '; padding: 16px 0;">
                            <img src="' . esc_url($email_head_logo) . '" alt="logo">
                        </div>';
        }

        $email_content .= '<div style="background-color: #F6F6F6; padding: 30px;">
                            <div style="margin: 0 auto; width: 620px; background-color: #fff;border:1px solid #eee; padding:30px;">
                                <div style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:100%;line-height:1.6em;display:block;max-width:600px;margin:0 auto;padding:0">
                                '.$message.'
                                </div>
                            </div>
                        </div>';

        if( $enable_email_footer != 0 ) {
            $email_content .= '<div style="padding-top: 30px; text-align:center; padding-bottom: 30px; font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;">

                            <div style="width: 640px; background-color: ' . $email_foot_bg_color . '; margin: 0 auto;">
                                ' . $email_footer_content . '
                            </div>
                            ' . $socials . '
                        </div>';
        }

        if( $enable_html_emails != 0 ) {
            $email_messages = $email_content;
        } else {
            $email_messages = $message;
        }


        if ( ! empty( $bcc_email ) ) {
            $bcc_emails = explode( ',', $bcc_email );
            foreach ( $bcc_emails as $bcc_email ) {
                wp_mail( trim( $bcc_email ), $subject, $email_messages, $headers );
            }
        }

        $headers[] = $cc_header;

        $email_sent = @wp_mail(
            $user_email,
            $subject,
            $email_messages,
            $headers
        );

        return $email_sent;

    };
endif;

if( !function_exists('houzez_send_emails') ):
    function houzez_send_emails( $user_email, $subject, $message ){
        $headers = array();
        $headers[] = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>';

        $enable_html_emails = houzez_option('enable_html_emails');
        $enable_email_header = houzez_option('enable_email_header');
        $enable_email_footer = houzez_option('enable_email_footer');

        if( $enable_html_emails != 0 ) {
            $headers[] = "Content-Type: text/html; charset=UTF-8";
        }
        $headers = apply_filters( "houzez_send_mails_header", $headers );// Filter for modify the header in child theme

        $enable_html_emails = houzez_option('enable_html_emails');
        $email_head_logo = houzez_option('email_head_logo', false, 'url');
        $email_head_bg_color = houzez_option('email_head_bg_color');
        $email_foot_bg_color = houzez_option('email_foot_bg_color');
        $email_footer_content = houzez_option('email_footer_content');

        $social_1_icon = houzez_option('social_1_icon', false, 'url');
        $social_1_link = houzez_option('social_1_link');
        $social_2_icon = houzez_option('social_2_icon', false, 'url');
        $social_2_link = houzez_option('social_2_link');
        $social_3_icon = houzez_option('social_3_icon', false, 'url');
        $social_3_link = houzez_option('social_3_link');
        $social_4_icon = houzez_option('social_4_icon', false, 'url');
        $social_4_link = houzez_option('social_4_link');

        $message = wp_kses_post( wpautop( wptexturize( $message ) ) );

        $socials = '';
        if( !empty($social_1_icon) || !empty($social_2_icon) || !empty($social_3_icon) || !empty($social_4_icon) ) {
            $socials = '<div style="font-size: 0; text-align: center; padding-top: 20px;">';
            $socials .= '<p style="margin:0;margin-bottom: 10px; text-align: center; font-size: 14px; color:#777777;">'.esc_html__('Follow us on', 'houzez').'</p>';

            if( !empty($social_1_icon) ) {
                $socials .= '<a href="'.esc_url($social_1_link).'" style="margin-right: 5px"><img src="'.esc_url($social_1_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_2_icon) ) {
                $socials .= '<a href="'.esc_url($social_2_link).'" style="margin-right: 5px"><img src="'.esc_url($social_2_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_3_icon) ) {
                $socials .= '<a href="'.esc_url($social_3_link).'" style="margin-right: 5px"><img src="'.esc_url($social_3_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_4_icon) ) {
                $socials .= '<a href="'.esc_url($social_4_link).'" style="margin-right: 5px"><img src="'.esc_url($social_4_icon).'" width="" height="" alt=""> </a>';
            }

            $socials .= '</div>';
        }

        if( $enable_email_header != 0 ) {
            $email_content = '<div style="text-align: center; background-color: ' . esc_attr($email_head_bg_color) . '; padding: 16px 0;">
                            <img src="' . esc_url($email_head_logo) . '" alt="logo">
                        </div>';
        }

        $email_content .= '<div style="background-color: #F6F6F6; padding: 30px;">
                            <div style="margin: 0 auto; width: 620px; background-color: #fff;border:1px solid #eee; padding:30px;">
                                <div style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:100%;line-height:1.6em;display:block;max-width:600px;margin:0 auto;padding:0">
                                '.$message.'
                                </div>
                            </div>
                        </div>';

        if( $enable_email_footer != 0 ) {
            $email_content .= '<div style="padding-top: 30px; text-align:center; padding-bottom: 30px; font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;">

                            <div style="width: 640px; background-color: ' . $email_foot_bg_color . '; margin: 0 auto;">
                                ' . $email_footer_content . '
                            </div>
                            ' . $socials . '
                        </div>';
        }

        if( $enable_html_emails != 0 ) {
            $email_messages = $email_content;
        } else {
            $email_messages = $message;
        }

        @wp_mail(
            $user_email,
            $subject,
            $email_messages,
            $headers
        );
    };
endif;



if( !function_exists('houzez_send_emails_match_submission') ):
    function houzez_send_emails_match_submission( $user_email, $subject, $message ){
        
        $headers = array();
        $fromemail = 'noreply@'.$_SERVER['HTTP_HOST'].'';
        $headers[] = 'From: No Reply <noreply@example.com>';

        $enable_html_emails = houzez_option('enable_html_emails');
        $enable_email_header = houzez_option('enable_email_header');
        $enable_email_footer = houzez_option('enable_email_footer');

        $headers[] = "Content-Type: text/html; charset=UTF-8";
    

        $headers = apply_filters( "houzez_match_listings_mail_header", $headers );// Filter for modify the header in child theme

        $enable_html_emails = houzez_option('enable_html_emails');
        $email_head_logo = houzez_option('email_head_logo', false, 'url');
        $email_head_bg_color = houzez_option('email_head_bg_color');
        $email_foot_bg_color = houzez_option('email_foot_bg_color');
        $email_footer_content = houzez_option('email_footer_content');

        $social_1_icon = houzez_option('social_1_icon', false, 'url');
        $social_1_link = houzez_option('social_1_link');
        $social_2_icon = houzez_option('social_2_icon', false, 'url');
        $social_2_link = houzez_option('social_2_link');
        $social_3_icon = houzez_option('social_3_icon', false, 'url');
        $social_3_link = houzez_option('social_3_link');
        $social_4_icon = houzez_option('social_4_icon', false, 'url');
        $social_4_link = houzez_option('social_4_link');

        $socials = '';
        if( !empty($social_1_icon) || !empty($social_2_icon) || !empty($social_3_icon) || !empty($social_4_icon) ) {
            $socials = '<div class="follow">';
            $socials .= '<p>'.esc_html__('Follow us on', 'houzez').'</p>';

            $socials .= '<div>';
            if( !empty($social_1_icon) ) {
                $socials .= '<a href="'.esc_url($social_1_link).'" style="margin-right: 5px"><img src="'.esc_url($social_1_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_2_icon) ) {
                $socials .= '<a href="'.esc_url($social_2_link).'" style="margin-right: 5px"><img src="'.esc_url($social_2_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_3_icon) ) {
                $socials .= '<a href="'.esc_url($social_3_link).'" style="margin-right: 5px"><img src="'.esc_url($social_3_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_4_icon) ) {
                $socials .= '<a href="'.esc_url($social_4_link).'" style="margin-right: 5px"><img src="'.esc_url($social_4_icon).'" width="" height="" alt=""> </a>';
            }

            $socials .= '</div>';
            $socials .= '</div>';
        }

        $email_wrap_start = '<!DOCTYPE html>
                        <html>
                        <body style="padding: 0; margin: 0;">
                            
                            <!-- main-wrap -->
                            <div class="main-wrap" style="text-align: center; font-family: Arial, sans-serif; font-size: 14px; line-height: 22px; background-color: #F8F8F8; padding-bottom:20px;">';

        $email_wrap_end = '</div></body></html>';

        $email_header = '<div class="header" style="background-color: ' . esc_attr($email_head_bg_color) . '; padding: 20px 0; margin: 0 0 30px;">
            <a style="display: inline-block; text-decoration: none;" href="#"><img src="' . esc_url($email_head_logo) . '"></a>
        </div>';

        $email_footer = '<div class="footer" style="margin-top: 60px; text-align:center; background: ' . $email_foot_bg_color . '; color: #777777; padding: 60px 10px; margin: 0 auto;">
            ' . $email_footer_content . '

            ' . $socials . '

        </div>';

        $email_content = $email_wrap_start;

        if( $enable_email_header != 0 ) {
            $email_content .= $email_header;
        }

        $email_content .= '<style type="text/css">
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: Helvetica, Arial, sans-serif;
                    font-size: 16px;
                    line-height: 24px;
                    font-weight: 300;
                }
                ul, li {
                    list-style: none;
                }
                p {
                    margin-bottom: 20px;
                }
                .email-wrap {
                    background-color: #f8f8f8;
                    padding-bottom: 30px;
                }
                .email-listings,
                .email-content,
                .email-footer,
                .email-header-inner {
                    max-width: 350px;
                    margin: 0 auto;
                }
                .email-header {
                    background-color: #fff;
                    padding: 15px 0;
                    margin-bottom: 40px;
                    box-shadow: 0 4px 8px rgba(0,0,0,.10);
                }
                .email-content {
                    padding: 0 0 10px;   
                    font-weight: 300;
                    /*letter-spacing: 0.65px; */
                }
                .email-listings-image,
                .email-listings-image a {
                    position: relative;
                }
                .img-fluid {
                    max-width: 100%;
                }
                .email-listings-image a,
                .email-listings-image a:before,
                .item-amenities .item-amenities-type,
                .item-amenities .item-amenities-sqft,
                .btn {
                    display: block;
                }
                .btn,
                .item-amenities,
                .item-address {
                    font-size: 16px;
                    line-height: 24px;
                    font-weight: 500;
                }
                .item-price {
                    font-size: 18px;
                    line-height: 24px;
                    font-weight: 400;
                }
                .item-sub-price {
                    font-size: 15px;
                    line-height: 24px;
                    font-weight: 300;
                    /*letter-spacing: 0.65px;*/
                }
                .item-title {
                    font-size: 20px;
                    line-height: 24px;
                    font-weight: 500;
                    margin-bottom: 10px
                }
                .item-address {
                    font-weight: 300;
                    /*letter-spacing: 0.65px;*/
                    margin: 0 0 10px;
                    font-style: normal;
                    color: #5c6872;
                }
                .btn {
                    text-decoration: none;
                    background-color: #004274;
                    border-color: #004274;
                    color: #fff;
                    border-radius: 4px;
                    padding: 12px;
                    text-align: center;
                }
                .email-listings-item {
                    padding: 0;
                    margin-bottom: 30px;
                    background-color: #fff;
                    border-radius: 4px;
                    overflow: hidden;
                }
                .email-listings-content {
                    padding: 20px 25px 25px;
                }
                .email-listings-image a:before {
                    content: "";
                    height: 100%;
                    width: 100%;
                    position: absolute;
                    opacity: 1;
                    background-image: -webkit-gradient(linear,left top,left bottom,from(rgba(0,0,0,0)),color-stop(0,rgba(0,0,0,0)),color-stop(50%,rgba(0,0,0,0)),to(rgba(0,0,0,.75)));
                    background-image: -o-linear-gradient(top,rgba(0,0,0,0) 0,rgba(0,0,0,0) 0,rgba(0,0,0,0) 50%,rgba(0,0,0,.75) 100%);
                    background-image: linear-gradient(to bottom,rgba(0,0,0,0) 0,rgba(0,0,0,0) 0,rgba(0,0,0,0) 50%,rgba(0,0,0,.75) 100%);
                }
                .item-title a {
                    display: inline-block;
                    color: #000;
                    text-decoration: none;
                }
                .status-label {
                    color: #fff;
                    background-color: #333;
                    border-radius: 4px;
                    padding: 0px 7px;
                    font-size: 10px;
                    text-transform: uppercase;
                    position: absolute;
                    top: 25px;
                    left: 25px;
                    z-index: 1;
                    font-weight: 500;
                    letter-spacing: .5px
                }
                .type-label {
                    font-size: 14px;
                    text-transform: uppercase;
                }
                .item-price-wrap {
                    text-decoration: none;
                    position: absolute;
                    bottom: 25px;
                    left: 25px;
                    z-index: 1;
                    color: #fff;
                    font-size: 18px;
                    font-weight: 400;
                }
                .item-amenities li {
                    display: inline-block;
                    line-height: 26px;        
                }
                .item-amenities-text {
                    font-weight: 300;
                    color: #5c6872;
                    margin-right: 12px
                }
                .item-amenities-sqft .item-amenities-text {
                    margin-right: 0;
                }
                .item-amenities-type {
                    margin-top: 10px;
                }
                .email-footer {
                    color: #5c6872;
                    text-align: center;
                    font-size: 14px;  
                    line-height: 20px;
                    font-weight: 300;
                    /*letter-spacing: 0.65px; */
                }
                .email-footer p {
                    margin-bottom: 10px;
                }
                .social-media {
                    margin: 0 0 20px 0;
                }
                .social-media li {
                    display: inline-block;
                    margin: 0 5px; 
                }
            </style>';

        $email_content .= wp_kses_post( wpautop( wptexturize( $message ) ) );

        if( $enable_email_footer != 0 ) {
            $email_content .= $email_footer;
        }

        $email_content .= $email_wrap_end;


        $email_messages = $email_content;

        @wp_mail(
            $user_email,
            $subject,
            $email_messages,
            $headers
        );
    };
endif;

if( !function_exists('houzez_send_messages_emails') ):
    function houzez_send_messages_emails( $user_email, $subject, $message ){
        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $enable_html_emails = houzez_option('enable_html_emails');
        $enable_email_header = houzez_option('enable_email_header');
        $enable_email_footer = houzez_option('enable_email_footer');


        $enable_html_emails = houzez_option('enable_html_emails');
        $email_head_logo = houzez_option('email_head_logo', false, 'url');
        $email_head_bg_color = houzez_option('email_head_bg_color');
        $email_foot_bg_color = houzez_option('email_foot_bg_color');
        $email_footer_content = houzez_option('email_footer_content');

        $social_1_icon = houzez_option('social_1_icon', false, 'url');
        $social_1_link = houzez_option('social_1_link');
        $social_2_icon = houzez_option('social_2_icon', false, 'url');
        $social_2_link = houzez_option('social_2_link');
        $social_3_icon = houzez_option('social_3_icon', false, 'url');
        $social_3_link = houzez_option('social_3_link');
        $social_4_icon = houzez_option('social_4_icon', false, 'url');
        $social_4_link = houzez_option('social_4_link');

        $message = wp_kses_post( wpautop( wptexturize( $message ) ) );

        $socials = '';
        if( !empty($social_1_icon) || !empty($social_2_icon) || !empty($social_3_icon) || !empty($social_4_icon) ) {
            $socials = '<div style="font-size: 0; text-align: center; padding-top: 20px;">';
            $socials .= '<p style="margin:0;margin-bottom: 10px; text-align: center; font-size: 14px; color:#777777;">'.esc_html__('Follow us on', 'houzez').'</p>';

            if( !empty($social_1_icon) ) {
                $socials .= '<a href="'.esc_url($social_1_link).'" style="margin-right: 5px"><img src="'.esc_url($social_1_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_2_icon) ) {
                $socials .= '<a href="'.esc_url($social_2_link).'" style="margin-right: 5px"><img src="'.esc_url($social_2_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_3_icon) ) {
                $socials .= '<a href="'.esc_url($social_3_link).'" style="margin-right: 5px"><img src="'.esc_url($social_3_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_4_icon) ) {
                $socials .= '<a href="'.esc_url($social_4_link).'" style="margin-right: 5px"><img src="'.esc_url($social_4_icon).'" width="" height="" alt=""> </a>';
            }

            $socials .= '</div>';
        }

        if( $enable_email_header != 0 ) {
            $email_content = '<div style="text-align: center; background-color: ' . esc_attr($email_head_bg_color) . '; padding: 16px 0;">
                            <img src="' . esc_url($email_head_logo) . '" alt="logo">
                        </div>';
        }

        $email_content .= '<div style="background-color: #F6F6F6; padding: 30px;">
                            <div style="margin: 0 auto; width: 620px; background-color: #fff;border:1px solid #eee; padding:30px;">
                                <div style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:100%;line-height:1.6em;display:block;max-width:600px;margin:0 auto;padding:0">
                                '.$message.'
                                </div>
                            </div>
                        </div>';

        if( $enable_email_footer != 0 ) {
            $email_content .= '<div style="padding-top: text-align:center; 30px; padding-bottom: 30px; font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;">

                            <div style="width: 640px; background-color: ' . $email_foot_bg_color . '; margin: 0 auto;">
                                ' . $email_footer_content . '
                            </div>
                            ' . $socials . '
                        </div>';
        }

        if( $enable_html_emails != 0 ) {
            $email_messages = $email_content;
        } else {
            $email_messages = $message;
        }

        @wp_mail(
            $user_email,
            $subject,
            $email_messages,
            $headers
        );
    };
endif;


if( !function_exists('houzez_email_to_admin') ) {
    function houzez_email_to_admin($email_type) {
        $admin_email = get_option('admin_email');

        if ($email_type == 'email_upgrade') {
            $args = array();
            houzez_email_type( $admin_email, 'featured_submission', $args );
        } else {
            $args = array();
            houzez_email_type( $admin_email, 'paid_submission', $args );
        }
    }
}


add_action( 'wp_ajax_nopriv_houzez_contact_realtor', 'houzez_contact_realtor' );
add_action( 'wp_ajax_houzez_contact_realtor', 'houzez_contact_realtor' );
if( !function_exists( 'houzez_contact_realtor' ) ) {
    function houzez_contact_realtor() {

        $hide_form_fields = houzez_option('hide_agency_agent_contact_form_fields');

        $nonce = $_POST['contact_realtor_ajax'];
        if (!wp_verify_nonce( $nonce, 'contact_realtor_nonce') ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Unverified Nonce!', 'houzez')
            ));
            wp_die();
        }

        $sender_phone = isset($_POST['mobile']) ? sanitize_text_field( $_POST['mobile'] ) : '';
        $user_type = isset($_POST['user_type']) ? sanitize_text_field( $_POST['user_type'] ) : '';
        $agent_type = isset($_POST['agent_type']) ? sanitize_text_field( $_POST['agent_type'] ) : '';
        $user_type = houzez_get_form_user_type($user_type); 

        $target_email = sanitize_email($_POST['target_email']);
        $target_email = is_email($target_email);
        if (!$target_email) {
            echo json_encode(array(
                'success' => false,
                'msg' => sprintf( esc_html__('%s Target Email address is not properly configured!', 'houzez'), $target_email )
            ));
            wp_die();
        }


        $sender_name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        if ( empty($sender_name) && $hide_form_fields['name'] != 1 ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Name field is empty!', 'houzez')
            ));
            wp_die();
        }

        $sender_email = sanitize_email($_POST['email']);
        $sender_email = is_email($sender_email);
        if (!$sender_email) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Provided Email address is invalid!', 'houzez')
            ));
            wp_die();
        }

        $sender_msg = isset($_POST['message']) ? $_POST['message'] : '';
        if ( empty($sender_msg) && $hide_form_fields['message'] != 1 ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Your message empty!', 'houzez')
            ));
            wp_die();
        }

        
        if( houzez_option('gdpr_and_terms_checkbox', 1) ) {
            $privacy_policy = $_POST['privacy_policy'];
            if ( empty($privacy_policy) ) {
                echo json_encode(array(
                    'success' => false,
                    'msg' => houzez_option('agent_forms_terms_validation')
                ));
                wp_die();
            }
        }
        

        houzez_google_recaptcha_callback();

        $email_subject = sprintf( esc_html__('New message sent by %s using contact form at %s', 'houzez'), $sender_name, get_bloginfo('name') );

        $email_body = esc_html__("You have received a message from: ", 'houzez') . $sender_name . " <br/>";
        if (!empty($sender_phone)) {
            $email_body .= esc_html__("Phone Number : ", 'houzez') . $sender_phone . " <br/>";
        }
        if (!empty($user_type)) {
            $email_body .= esc_html__("User Type : ", 'houzez') . $user_type . " <br/>";
        }
        $email_body .= esc_html__("Additional message is as follows.", 'houzez') . " <br/>";
        $email_body .= wp_kses_post( wpautop( wptexturize( $sender_msg ) ) ) . " <br/>";
        $email_body .= sprintf( esc_html__( 'You can contact %s via email %s', 'houzez'), $sender_name, $sender_email );

        $headers = array();
        $headers[] = "From: $sender_name <$sender_email>";
        $headers[] = "Reply-To: $sender_name <$sender_email>";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        $headers = apply_filters( "houzez_realtors_mail_header", $headers );// Filter for modify the header in child theme

        if (wp_mail( $target_email, $email_subject, $email_body, $headers)) {
            echo json_encode( array(
                'success' => true,
                'msg' => esc_html__("Message Sent Successfully!", 'houzez')
            ));

            if( houzez_option('webhook_agency_contact') == 1 && $agent_type == "agency_info" ) {
                houzez_webhook_post( $_POST, 'houzez_agency_profile_contact_from' );

            } elseif( ( houzez_option('webhook_agent_contact') == 1 ) && ( $agent_type == "agent_info" || $agent_type == "author_info" ) ) {
                houzez_webhook_post( $_POST, 'houzez_agent_profile_contact_from' );
            }

        } else {
            echo json_encode(array(
                    'success' => false,
                    'msg' => esc_html__("Server Error: Make sure Email function working on your server!", 'houzez')
                )
            );
        }

        $activity_args = array(
            'type' => 'lead_agent',
            'name' => $sender_name,
            'email' => $sender_email,
            'phone' => $sender_phone,
            'user_type' => $user_type,
            'message' => $sender_msg,
        );
        do_action('houzez_record_activities', $activity_args);

        do_action('houzez_after_agent_form_submission');
        
        wp_die();
    }
}

add_action( 'wp_ajax_nopriv_houzez_ele_contact_form', 'houzez_ele_contact_form' );
add_action( 'wp_ajax_houzez_ele_contact_form', 'houzez_ele_contact_form' );
if( !function_exists( 'houzez_ele_contact_form' ) ) {
    function houzez_ele_contact_form() {

        $email_to = sanitize_text_field( $_POST['email_to'] );
        $email_subject = sanitize_text_field( $_POST['email_subject'] );
        $email_subject = stripslashes($email_subject);
        
        $email_to_cc = isset($_POST['email_to_cc']) ? sanitize_text_field( $_POST['email_to_cc']) : ''; 


        $form_id = isset($_POST['form_id']) ? sanitize_text_field( $_POST['form_id'] ) : '';
        $full_name = isset($_POST['name']) ? sanitize_text_field( $_POST['name'] ) : '';
        $first_name = isset($_POST['first_name']) ? sanitize_text_field( $_POST['first_name'] ) : '';
        $last_name = isset($_POST['last_name']) ? sanitize_text_field( $_POST['last_name'] ) : '';
        $mobile = isset($_POST['mobile']) ? sanitize_text_field( $_POST['mobile'] ) : '';
        $work_phone = isset($_POST['work_phone']) ? sanitize_text_field( $_POST['work_phone'] ) : '';
        $home_phone = isset($_POST['home_phone']) ? sanitize_text_field( $_POST['home_phone'] ) : '';
        $user_type = isset($_POST['user_type']) ? sanitize_text_field( $_POST['user_type'] ) : ''; 
        $address = isset($_POST['address']) ? sanitize_text_field( $_POST['address'] ) : ''; 
        $country = isset($_POST['country']) ? sanitize_text_field( $_POST['country'] ) : ''; 
        $city = isset($_POST['city']) ? sanitize_text_field( $_POST['city'] ) : ''; 
        $state = isset($_POST['state']) ? sanitize_text_field( $_POST['state'] ) : ''; 
        $zip = isset($_POST['zip']) ? sanitize_text_field( $_POST['zip'] ) : ''; 
        $gdpr_agreement = isset($_POST['gdpr_agreement']) ? sanitize_text_field( $_POST['gdpr_agreement'] ) : ''; 
        $redirect_to = isset($_POST['redirect_to']) ? esc_url( $_POST['redirect_to'] ) : ''; 

        if (!$email_to) {
            echo json_encode(array(
                'success' => false,
                'msg' => sprintf( esc_html__('%s Target Email address is not properly configured!', 'houzez'), $target_email )
            ));
            wp_die();
        }

        $sender_name = isset($_POST['name']) ? sanitize_text_field( $_POST['name'] ) : '';
        $sender_email = sanitize_email($_POST['email']);
        $sender_msg = isset($_POST['message']) ? stripslashes( $_POST['message'] ) : '';
        $email_reply_to = is_email($sender_email);

        if( empty($sender_name) ) {
            $first_name = isset($_POST['first_name']) ? sanitize_text_field( $_POST['first_name'] ) : '';
            $last_name = isset($_POST['last_name']) ? sanitize_text_field( $_POST['last_name'] ) : '';
            $sender_name = $first_name.' '.$last_name;
        }

        houzez_google_recaptcha_callback();

        $headers = sprintf( 'From: %s <%s>' . "\r\n", $sender_name, $sender_email );
        $headers .= sprintf( 'Reply-To: %s' . "\r\n", $email_reply_to );
        $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";

        $email_body = '';

        if (!empty($full_name)) {
            $email_body .= esc_html__("Full Name : ", 'houzez') . $full_name . " <br/>";
        }

        if (!empty($first_name)) {
            $email_body .= esc_html__("First Name : ", 'houzez') . $first_name . " <br/>";
        }

        if (!empty($last_name)) {
            $email_body .= esc_html__("Last Name : ", 'houzez') . $last_name . " <br/>";
        }

        if (!empty($sender_email)) {
            $email_body .= esc_html__("Email : ", 'houzez') . $sender_email . " <br/>";
        }

        if (!empty($mobile)) {
            $email_body .= esc_html__("Mobile : ", 'houzez') . $mobile . " <br/>";
        }

        if (!empty($home_phone)) {
            $email_body .= esc_html__("Phone Number : ", 'houzez') . $home_phone . " <br/>";
        }

        if (!empty($work_phone)) {
            $email_body .= esc_html__("Work Phone : ", 'houzez') . $work_phone . " <br/>";
        }

        if (!empty($address)) {
            $email_body .= esc_html__("Address : ", 'houzez') . $address . " <br/>";
        }

        if (!empty($country)) {
            $email_body .= esc_html__("Country : ", 'houzez') . $country . " <br/>";
        }

        if (!empty($state)) {
            $email_body .= esc_html__("State : ", 'houzez') . $state . " <br/>";
        }

        if (!empty($city)) {
            $email_body .= esc_html__("City : ", 'houzez') . $city . " <br/>";
        }

        if (!empty($zip)) {
            $email_body .= esc_html__("Zip/Postal Code : ", 'houzez') . $zip . " <br/>";
        }

        if (!empty($user_type)) {
            $email_body .= esc_html__("User Type : ", 'houzez') . $user_type . " <br/>";
        }
            
        if( !empty($sender_msg) ) {
            $email_body .= '<br/><br/>'.esc_html__("Message:", 'houzez');
            $email_body .= wp_kses_post( wpautop( wptexturize( $sender_msg ) ) ) . " <br/><br/>";
        }

        if( !empty($gdpr_agreement) ) {
            
            $email_body .= sprintf( esc_html__('GDPR accepted on: %s at %s', 'houzez'),  houzez_get_date(), houzez_get_time() );
             
        }
        


        $cc_header = '';
        if ( ! empty( $email_to_cc ) ) {
            $cc_header = 'Cc: ' . $email_to_cc . "\r\n";
        }

        $email_sent = wp_mail( $email_to, $email_subject, $email_body, $headers . $cc_header );

        if ( ! empty( $_POST['email_to_bcc'] ) ) {
            $bcc_emails = explode( ',', $_POST['email_to_bcc'] );
            foreach ( $bcc_emails as $bcc_email ) {
                wp_mail( trim( $bcc_email ), $email_subject, $email_body, $headers );
            }
        }

        if ($email_sent) {
            echo json_encode( array(
                'success' => true,
                'redirect_to' => $redirect_to,
                'msg' => esc_html__("Message Sent Successfully!", 'houzez')
            ));

            $webhook = isset( $_POST['webhook'] ) ? $_POST['webhook'] : '';
            $webhook_url = isset( $_POST['webhook_url'] ) ? $_POST['webhook_url'] : '';

            if( $webhook == "true" && !empty($webhook_url) ) {
                houzez_webhook_post_for_inquiry_contact_widget( $webhook_url, $_POST, 'contact_form_'.$form_id );
            }

        } else {
            echo json_encode(array(
                    'success' => false,
                    'redirect_to' => '',
                    'msg' => esc_html__("Server Error: Make sure Email function working on your server!", 'houzez')
                )
            );
        }

        do_action('houzez_after_contact_form_submission');

        $activity_args = array(
            'type' => 'lead_contact',
            'name' => $sender_name,
            'email' => $sender_email,
            'phone' => $mobile,
            'user_type' => $user_type,
            'lead_page_id' => isset($_POST['lead_page_id']) ? $_POST['lead_page_id'] : '',
            'message' => $sender_msg,
        );
        do_action('houzez_record_activities', $activity_args);
        
        wp_die();
    }
}

add_action( 'wp_ajax_nopriv_houzez_ele_inquiry_form', 'houzez_ele_inquiry_form' );
add_action( 'wp_ajax_houzez_ele_inquiry_form', 'houzez_ele_inquiry_form' );
if( !function_exists( 'houzez_ele_inquiry_form' ) ) {
    function houzez_ele_inquiry_form() {

        $email_to = sanitize_text_field( $_POST['email_to'] );
        $email_subject = sanitize_text_field( $_POST['email_subject'] );
        $email_subject = stripslashes($email_subject);
        
        $email_to_cc = isset($_POST['email_to_cc']) ? sanitize_text_field( $_POST['email_to_cc']) : ''; 
        $enquiry_type = isset($_POST['enquiry_type']) ? sanitize_text_field( $_POST['enquiry_type']) : ''; 

        $form_id = isset($_POST['form_id']) ? sanitize_text_field( $_POST['form_id'] ) : '';
        $meta = isset($_POST['e_meta']) ? $_POST['e_meta'] : '';
        $beds = isset($meta['beds']) ? $meta['beds'] : '';
        $baths = isset($meta['baths']) ? $meta['baths'] : '';
        $price = isset($meta['price']) ? $meta['price'] : '';
        $area_size = isset($meta['area-size']) ? $meta['area-size'] : '';
        $zipcode = isset($meta['zipcode']) ? $meta['zipcode'] : '';

        $property_type = isset($meta['property_type']) ? $meta['property_type'] : '';
        $country = isset($meta['country']) ? $meta['country'] : '';
        $state = isset($meta['state']) ? $meta['state'] : '';
        $city = isset($meta['city']) ? $meta['city'] : '';
        $area = isset($meta['area']) ? $meta['area'] : '';
        $gdpr_agreement = isset($_POST['gdpr_agreement']) ? sanitize_text_field( $_POST['gdpr_agreement'] ) : '';

        $redirect_to = isset($_POST['redirect_to']) ? esc_url( $_POST['redirect_to'] ) : '';

        $sender_phone = isset($_POST['mobile']) ? sanitize_text_field( $_POST['mobile'] ) : '';
        $user_type = isset($_POST['user_type']) ? sanitize_text_field( $_POST['user_type'] ) : ''; 

        if (!$email_to) {
            echo json_encode(array(
                'success' => false,
                'msg' => sprintf( esc_html__('%s Target Email address is not properly configured!', 'houzez'), $target_email )
            ));
            wp_die();
        }

        $sender_name = isset($_POST['name']) ? sanitize_text_field( $_POST['name'] ) : '';
        $first_name = isset($_POST['first_name']) ? sanitize_text_field( $_POST['first_name'] ) : '';
        $last_name = isset($_POST['last_name']) ? sanitize_text_field( $_POST['last_name'] ) : '';

        $sender_email = sanitize_email($_POST['email']);
        $sender_msg = isset($_POST['message']) ? stripslashes( $_POST['message'] ) : '';
        $email_reply_to = is_email($sender_email);

        if( empty($sender_name) ) {
            
            $sender_name = $first_name.' '.$last_name;
        }

        $dashboard_crm = houzez_get_template_link_2('template/user_dashboard_crm.php');
        $crm_enquiries = add_query_arg( 'hpage', 'enquiries', $dashboard_crm );

        houzez_google_recaptcha_callback();

        $headers = sprintf( 'From: %s <%s>' . "\r\n", $sender_name, $sender_email );
        $headers .= sprintf( 'Reply-To: %s' . "\r\n", $email_reply_to );
        $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";

        $email_body = esc_html__("You have received new lead from: ", 'houzez') . $sender_name . " <br/><br/>";

        if (!empty($user_type)) {
            $email_body .= esc_html__("User Type : ", 'houzez') . $user_type . " <br/>";
        }
        
        if (!empty($sender_name)) {
            $email_body .= esc_html__("Full Name : ", 'houzez') . $sender_name . " <br/>";
        }

        if (!empty($first_name)) {
            $email_body .= esc_html__("First Name : ", 'houzez') . $first_name . " <br/>";
        }

        if (!empty($last_name)) {
            $email_body .= esc_html__("Last Name : ", 'houzez') . $last_name . " <br/>";
        }

        if (!empty($sender_email)) {
            $email_body .= esc_html__("Email : ", 'houzez') . $sender_email . " <br/>";
        }

        if (!empty($sender_phone)) {
            $email_body .= esc_html__("Phone Number : ", 'houzez') . $sender_phone . " <br/>";
        }

        if (!empty($enquiry_type)) {
            $email_body .= esc_html__("Inquiry Type : ", 'houzez') . $enquiry_type . " <br/>";
        }
        if (!empty($property_type)) {
            $email_body .= esc_html__("Property Type : ", 'houzez') . $property_type . " <br/>";
        }

        if (!empty($price)) {
            $email_body .= esc_html__("Price : ", 'houzez') . $price . " <br/>";
        }

        if (!empty($beds)) {
            $email_body .= esc_html__("Beds : ", 'houzez') . $beds . " <br/>";
        }

        if (!empty($baths)) {
            $email_body .= esc_html__("Baths : ", 'houzez') . $baths . " <br/>";
        }

        if (!empty($area_size)) {
            $email_body .= esc_html__("Area Size : ", 'houzez') . $area_size . " <br/>";
        }

        if (!empty($country)) {
            $email_body .= esc_html__("Country : ", 'houzez') . $country . " <br/>";
        }

        if (!empty($state)) {
            $email_body .= esc_html__("State : ", 'houzez') . $state . " <br/>";
        }

        if (!empty($city)) {
            $email_body .= esc_html__("City : ", 'houzez') . $city . " <br/>";
        }

        if (!empty($area)) {
            $email_body .= esc_html__("Area : ", 'houzez') . $area . " <br/>";
        }

        if (!empty($zipcode)) {
            $email_body .= esc_html__("Zip/Postal Code : ", 'houzez') . $zipcode . " <br/>";
        }


        if( !empty($sender_msg) ) {
            $email_body .= '<br/><br/>'.esc_html__("Message:", 'houzez');
            $email_body .= wp_kses_post( wpautop( wptexturize( $sender_msg ) ) ) . " <br/>";
        }

        $email_body .= sprintf( esc_html__( 'You can see more details here %s', 'houzez'), $crm_enquiries ). " <br/>";

        if( !empty($gdpr_agreement) ) {
            
            $email_body .= sprintf( esc_html__('GDPR accepted on: %s at %s', 'houzez'),  houzez_get_date(), houzez_get_time() );
             
        }


        $cc_header = '';
        if ( ! empty( $email_to_cc ) ) {
            $cc_header = 'Cc: ' . $email_to_cc . "\r\n";
        }
        $email_body=cuerpo_correo_enquire();
        $email_sent = wp_mail( $email_to, $email_subject, $email_body, $headers . $cc_header );

        if ( ! empty( $_POST['email_to_bcc'] ) ) {
            $bcc_emails = explode( ',', $_POST['email_to_bcc'] );
            foreach ( $bcc_emails as $bcc_email ) {
                wp_mail( trim( $bcc_email ), $email_subject, $email_body, $headers );
            }
        }

        if ($email_sent) {
            echo json_encode( array(
                'success' => true,
                'redirect_to' => $redirect_to,
                'msg' => esc_html__("Message Sent Successfully!", 'houzez')
            ));

            $webhook = isset( $_POST['webhook'] ) ? $_POST['webhook'] : '';
            $webhook_url = isset( $_POST['webhook_url'] ) ? $_POST['webhook_url'] : '';

            if( $webhook == "true" && !empty($webhook_url) ) {
                houzez_webhook_post_for_inquiry_contact_widget( $webhook_url, $_POST, 'contact_form_'.$form_id );
            }

        } else {
            echo json_encode(array(
                    'success' => false,
                    'redirect_to' => '',
                    'msg' => esc_html__("Server Error: Make sure Email function working on your server!", 'houzez')
                )
            );
        }

        do_action('houzez_after_estimation_form_submission');

        $activity_args = array(
            'type' => 'lead_contact',
            'name' => $sender_name,
            'email' => $sender_email,
            'phone' => $sender_phone,
            'user_type' => $user_type,
            'lead_page_id' => isset($_POST['lead_page_id']) ? $_POST['lead_page_id'] : '',
            'message' => $sender_msg,
        );
        do_action('houzez_record_activities', $activity_args);
        
        wp_die();
    }

    function cuerpo_correo_enquire(){
        $html='<!DOCTYPE html>

        <html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
        
        <head>
            <title></title>
            <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
            <meta content="width=device-width, initial-scale=1.0" name="viewport" />
            <!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
            <!--[if !mso]><!-->
            <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css" />
            <link href="https://fonts.googleapis.com/css?family=Arvo" rel="stylesheet" type="text/css" />
            <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css" />
            <!--<![endif]-->
            <style>
                * {
                    box-sizing: border-box;
                }
        
                body {
                    margin: 0;
                    padding: 0;
                }
        
                a[x-apple-data-detectors] {
                    color: inherit !important;
                    text-decoration: inherit !important;
                }
        
                #MessageViewBody a {
                    color: inherit;
                    text-decoration: none;
                }
        
                p {
                    line-height: inherit
                }
        
                @media (max-width:705px) {
                    .icons-inner {
                        text-align: center;
                    }
        
                    .icons-inner td {
                        margin: 0 auto;
                    }
        
                    .row-content {
                        width: 100% !important;
                    }
        
                    .image_block img.big {
                        width: auto !important;
                    }
        
                    .column .border {
                        display: none;
                    }
        
                    .stack .column {
                        width: 100%;
                        display: block;
                    }
        
                    .row-4 .column-1 {
                        border-top: 20px solid #F7F7F7;
                        border-right: 20px solid #F7F7F7;
                        border-bottom: 20px solid #F7F7F7;
                        border-left: 20px solid #F7F7F7;
                    }
                }
            </style>
        </head>
        
        <body style="background-color: #04093a; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
            <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation"
                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #04093a;" width="100%">
                <tbody>
                    <tr>
                        <td>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1"
                                role="presentation"
                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-position: center top;"
                                width="100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                                class="row-content stack" role="presentation"
                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 685px;"
                                                width="685">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                            width="100%">
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                class="icons_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="vertical-align: middle; color: #000000; font-family: inherit; font-size: 14px; text-align: center; padding-top: 60px;">
                                                                        <table align="center" cellpadding="0" cellspacing="0"
                                                                            role="presentation"
                                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                            <tr>
                                                                                <td
                                                                                    style="vertical-align: middle; text-align: center; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px;">
                                                                                    <img align="center" alt="" class="icon"
                                                                                        height="64"
                                                                                        src="images/ebebe134-ba83-4f73-af6b-2bbe9ed7e1a9.png"
                                                                                        style="display: block; height: auto; margin: 0 auto; border: 0;"
                                                                                        width="71" /></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                class="image_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="width:100%;padding-right:0px;padding-left:0px;padding-top:40px;">
                                                                        <div align="center" style="line-height:10px"><img
                                                                                alt="Ukraine support header" class="big"
                                                                                src="images/f4cdbdde-caa3-41ab-b7e0-fea10ac4a086.png"
                                                                                style="display: block; height: auto; border: 0; width: 685px; max-width: 100%;"
                                                                                title="Ukraine support header" width="685" />
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                class="heading_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:70px;padding-left:20px;padding-right:20px;padding-top:70px;text-align:center;width:100%;">
                                                                        <h1
                                                                            style="margin: 0; color: #055db9; direction: ltr; font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; font-size: 46px; font-weight: 700; letter-spacing: 2px; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;">
                                                                            <strong>WE STAND<br />TOGETHER
                                                                                FOR<br />UKRAINE<br /></strong></h1>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2"
                                role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                                class="row-content stack" role="presentation"
                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f7f7f7; color: #000000; width: 685px;"
                                                width="685">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                            width="100%">
                                                            <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                                                role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:30px;padding-left:30px;padding-right:30px;padding-top:80px;">
                                                                        <div style="font-family: Tahoma, Verdana, sans-serif">
                                                                            <div
                                                                                style="font-size: 12px; mso-line-height-alt: 18px; color: #04093a; line-height: 1.5; font-family: \'Lato\', Tahoma, Verdana, Segoe, sans-serif;">
                                                                                <p style="margin: 0; font-size: 12px;">
                                                                                    <strong>Nombre: </strong>{full_name}</p>
                                                                                <p style="margin: 0; font-size: 12px;">
                                                                                    <strong>Email: </strong>{Email}</p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                class="image_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td style="width:100%;padding-right:0px;padding-left:0px;">
                                                                        <div align="center" style="line-height:10px"><img
                                                                                alt="Young man holding a sign against the war"
                                                                                src="images/image_1.png"
                                                                                style="display: block; height: auto; border: 0; width: 308px; max-width: 100%;"
                                                                                title="Young man holding a sign against the war"
                                                                                width="308" /></div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="30" cellspacing="0"
                                                                class="heading_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td>
                                                                        <h1
                                                                            style="margin: 0; color: #16006d; direction: ltr; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; font-size: 23px; font-weight: 700; letter-spacing: normal; line-height: 150%; text-align: center; margin-top: 0; margin-bottom: 0;">
                                                                            <strong>Join us to ensure that every penny raised
                                                                                will go towards helping families in
                                                                                need.</strong></h1>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-3"
                                role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                                class="row-content stack" role="presentation"
                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f7f7f7; color: #000000; width: 685px;"
                                                width="685">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                            width="100%">
                                                            <table border="0" cellpadding="10" cellspacing="0"
                                                                class="button_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td>
                                                                        <div align="center">
                                                                            <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://www.example.com/" style="height:44px;width:226px;v-text-anchor:middle;" arcsize="5%" strokeweight="0.75pt" strokecolor="#04093A" fill="false"><w:anchorlock/><v:textbox inset="0px,0px,0px,0px"><center style="color:#04093a; font-family:Tahoma, sans-serif; font-size:16px"><![endif]--><a
                                                                                href="https://www.example.com/"
                                                                                style="text-decoration:none;display:inline-block;color:#04093a;background-color:transparent;border-radius:2px;width:auto;border-top:1px solid #04093A;border-right:1px solid #04093A;border-bottom:1px solid #04093A;border-left:1px solid #04093A;padding-top:5px;padding-bottom:5px;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"
                                                                                target="_blank"><span
                                                                                    style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;letter-spacing:2px;"><span
                                                                                        style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">START
                                                                                        HELPING NOW</span></span></a>
                                                                            <!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                class="heading_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:30px;padding-left:30px;padding-right:30px;padding-top:60px;text-align:center;width:100%;">
                                                                        <h1
                                                                            style="margin: 0; color: #16006d; direction: ltr; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; font-size: 23px; font-weight: 700; letter-spacing: normal; line-height: 150%; text-align: center; margin-top: 0; margin-bottom: 0;">
                                                                            <strong>Help us reach our goal</strong></h1>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                                                role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:30px;padding-left:30px;padding-right:30px;padding-top:20px;">
                                                                        <div style="font-family: Tahoma, Verdana, sans-serif">
                                                                            <div
                                                                                style="font-size: 14px; font-family: \'Lato\', Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 21px; color: #04093a; line-height: 1.5;">
                                                                                <p
                                                                                    style="margin: 0; font-size: 18px; mso-line-height-alt: 27px;">
                                                                                    <span style="font-size:18px;">Your donation
                                                                                        will help us and our humanitarian
                                                                                        partners operating in Ukraine through
                                                                                        the Ukraine Humanitarian Fund - one of
                                                                                        the quickest and most effective ways to
                                                                                        directly support urgent humanitarian
                                                                                        relief on the ground.</span></p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                class="heading_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-left:20px;padding-right:20px;text-align:center;width:100%;">
                                                                        <h1
                                                                            style="margin: 0; color: #055db9; direction: ltr; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; font-size: 30px; font-weight: 700; letter-spacing: 2px; line-height: 150%; text-align: center; margin-top: 0; margin-bottom: 0;">
                                                                            <strong>$5,374</strong></h1>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4"
                                role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                                class="row-content stack" role="presentation"
                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #04093a; color: #000000; width: 685px;"
                                                width="685">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;"
                                                            width="100%">
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td class="border" colspan="3"
                                                                        style="font-size:7px;height:20px;background-color:#F7F7F7"
                                                                        width="100%"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="border"
                                                                        style="width:20px;background-color:#F7F7F7"> </td>
                                                                    <td class="content_blocks"
                                                                        style="padding-top:0px;padding-bottom:0px;width:645px;">
                                                                        <table border="0" cellpadding="0" cellspacing="0"
                                                                            class="divider_block" role="presentation"
                                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                            width="100%">
                                                                            <tr>
                                                                                <td>
                                                                                    <table border="0" cellpadding="0"
                                                                                        cellspacing="0" role="presentation"
                                                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                                        width="75%">
                                                                                        <tr>
                                                                                            <td class="divider_inner"
                                                                                                style="font-size: 1px; line-height: 1px; border-top: 7px solid #055DB9;">
                                                                                                <span> </span></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                    <td class="border"
                                                                        style="width:20px;background-color:#F7F7F7"> </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="border" colspan="3"
                                                                        style="font-size:7px;height:20px;background-color:#F7F7F7"
                                                                        width="100%"></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-5"
                                role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                                class="row-content stack" role="presentation"
                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f7f7f7; color: #000000; width: 685px;"
                                                width="685">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                            width="100%">
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                class="button_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:70px;padding-left:10px;padding-right:10px;padding-top:10px;text-align:center;">
                                                                        <div align="center">
                                                                            <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://www.example.com/" style="height:44px;width:160px;v-text-anchor:middle;" arcsize="5%" strokeweight="0.75pt" strokecolor="#04093A" fill="false"><w:anchorlock/><v:textbox inset="0px,0px,0px,0px"><center style="color:#04093a; font-family:Tahoma, sans-serif; font-size:16px"><![endif]--><a
                                                                                href="https://www.example.com/"
                                                                                style="text-decoration:none;display:inline-block;color:#04093a;background-color:transparent;border-radius:2px;width:auto;border-top:1px solid #04093A;border-right:1px solid #04093A;border-bottom:1px solid #04093A;border-left:1px solid #04093A;padding-top:5px;padding-bottom:5px;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"
                                                                                target="_blank"><span
                                                                                    style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;letter-spacing:2px;"><span
                                                                                        style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">DONATE
                                                                                        NOW</span></span></a>
                                                                            <!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-6"
                                role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                                class="row-content stack" role="presentation"
                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 685px;"
                                                width="685">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 15px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                            width="100%">
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                class="heading_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="text-align:center;width:100%;padding-top:60px;padding-bottom:30px;">
                                                                        <h1
                                                                            style="margin: 0; color: #fed000; direction: ltr; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; font-size: 30px; font-weight: 700; letter-spacing: 2px; line-height: 150%; text-align: center; margin-top: 0; margin-bottom: 0;">
                                                                            <strong>HOW TO PARTICIPATE</strong></h1>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-7"
                                role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                                class="row-content stack" role="presentation"
                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 685px;"
                                                width="685">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                            width="33.333333333333336%">
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                class="image_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-left:5px;padding-right:5px;width:100%;padding-top:5px;">
                                                                        <div style="line-height:10px"><img alt="Donate icon"
                                                                                src="images/illustration_3.png"
                                                                                style="display: block; height: auto; border: 0; width: 206px; max-width: 100%;"
                                                                                title="Donate icon" width="206" /></div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                                                role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:10px;padding-left:20px;padding-right:20px;padding-top:40px;">
                                                                        <div style="font-family: Tahoma, Verdana, sans-serif">
                                                                            <div
                                                                                style="font-size: 14px; font-family: \'Lato\', Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 16.8px; color: #ffffff; line-height: 1.2;">
                                                                                <p
                                                                                    style="margin: 0; font-size: 14px; text-align: left; letter-spacing: 4px;">
                                                                                    <span style="font-size:18px;">DONATE TO
                                                                                    </span></p>
                                                                                <p
                                                                                    style="margin: 0; font-size: 14px; text-align: left; letter-spacing: 4px;">
                                                                                    <span style="font-size:18px;">RED
                                                                                        CROSS</span></p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                                                role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:15px;padding-left:20px;padding-right:20px;padding-top:10px;">
                                                                        <div style="font-family: sans-serif">
                                                                            <div
                                                                                style="font-size: 14px; mso-line-height-alt: 21px; color: #ffffff; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
                                                                                <p
                                                                                    style="margin: 0; font-size: 14px; text-align: left;">
                                                                                    Lorem ipsum dolor sit amet, consetetur
                                                                                    sadipscing elitr.</p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td class="column column-2"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                            width="33.333333333333336%">
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                class="image_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-left:5px;padding-right:5px;width:100%;padding-top:5px;">
                                                                        <div style="line-height:10px"><img alt="Help icon"
                                                                                src="images/illustration_2.png"
                                                                                style="display: block; height: auto; border: 0; width: 206px; max-width: 100%;"
                                                                                title="Help icon" width="206" /></div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                                                role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:10px;padding-left:20px;padding-right:20px;padding-top:40px;">
                                                                        <div style="font-family: Tahoma, Verdana, sans-serif">
                                                                            <div
                                                                                style="font-size: 14px; font-family: \'Lato\', Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 16.8px; color: #ffffff; line-height: 1.2;">
                                                                                <p
                                                                                    style="margin: 0; font-size: 14px; text-align: left; letter-spacing: 4px;">
                                                                                    <span style="font-size:18px;">HELP
                                                                                        THEIR</span></p>
                                                                                <p
                                                                                    style="margin: 0; font-size: 14px; text-align: left; letter-spacing: 4px;">
                                                                                    <span
                                                                                        style="font-size:18px;">RELATIVES</span>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                                                role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:15px;padding-left:20px;padding-right:20px;padding-top:10px;">
                                                                        <div style="font-family: sans-serif">
                                                                            <div
                                                                                style="font-size: 14px; mso-line-height-alt: 21px; color: #ffffff; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
                                                                                <p
                                                                                    style="margin: 0; font-size: 14px; text-align: left;">
                                                                                    Lorem ipsum dolor sit amet, consetetur
                                                                                    sadipscing elitr.</p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td class="column column-3"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                            width="33.333333333333336%">
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                class="image_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-left:5px;padding-right:5px;width:100%;padding-top:5px;">
                                                                        <div style="line-height:10px"><img alt="Peace icon"
                                                                                src="images/illustration_1.png"
                                                                                style="display: block; height: auto; border: 0; width: 206px; max-width: 100%;"
                                                                                title="Peace icon" width="206" /></div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                                                role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:10px;padding-left:20px;padding-right:20px;padding-top:40px;">
                                                                        <div style="font-family: Tahoma, Verdana, sans-serif">
                                                                            <div
                                                                                style="font-size: 14px; font-family: \'Lato\', Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 16.8px; color: #ffffff; line-height: 1.2;">
                                                                                <p
                                                                                    style="margin: 0; font-size: 14px; text-align: left; letter-spacing: 4px;">
                                                                                    <span style="font-size:18px;">SPREAD
                                                                                        THE</span></p>
                                                                                <p
                                                                                    style="margin: 0; font-size: 14px; text-align: left; letter-spacing: 4px;">
                                                                                    <span
                                                                                        style="font-size:18px;">MESSAGE<br /></span>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                                                role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:15px;padding-left:20px;padding-right:20px;padding-top:10px;">
                                                                        <div style="font-family: sans-serif">
                                                                            <div
                                                                                style="font-size: 14px; mso-line-height-alt: 21px; color: #ffffff; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
                                                                                <p
                                                                                    style="margin: 0; font-size: 14px; text-align: left;">
                                                                                    Lorem ipsum dolor sit amet, consetetur
                                                                                    sadipscing elitr.</p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-8"
                                role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                                class="row-content stack" role="presentation"
                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 685px;"
                                                width="685">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 15px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                            width="100%">
                                                            <div class="spacer_block"
                                                                style="height:60px;line-height:60px;font-size:1px;"> </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-9"
                                role="presentation"
                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fed000;" width="100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                                class="row-content stack" role="presentation"
                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 685px;"
                                                width="685">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                            width="100%">
                                                            <div class="spacer_block"
                                                                style="height:10px;line-height:10px;font-size:1px;"> </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-10"
                                role="presentation"
                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #055db9;" width="100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                                class="row-content stack" role="presentation"
                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 685px;"
                                                width="685">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                            width="100%">
                                                            <div class="spacer_block"
                                                                style="height:10px;line-height:10px;font-size:1px;"> </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-11"
                                role="presentation"
                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f7f7f7;" width="100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                                class="row-content stack" role="presentation"
                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 685px;"
                                                width="685">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                            width="50%">
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                class="image_block" role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:20px;padding-left:20px;padding-right:20px;padding-top:45px;width:100%;">
                                                                        <div style="line-height:10px"><a href="www.example.com"
                                                                                style="outline:none" tabindex="-1"
                                                                                target="_blank"><img alt="Your logo"
                                                                                    src="https://www.whooking.com/wp-content/uploads/2022/03/Brand_1.png"
                                                                                    style="display: block; height: auto; border: 0; width: 171px; max-width: 100%;"
                                                                                    title="Your logo" width="171" /></a></div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td class="column column-2"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                            width="50%">
                                                            <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                                                role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:5px;padding-left:10px;padding-right:10px;padding-top:70px;">
                                                                        <div style="font-family: sans-serif">
                                                                            <div
                                                                                style="font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #888888; line-height: 1.2;">
                                                                                <p
                                                                                    style="margin: 0; font-size: 14px; text-align: center;">
                                                                                    <span style="font-size:12px;">© 2022 Your
                                                                                        Company Name | 110 River St. City,
                                                                                        State, Country 999999</span></p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                                                role="presentation"
                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                                width="100%">
                                                                <tr>
                                                                    <td
                                                                        style="padding-bottom:55px;padding-left:10px;padding-right:10px;padding-top:5px;">
                                                                        <div style="font-family: sans-serif">
                                                                            <div
                                                                                style="font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #888888; line-height: 1.2;">
                                                                                <p
                                                                                    style="margin: 0; font-size: 14px; text-align: center;">
                                                                                    <span style="font-size:12px;">If you prefer
                                                                                        not to receive marketing emails form
                                                                                        this list, <a
                                                                                            href="http://www.example.com"
                                                                                            rel="noopener"
                                                                                            style="text-decoration: underline; color: #0086e1;"
                                                                                            target="_blank">click here to
                                                                                            unsubscribe</a>.</span></p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        </td>
                    </tr>
                </tbody>
            </table><!-- End -->
        </body>
        
        </html>';
        return $html;
    }
}

add_action( 'wp_ajax_nopriv_houzez_property_agent_contact', 'houzez_property_agent_contact' );
add_action( 'wp_ajax_houzez_property_agent_contact', 'houzez_property_agent_contact' );

if( !function_exists('houzez_property_agent_contact') ) {
    function houzez_property_agent_contact() {

        $agent_forms_terms = houzez_option('agent_forms_terms');
        $hide_form_fields = houzez_option('hide_prop_contact_form_fields');

        $nonce = $_POST['property_agent_contact_security'];
        if (!wp_verify_nonce( $nonce, 'property_agent_contact_nonce') ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Invalid Nonce!', 'houzez')
            ));
            wp_die();
        }

        $property_id = isset($_POST['property_id']) ? sanitize_text_field( $_POST['property_id'] ) : '';
        $sender_phone = isset($_POST['mobile']) ? sanitize_text_field( $_POST['mobile'] ) : '';
        $property_link = esc_url( $_POST['property_permalink'] );
        $property_title = sanitize_text_field( $_POST['property_title'] );

        $user_type = isset($_POST['user_type']) ? sanitize_text_field( $_POST['user_type'] ) : '';
        $user_type = houzez_get_form_user_type($user_type);

        $target_email = $_POST['target_email'];
        if ( !is_array( $target_email ) ) {
            $target_email = is_email($target_email);
        }
        if (!$target_email) {
            echo json_encode(array(
                'success' => false,
                'msg' => sprintf( esc_html__('%s Email address is not configured!', 'houzez'), $target_email )
            ));
            wp_die();
        }

        $sender_name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        if ( empty($sender_name) && $hide_form_fields['name'] != 1 ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Name field is empty!', 'houzez')
            ));
            wp_die();
        }

        
        if ( empty($sender_phone) && $hide_form_fields['phone'] != 1 ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Phone field is empty!', 'houzez')
            ));
            wp_die();
        }

        $sender_email = sanitize_email($_POST['email']);
        $sender_email = is_email($sender_email);
        if (!$sender_email) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Invalid email address!', 'houzez')
            ));
            wp_die();
        }

        $sender_msg = stripslashes( $_POST['message'] );
        if ( empty($sender_msg) && $hide_form_fields['message'] != 1 ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Your message is empty!', 'houzez')
            ));
            wp_die();
        }

        
        if( houzez_option('gdpr_and_terms_checkbox', 1) ) {
            $privacy_policy = $_POST['privacy_policy'];
            if ( empty($privacy_policy) ) {
                echo json_encode(array(
                    'success' => false,
                    'msg' => houzez_option('agent_forms_terms_validation')
                ));
                wp_die();
            }
        }
        

        houzez_google_recaptcha_callback();

        $cc_email = '';
        $bcc_email = '';
        $send_message_copy = houzez_option('send_agent_message_copy');
        if( $send_message_copy == '1' ){
            $cc_email = houzez_option( 'send_agent_message_email' );
        }

        $args = array(
            'sender_name' => $sender_name, 
            'sender_email' => $sender_email, 
            'sender_phone' => $sender_phone, 
            'property_title' => $property_title, 
            'property_link' => $property_link, 
            'property_id' => $property_id, 
            'user_type' => $user_type, 
            'sender_message' => $sender_msg, 
        );

        $email_sent = houzez_email_with_reply( $target_email, 'property_agent_contact', $args, $sender_name, $sender_email, $cc_email, $bcc_email);


        if ( $email_sent ) {

            if( houzez_option('webhook_property_agent_contact') == 1 ) {
                houzez_webhook_post( $_POST, 'houzez_property_agent_contact_form' );
            } 

            echo json_encode( array(
                'success' => true,
                'msg' => esc_html__("Email Sent Successfully!", 'houzez')
            ));
        } else {
            echo json_encode(array(
                    'success' => false,
                    'msg' => esc_html__("Server Error: Make sure Email function working on your server!", 'houzez')
                )
            );
        }

        $activity_args = array(
            'type' => 'lead',
            'name' => $sender_name,
            'email' => $sender_email,
            'phone' => $sender_phone,
            'user_type' => $user_type,
            'message' => $sender_msg,
        );
        do_action('houzez_record_activities', $activity_args);

        do_action('houzez_after_agent_form_submission');
        

        wp_die();

    }
}

add_action( 'wp_ajax_nopriv_houzez_schedule_send_message', 'houzez_schedule_send_message' );
add_action( 'wp_ajax_houzez_schedule_send_message', 'houzez_schedule_send_message' );

if( !function_exists('houzez_schedule_send_message') ) {
    function houzez_schedule_send_message() {

        $agent_forms_terms = houzez_option('agent_forms_terms');

        $nonce = $_POST['schedule_contact_form_ajax'];
        if (!wp_verify_nonce( $nonce, 'schedule-contact-form-nonce') ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Invalid Nonce!', 'houzez')
            ));
            wp_die();
        }

        $sender_phone = sanitize_text_field( $_POST['phone'] );
        $property_link = esc_url( $_POST['property_permalink'] );
        $property_title = sanitize_text_field( $_POST['property_title'] );

        $target_email = $_POST['target_email'];
        if ( !is_array( $target_email ) ) {
            $target_email = is_email($target_email);
        }
        if (!$target_email) {
            echo json_encode(array(
                'success' => false,
                'msg' => sprintf( esc_html__('%s Email address is not configured!', 'houzez'), $target_email )
            ));
            wp_die();
        }

        $sender_name = sanitize_text_field($_POST['name']);
        if ( empty($sender_name) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Name field is empty!', 'houzez')
            ));
            wp_die();
        }

        $schedule_tour_type = sanitize_text_field($_POST['schedule_tour_type']);

        if ( empty($sender_phone) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Phone field is empty!', 'houzez')
            ));
            wp_die();
        }

        $sender_email = sanitize_email($_POST['email']);
        $sender_email = is_email($sender_email);
        if (!$sender_email) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Invalid email address!', 'houzez')
            ));
            wp_die();
        }

        $sender_msg = wp_kses_post( $_POST['message'] );
        if ( empty($sender_msg) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Your message is empty!', 'houzez')
            ));
            wp_die();
        }

        $schedule_date = wp_kses_post( $_POST['schedule_date'] );
        if ( empty($schedule_date) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Date field is empty!', 'houzez')
            ));
            wp_die();
        }

        $schedule_time = wp_kses_post( $_POST['schedule_time'] );
        if ( empty($schedule_time) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Time field is empty!', 'houzez')
            ));
            wp_die();
        }

        
        if( houzez_option('gdpr_and_terms_checkbox', 1) ) {
            $privacy_policy = $_POST['privacy_policy'];
            if ( empty($privacy_policy) ) {
                echo json_encode(array(
                    'success' => false,
                    'msg' => houzez_option('agent_forms_terms_validation')
                ));
                wp_die();
            }
        }

        $cc_email = '';
        $bcc_email = '';
        $send_message_copy = houzez_option('send_agent_message_copy');
        if( $send_message_copy == '1' ){
            $cc_email = houzez_option( 'send_agent_message_email' );
        }

        $args = array(
            'sender_name' => $sender_name, 
            'sender_email' => $sender_email, 
            'sender_phone' => $sender_phone, 
            'property_title' => $property_title, 
            'property_link' => $property_link, 
            'schedule_date' => $schedule_date, 
            'schedule_time' => $schedule_time, 
            'schedule_tour_type' => $schedule_tour_type, 
            'sender_message' => $sender_msg, 
        );

        $email_sent = houzez_email_with_reply( $target_email, 'property_schedule_tour', $args, $sender_name, $sender_email, $cc_email, $bcc_email);


        if ( $email_sent ) {
            echo json_encode( array(
                'success' => true,
                'msg' => esc_html__("Email Sent Successfully!", 'houzez')
            ));
        } else {
            echo json_encode(array(
                    'success' => false,
                    'msg' => esc_html__("Server Error: Make sure Email function working on your server!", 'houzez')
                )
            );
        }

        $activity_args = array(
            'type' => 'lead',
            'subtype' => 'schedule_tour',
            'name' => $sender_name,
            'email' => $sender_email,
            'phone' => $sender_phone,
            'message' => $sender_msg,
            'schedule_tour_type' => $schedule_tour_type,
            'schedule_date' => $schedule_date,
            'schedule_time' => $schedule_time,
        );
        do_action('houzez_record_activities', $activity_args);

        do_action('houzez_after_agent_form_submission');

        wp_die();

    }
}

if(!function_exists('houzez_show_google_reCaptcha')) {
    function houzez_show_google_reCaptcha() {
        $enable_reCaptcha = houzez_option('enable_reCaptcha');
        $recaptha_site_key = houzez_option('recaptha_site_key');
        $recaptha_secret_key = houzez_option('recaptha_secret_key');

        if( $enable_reCaptcha != 0 && !empty($recaptha_site_key) && !empty($recaptha_secret_key) ) {
            return true;
        }
        return false;

    }
}

if (!function_exists( 'houzez_generator_recaptcha_callback')) {
    
    function houzez_generator_recaptcha_callback() {
        if ( houzez_show_google_reCaptcha() ) {
            $reCAPTCHA_site_key = houzez_option( 'recaptha_site_key' );
            $recaptha_type = houzez_option( 'recaptha_type', 'v2' );
            ?>
            <script type="text/javascript">
                var reCaptchaIDs = [];
                var siteKey = '<?php echo $reCAPTCHA_site_key; ?>';
                var reCaptchaType = '<?php echo $recaptha_type; ?>';

                var houzezReCaptchaLoad = function() {
                    jQuery( '.houzez_google_reCaptcha' ).each( function( index, el ) {
                        var tempID;

                        if ( reCaptchaType === 'v3' ) {

                            tempID = grecaptcha.ready(function () {
                                grecaptcha.execute(siteKey, {action: 'homepage'}).then(function (token) {
                                    el.insertAdjacentHTML('beforeend', '<input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response" value="' + token + '">');
                                });
                            });

                        } else {

                        tempID = grecaptcha.render( el, {
                                'sitekey' : siteKey
                            } );
                        }

                        reCaptchaIDs.push( tempID );
                    } );
                };

                //reCAPTCHA reset
                var houzezReCaptchaReset = function() {
                    if ( reCaptchaType === 'v2' ) {
                        if( typeof reCaptchaIDs != 'undefined' ) {
                            var arrayLength = reCaptchaIDs.length;
                            for( var i = 0; i < arrayLength; i++ ) {
                                grecaptcha.reset( reCaptchaIDs[i] );
                            }
                        }
                    } else {
                        houzezReCaptchaLoad();
                    }
                };
            </script>
            <?php
        }
    }

    add_action( 'wp_footer', 'houzez_generator_recaptcha_callback' );
}

/*
 * Google reCaptcha filter
 * */
if(!function_exists('houzez_google_recaptcha_callback')) {
    function houzez_google_recaptcha_callback() {

        $recaptha_site_key = houzez_option('recaptha_site_key');
        $recaptha_secret_key = houzez_option('recaptha_secret_key');
        $enable_reCaptcha = houzez_option('enable_reCaptcha');

        if( $enable_reCaptcha != 1 || ( empty($recaptha_site_key) || empty($recaptha_secret_key) ) ) {
            return true;
        }

        // include library https://github.com/google/recaptcha
        include_once(  HOUZEZ_PLUGIN_DIR . 'includes/reCaptcha/autoload.php' );

        // If the form submission includes the "g-captcha-response" field
        // Create an instance of the service using your secret
        //$recaptcha = new \ReCaptcha\ReCaptcha($recaptha_secret_key);
        $recaptcha = new \ReCaptcha\ReCaptcha( $recaptha_secret_key, new \ReCaptcha\RequestMethod\CurlPost() );

        // If file_get_contents() is locked down on your PHP installation to disallow
        // its use with URLs, then you can use the alternative request method instead.
        // This makes use of fsockopen() instead.
        //  $recaptcha = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\SocketPost());

        // Make the call to verify the response and also pass the user's IP address
        $resp = $recaptcha->verify($_POST["g-recaptcha-response"], $_SERVER['REMOTE_ADDR']);


        if ($resp->isSuccess()):
            return true;
        else:

            $error_codes   = $resp->getErrorCodes();

            //Error codes - https://developers.google.com/recaptcha/docs/verify
            $captach_errors  = array(
                'missing-input-secret'   => esc_html__('The secret parameter is missing.', 'houzez'),
                'invalid-input-secret'   => esc_html__('The secret parameter is invalid or malformed.', 'houzez'),
                'missing-input-response' => esc_html__('The response parameter is missing.', 'houzez'),
                'invalid-input-response' => esc_html__('The response parameter is invalid or malformed.', 'houzez'),
                'bad-request' => esc_html__('The request is invalid or malformed.', 'houzez'),
            );
            $error_message = $captach_errors[ $error_codes[ 0 ]];
            echo json_encode( array(
                'success' => false,
                'msg' => esc_html__( 'reCAPTCHA Failed:', 'houzez' ) . ' ' . $error_message
            ) );
            wp_die();
        endif;
    }
}
