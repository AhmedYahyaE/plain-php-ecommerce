// The jQuery of the "Admin Panel Section" of the website



/* global $, confirm */
$(function () {
    'use strict';



    // Dashboard Page
    $('.toggle-info').click(function () {
        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);

        if ($(this).hasClass('selected')) {
            $(this).html('<i class="fa fa-minus fa-lg" aria-hidden="true"></i>');
        } else {
            $(this).html('<i class="fa fa-plus fa-lg" aria-hidden="true"></i>');
        }
    });



    // Triggering (firing) the SelectBoxIt Plugin (for all Select Boxes in my application)
    $("select").selectBoxIt({
        autoWidth: false
    });



    // Hiding placeholder upon HTML Form focus event
    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder')); // storing the placeholder attribute value in a custom attribute data-text
        $(this).attr('placeholder', ''); // hiding the placeholder upon focus

    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text')); // Or    $(this).attr('placeholder', $(this).data('text'));
    });



    // Form Validation
    // Adding Asterisk on the required fields
    $('input').each(function () {
        if ($(this).attr('required') === 'required') { // OR the same code:   if ($(this).prop('required') === true) {
            $(this).after('<span class="asterisk">*</span>');
        }
    });



    // Converting Password field to Text field (when the Eye Icon is hovered on to show the entered password) in eCommerce\admin\members.php
    var passField = $('.password'); // the passwrod <input> field
    $('.show-pass').hover(function () { // When hovering over the Eye Icon
        passField.attr('type', 'text'); // When hovering over the eye icon, make the <input> field as    <input type="text">

    }, function () { // when mouse goes away (hovering away) from the Eye Icon
        passField.attr('type', 'password'); // When hovering away from the eye icon, make the <input> field as    <input type="password">
    });



    // Confirmation message when Delete button in members.php and categories.php is clicked
    $('.confirm').click(function () {
        return confirm('Are You Sure?');
    });



    $('.cat h3').click(function () {
        $(this).next('.full-view').fadeToggle(200);
    });



    // Switch Categories View Options (Full/Classic) in admin/categories.php Manage
    $('.option span').click(function () {
        $(this).addClass('active').siblings('span').removeClass('active'); // Add the .active CSS class to the clicked <span>, and remove it from the other <span> siblings (switching)    // The .active CSS class is used to color the <span> with red color using CSS. Check backend.css file


        if ($(this).data('view') === 'full') { // show the .full-view CSS class <div>
            $('.cat .full-view').fadeIn(200); // Show the .full-view CSS class <div>
        } else { // hide the .full-view CSS class <div>
            $('.cat .full-view').fadeOut(200); // Hide the .full-view CSS class <div>
        }
    });



    // Showing Delete button upon hovering over child categories (subcategories) event in admin categories.php
    $('.child-link').hover(function () {
        $(this).find('.show-delete').fadeIn(400); // Show
    }, function () {
        $(this).find('.show-delete').fadeOut(400); // Hide
    });

});