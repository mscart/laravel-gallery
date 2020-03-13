var Settings = function() {

    return {
        init: function() {
            ajaxCSRFToken();
            _formValidate();
            _componentSelect2();
            _componentUniform();

        }
    }

}();

// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    Settings.init();

});
