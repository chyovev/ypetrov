var App = {
    hasInited: false,
    
    ///////////////////////////////////////////////////////
    init: function() {
        if (App.hasInited) {
            return;
        }
        App.bind();
        App.toggleStickyNavigation();
        App.scrollSidebarToCurrentPoem();
    },
    
    ///////////////////////////////////////////////////////
    bind: function() {
        $(window).scroll(App.toggleStickyNavigation);
        $('.search-submit').on('click', App.showSearchField);
        $('.nav-toggler-wrapper').on('click', App.toggleNavigation);
        $('nav li.has-items > a').on('click', App.toggleSubNavigation);
        $('.aside-toggler').on('click', App.togglePanel);
    },
    
    ///////////////////////////////////////////////////////////////////////////
    toggleStickyNavigation: function(e) {
        var posY = $(window).scrollTop();
        
        (posY > 45) ? $('body').addClass('sticky')
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
        $('nav').slideToggle('fast', function() {
            $(this).toggleClass('open').removeAttr('style');
        });
    },

    ///////////////////////////////////////////////////////////////////////////
    // click to toggle only on mobile, desktop uses the hover CSS effect
    toggleSubNavigation: function(e) {
        $subNav = $(this).siblings('ul');
        
        if (App.isMobile() && $subNav.length) {
            e.preventDefault();

            // if the submenu is not visible, slide it down
            if ( ! $subNav.is(':visible')) {
                $subNav.slideDown('fast');
                $(this).parent().toggleClass('open');
            }
            
            // otherwise, slide it up AND remove the style="display: none" attr
            // to guarantee desktop compatibility
            else {
                $subNav.slideUp('fast', function() {
                    $(this).removeAttr('style');
                    $(this).parent().toggleClass('open');
                });
            }
            

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

    ///////////////////////////////////////////////////////////////////////////
    scrollSidebarToCurrentPoem: function() {
        var $sidebar     = $('aside'),
            $currentPoem = $sidebar.find('li a.active');

        // if there's an active poem and we're on mobile,
        // get the Y coordinates of the sidebar and the poem
        // and subtract half the height of the sidebar
        // to land the active poem nice in the middle
        if ($currentPoem.length && App.isMobile()) {
            var sidebarHeight = $sidebar.height(),
                sidebarY      = $sidebar.scrollTop(),
                poemY         = $currentPoem.offset().top
                scrollToY     = sidebarY + poemY - (sidebarHeight / 2);

            $sidebar.scrollTop(scrollToY);
        }
    },
}

$(document).ready(function() {
    App.init();
});