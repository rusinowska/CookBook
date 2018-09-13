//mdc.autoInit();
// // var textFields = document.querySelectorAll('.mdc-text-field');
// // const textField = new MDCTextField(document.querySelector('.mdc-text-field'));
// import {MDCTextField} from '@material/textFields';
// mdc.textFields.MDCTextField.attachTo(document.querySelector('.mdc-text-field'));
// // for (var i = 0, tf; tf = textFields[i]; i++) {
// //     mdc.textfield.MDCTextfield.attachTo(tf);
// // }

M.AutoInit();

document.addEventListener('DOMContentLoaded', function() {

    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems, options);
});
