var AddGallery = function() {



    return {
        // public functions
        init: function() {
            ajaxCSRFToken();
            _componentMultiSelect();
           // AddCategory();
            _componentSelect2();
            //_formValidate();
            _multiValidate();

            _slugify();
        }
    };
}()
jQuery(document).ready(function() {
    AddGallery.init()
});
