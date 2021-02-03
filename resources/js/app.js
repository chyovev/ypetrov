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
        $('.nav-toggler-wrapper').on('click', App.toggleNavigation);
        $('nav li.has-items > a').on('click', App.toggleSubNavigation);
        $('.aside-toggler').on('click', App.togglePanel)
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
            $('.logo-wrapper span').animate({width:'toggle'}, 350);
            $('.search-field').animate({width:'toggle'}, 350).focus();
        }
    },

    ///////////////////////////////////////////////////////////////////////////
    toggleNavigation: function(e) {
        $('#nav-toggler').toggleClass('active');
        $('nav').slideToggle('normal', function() {
            $(this).toggleClass('open').removeAttr('style');
        });
    },

    ///////////////////////////////////////////////////////////////////////////
    // click to toggle only on mobile, desktop uses the hover CSS effect
    toggleSubNavigation: function(e) {
        if (App.isMobile() && $(this).siblings('ul').length) {
            e.preventDefault();

            // if the submenu is not visible, slide it down
            if ( ! $(this).siblings('ul').is(':visible')) {
                $(this).siblings('ul').slideDown('fast');
            }
            
            // otherwise, slide it up AND remove the style="display: none" attr
            // to guarantee desktop compatibility
            else {
                $(this).siblings('ul').slideUp('fast', function() {
                    $(this).removeAttr('style');
                });
            }
            
            $(this).parent().toggleClass('open');

            return false;
        }
        return true;
    },

    ///////////////////////////////////////////////////////////////////////////
    isMobile: function() {
        return $('.mobile-only:visible').length ? true : false;
    },

    ///////////////////////////////////////////////////////////////////////////
    togglePanel: function() {
        $('.aside-wrapper').toggleClass('open');
        $(this).toggleClass('open');
    },
}


$(document).ready(function() {
    App.init();
    App.toggleStickyNavigation();
});