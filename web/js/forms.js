/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    $('tr.error_list').each(function(i){
       $(this).prev('tr').addClass('errors');
    });
    
});

