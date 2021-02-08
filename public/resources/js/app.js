var App = {
    hasInited: false,
    stickyNavHeight: 55,
    // animate functions triggers scroll, use this flag variable to semi-prevent it
    animateScrollInProgress: false,
    
    ///////////////////////////////////////////////////////
    init: function() {
        if (App.hasInited) {
            return;
        }
        App.bind();
        App.detectScroll();
        App.scrollSidebarToCurrentPoem();
    },
    
    ///////////////////////////////////////////////////////
    bind: function() {
        $(window).scroll(App.detectScroll);
        $('.search-submit').on('click', App.showSearchField);
        $('.nav-toggler-wrapper').on('click', App.toggleNavigation);
        $('nav li.has-items > a').on('click', App.toggleSubNavigation);
        $('.aside-toggler').on('click', App.togglePanel);
        $('#scroll-top').on('click', App.scrollToTop);
        $('aside:not("#no-ajax") a').on('click', App.loadPoemDynamicallyOnClick);
        $(window).on('popstate', App.loadPoemDynamicallyOnPopstate);
    },
    
    ///////////////////////////////////////////////////////////////////////////
    detectScroll: function() {
        App.toggleStickyNavigation();
        App.toggleScrollToTopButton();
    },

    ///////////////////////////////////////////////////////////////////////////
    toggleStickyNavigation: function() {
        var posY = $(window).scrollTop();

        (posY > 45) ? $('body').addClass('sticky')
                    : $('body').removeClass('sticky');
    },

    ///////////////////////////////////////////////////////////////////////////
    toggleScrollToTopButton: function() {
        if ( ! $('.content-wrapper').length || App.animateScrollInProgress) {
            return;
        }

        var poemWrapperTop    = $('.content-wrapper')[0].getBoundingClientRect().top - App.stickyNavHeight,
            footerTop         = $('footer')[0].getBoundingClientRect().top,
            viewportHeight    = $(window).height(),
            thresholdY        = -200, // arbitrary
            $scrollTopBtn     = $('#scroll-top');

        // if poem top is 200px scrolled down
        // or the footer is about to be reached,
        // show the scroll-top button
        (poemWrapperTop < thresholdY)
            ? $scrollTopBtn.fadeIn()
            : $scrollTopBtn.fadeOut();

        // when the footer is almost reached on mobile,
        // shift button upwards to avoid footer overlap
        (App.isMobile() && (footerTop + 20) < viewportHeight)
            ? $scrollTopBtn.addClass('overlap-footer')
            : $scrollTopBtn.removeClass('overlap-footer');
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

    ///////////////////////////////////////////////////////////////////////////
    scrollToTop: function() {
        // subtract the sticky header height to get visible top
        var poemContainerY = $('#container').offset().top - App.stickyNavHeight;

        // set flag to true to avoid animate from firing the toggleScrollToTopButton function
        App.animateScrollInProgress = true;

        // once the animation is finished, reset the flag and retire the arrow
        $('html, body').animate({ scrollTop: poemContainerY}, 500, function() {
            App.animateScrollInProgress = false;
            $('#scroll-top').fadeOut();
        });

    },

    ///////////////////////////////////////////////////////////////////////////
    // to improve UX, poems should be browsed between using AJAX requests
    loadPoemDynamicallyOnClick: function(e) {
        e.preventDefault();

        // don't refetch current poem
        if ($(this).hasClass('active')) {
            return false;
        }

        App.fadeOutPoemWrapper(App.initPoemAjax, $(this).attr('href'));
    },

    ///////////////////////////////////////////////////////////////////////////
    // onclick calls pushState which simulates a page reload,
    // but that doesn't apply to navigation buttons;
    // a separate popstate eventlistener is needed
    loadPoemDynamicallyOnPopstate: function(e) {
        // the ajax response was previously passed to pushState as a «state» property
        // there's no need for a separate AJAX request on popstate
        App.fadeOutPoemWrapper(App.updatePoem, e.originalEvent.state);
    },

    ///////////////////////////////////////////////////////////////////////////
    // first fade out the wrapper, then call the respective function with its params
    fadeOutPoemWrapper: function(callbackFunction, callbackFunctionParams) {
        $('#container').animate({opacity:0}, 150, function() {
            callbackFunction(callbackFunctionParams);
        });
    },

    ///////////////////////////////////////////////////////////////////////////
    fadeInPoemWrapper: function() {
        $('#container').animate({opacity: 1}, 150);
    },

    ///////////////////////////////////////////////////////////////////////////
    initPoemAjax: function(url) {
        return $.ajax({
            url:      url,
            type:     'GET',
            dataType: 'JSON',

            // save the url as a part of the response
            // to use it later in the popstate event
            success: function(response) {
                response.url = url;

                App.updatePoem(response);

                // change the URL, but save the response
                history.pushState(response, document.title, url);
            },

            // if the request fails, simply redirect the user to the page
            error: function () {
                window.location = url;
            }
        })
    },

    ///////////////////////////////////////////////////////////////////////////
    updatePoem: function(response) {
        if (response == null) {
            return;
        }

        var $title      = $('#title'),
            $dedication = $('#dedication'),
            $container  = $('#container'),
            $poemBody   = $('#body'),
            $sidebar    = $('aside');

        // if whitespaces are crucial to the poem,
        // add monospace class to the container
        response.monospace ? $container.addClass('monospace') : $container.removeClass('monospace');

        // replace the title
        $title.html(response.title);

        // update the dedication
        $dedication.html(response.dedication);
        response.dedication ? $dedication.show() : $dedication.hide();

        // update the poem body and mark it as poem or story
        $poemBody.html(response.body);

        // replace the metatitle of the document
        document.title = response.metaTitle;

        // remove active class from previously selected poem
        $sidebar.find('a.active').removeClass('active');

        // add active class to current poem
        $sidebar.find('a[href="'+response.url+'"]').addClass('active');

        // fade back in the container to make for a smooth transition
        App.fadeInPoemWrapper();

        // on mobile scroll to top of poem
        if (App.isMobile()) {
            App.scrollToTop();
        }

        // on desktop show arrow to scroll to top if container is scrolled
        else {
            var containerTop = $container[0].getBoundingClientRect().top;

            if ((containerTop - App.stickyNavHeight) < 0) {
                $('#scroll-top').fadeIn();
            }
        }
    },
}

$(document).ready(function() {
    App.init();
});