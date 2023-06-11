// The jQuery of the "Frontend Section" of the website



/* global $, confirm */
$(function () {
    'use strict';



    // Switching between Login or Signup forms. Check    eCommerce\login.php
    $('.login-page h1 span').click(function () {
        // console.log($(this));


        $(this).addClass('selected').siblings().removeClass('selected'); // Add the .selected CSS class to the clicked <span>, and remove it from the other siblings (the other <span>-s) at the same time (switch adding the .selected CSS class to the Login <span> and Signup <span>)    // This is used for coloring the .selected CSS class <span>. Check front.css file

        $('.login-page form').hide(); // Hide BOTH Login and Signup HTML Forms

        // Show the clicked HTML Form whether the Login or Signup Form
        // console.log($(this)); // Using Custom HTML data-* Attributes    // '.login' or '.signup'
        // console.log($(this).data('class')); // Using Custom HTML data-* Attributes    // '.login' or '.signup'

        // console.log('.' + $(this).data('class'));    // Using Custom HTML data-* Attributes    // '.login' or '.signup'
        // console.log($('.' + $(this).data('class'))); // Using Custom HTML data-* Attributes    // '.login' or '.signup'

        $('.' + $(this).data('class')).fadeIn(100);     // Using Custom HTML data-* Attributes    // '.login' or '.signup'
    });



    // Triggering (firing) the SelectBoxIt Plugin (for all Select Boxes in my application)
    $("select").selectBoxIt({
        autoWidth: false
    });



    // Hiding placeholder upon form focus
    $('[placeholder]').focus(function () {
        
        $(this).attr('data-text', $(this).attr('placeholder')); // storing the placeholder attribute value in a Custom HTML data-* Attribute data-text
        $(this).attr('placeholder', ''); // hiding the placeholder upon focus
        
    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text')); // Or    $(this).attr('placeholder', $(this).data('text'));
    });




    // Form Validation
    // Adding an Asterisk * on the required fields
    $('input').each(function () {
        if ($(this).attr('required') === 'required') { // Or the same code:   if ($(this).prop('required') === true) {
            $(this).after('<span class="asterisk">*</span>');
        }
    });



    // Confirmation message when Delete button in members.php is clicked
    $('.confirm').click(function () {
        return confirm('Are You Sure?');
    });



    // Newad.php Page (Beautiful Live Show)
    /******
    $('.live-name').keyup(function () {
        // console.log($(this).val());
        $('.live-preview h3').text($(this).val());
    });
    $('.live-desc').keyup(function () {
        // console.log($(this).val());
        $('.live-preview p').text($(this).val());
    });
    $('.live-price').keyup(function () {
        // console.log($(this).val());
        $('.live-preview .price-tag').text('$' + $(this).val());
    });
    ******/
    // TO REDUCE THE LAST CODE INTO ONE FUNCTION
    $('.live').keyup(function () {
        // console.log($(this).data('class')); // Here we are printing the classes themselves    // Outputs are .live-name, .live-desc, .live-price
        // console.log($($(this).data('class'))); // Here we are selecting elements not printing the classes themselves as in the previous code line
        $($(this).data('class')).text($(this).val());
    });
    
});