//mdc.autoInit();
// // var textFields = document.querySelectorAll('.mdc-text-field');
// // const textField = new MDCTextField(document.querySelector('.mdc-text-field'));
// import {MDCTextField} from '@material/textFields';
// mdc.textFields.MDCTextField.attachTo(document.querySelector('.mdc-text-field'));
// // for (var i = 0, tf; tf = textFields[i]; i++) {
// //     mdc.textfield.MDCTextfield.attachTo(tf);
// // }


M.AutoInit();

$(document).ready(function(){

    var toggleButton = document.getElementsByClassName('togglebutton');

    var tiles = document.getElementsByClassName("mdc-grid-tile");


    $(toggleButton).on('click', function() {
        var value = $(this).attr('id');
        $(this).toggleClass('activetoggle');
        if($(this).hasClass('activetoggle')){

            $(tiles).not( '.'+ value +'').addClass('hidetile');
        }else{
            $(tiles).not( '.'+ value +'').removeClass('hidetile');
        }

    });

    $('.sidenav').sidenav();




});


// // **
// // A little fixed Multiple Filter Masonry here
// // https://github.com/digistate/resouces/blob/master/multipleFilterMasonry.js
//
// // Params
// var j$ = jQuery,
//     $mContainer = j$("#recipes_tiles"),
//     $filterButton = j$(".togglebutton"),
//     //$loadingMessage = j$("#loading_msg");
// $params = {
//     itemSelector: ".recipe_card",
//     filtersGroupSelector:".togglebutton_set"
//     // Uncomment below to set the selectorType to use <ul> instead of inputs
//     // selectorType: "list"
// };
//
// // After the page is loaded
// j$(window).load(function() {
//     // Do mansonry with filtering
//     $mContainer.multipleFilterMasonry($params);
//     // Show articles with fadein
//     $mContainer.find("article").animate({
//         "opacity":1
//     }, 1200);
//     // Hide loading message
//     $loadingMessage.fadeOut();
//
//     // Change the filtering button(label) status
//     $filterButton.find("input").change(function(){
//         j$(this).parent().toggleClass("active");
//     });
// });