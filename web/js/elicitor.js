/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {



    $('#signin-button').click(function() {
        $('#signin-box').slideToggle(200, function() {
            // Animation complete.
        });
        return false;
    });

//    $(document).click(function() {
//       if($('#signin-box').is(':visible')) {
//           $('#signin-box').slideToggle(200, function() {
//            // Animation complete.
//           });
//       }
//    });

    $('#change-password-link').click(function(){
        $('#change-password').dialog();
    });

});