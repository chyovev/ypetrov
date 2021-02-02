var App = {
    hasInited: false,
    
    ///////////////////////////////////////////////////////
    init: function() {
        if (App.hasInited) {
            return;
        }
        App.bind();
    },
    
    ///////////////////////////////////////////////////////
    bind: function() {
        $(window).scroll(App.toggleStickyNavigation);
        $('.search-submit').on('click', App.showSearchField);
    },
    
    ///////////////////////////////////////////////////////////////////////////
    toggleStickyNavigation: function(e) {
        var posY = $(window).scrollTop();
        
        (posY > 40) ? $('body').addClass('sticky')
                    : $('body').removeClass('sticky');
    },
    
    ///////////////////////////////////////////////////////////////////////////
    showSearchField: function(e) {
        // if the field is not visible, show it and prevent default action
        // otherwise just submit the form
        if ( ! $('.search-field:visible').length) {
            e.preventDefault();
            $('.logo-wrapper span').addClass('hidden');
            $('.search-field').animate({width:'toggle'}, 350).focus();
        }
    }
}


$(document).ready(function() {
    App.init();
});