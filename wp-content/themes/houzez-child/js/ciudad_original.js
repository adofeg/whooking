/*
 Theme Name: houzez
 Description: houzez
 Author: Adolfo Feria
 Version: 1.1
 */
 jQuery( document ).ready(function() {
    "use strict";


    jQuery("select#form-field-bf4d768").val("");
    jQuery("select#form-field-3c951c3").val("");

    jQuery('select#form-field-3c951c3').change(function() {
        let attribute = jQuery(this).val();
        //jQuery("select#form-field-bf4d768 option[data-belong!="+attribute+"]").hide();
        //jQuery("select#form-field-bf4d768 option[data-belong="+attribute+"]").show();
        jQuery("select#form-field-bf4d768 option[data-belong!="+attribute+"]").addClass('hide');
        jQuery("select#form-field-bf4d768 option[data-belong="+attribute+"]").addClass('show');
    });
    


});
jQuery.noConflict();