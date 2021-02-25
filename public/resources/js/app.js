var App = {
    hasInited: false,
    stickyNavHeight: 55,
    // animate functions triggers scroll, use this flag variable to semi-prevent it
    animateScrollInProgress: false,
    swiperObject: null,
    isAjaxInProgress: false,
    genericErrorMessage: 'Възникна грешка. Моля, опитайте по-късно.',
    
    ///////////////////////////////////////////////////////
    init: function() {
        if (App.hasInited) {
            return;
        }
        App.bind();
        App.detectScroll();
        App.scrollSidebarToCurrentPoem();
        App.initGallerySwiper();
        App.initMagnificPopup();
    },

    ///////////////////////////////////////////////////////
    bind: function() {
        $(window).scroll(App.detectScroll);
        $(document).keyup(App.onKeyUp);
        $('.search-submit').on('click', App.showSearchField);
        $('.filter .show').on('click', App.showFilterField);
        $('.filter .clear').on('click', App.clearFilterField);
        $('#filter-field').on("input propertychange", App.filterPoems);
        $('.nav-toggler-wrapper').on('click', App.toggleNavigation);
        $('nav li.has-items > a').on('click', App.toggleSubNavigation);
        $('.aside-toggler').on('click', App.togglePanel);
        $('#scroll-top').on('click', App.scrollToTop);
        $('aside:not("#no-ajax") a').on('click', App.loadPoemDynamicallyOnClick);
        $(window).on('popstate', App.loadPoemDynamicallyOnPopstate);
        $('.thumb').on('click', App.jumpToImage);
        $('.swipe-nav').on('click', App.navigateGallery);
        $(document).on('submit', '.ajax-form', App.submitFormAjax); // using document because of ajax loaded poems
        $(document).on('click', '.captcha', App.generateNewCaptcha);
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
        (App.isMobile() && (footerTop - 20) < viewportHeight)
            ? $scrollTopBtn.addClass('overlap-footer')
            : $scrollTopBtn.removeClass('overlap-footer');
    },
    
    ///////////////////////////////////////////////////////////////////////////
    onKeyUp: function(e) {
        if (e.key === 'Escape') {

            // if the search field is visible, blur it and hide it
            if ($('.search-field').is(':focus')) {
                
                // show logo span if it's not visible
                if ( ! $('.logo-wrapper span').is(':visible')) {
                    $('.logo-wrapper span').animate({width:'toggle'}, 350);
                }

                $('.search-field').blur().animate({width:'toggle'}, 350, function() {
                    // remove style attribute to fix border radius of submit button
                    $(this).removeClass('open').removeAttr('style');
                });
            }

            // if the filter field is visible,
            // reset it and hide it
            else if ($('#filter-field').is(':visible')) {
                $('#filter-field').val('').trigger('input');
                $('.filter button').trigger('click');
            }
        }
    },

    ///////////////////////////////////////////////////////////////////////////
    showSearchField: function(e) {
        // if the field is not visible, show it and prevent default action
        // otherwise just submit the form
        var $field = $('.search-field');

        if ( ! $field.is(':visible') || $field.val() === '') {
            e.preventDefault();
            $('.logo-wrapper span').animate({width:'toggle'}, 350);

            // if the field gets shown, focus it; otherwise remove style attribute
            $field.toggleClass('open').animate({width:'toggle'}, 350, function() {
                $(this).hasClass('open') ? $(this).focus() : $(this).removeAttr('style');
            });
        }

    },

    ///////////////////////////////////////////////////////////////////////////
    showFilterField: function(e) {
        e.preventDefault();

        var $field  = $('#filter-field'),
            $filter = $field.parents('.filter');

        $('.clear').fadeToggle();
        
        $field.animate({width:'toggle'}, 350, function() {
            $filter.toggleClass('open');
        });

        // class open is toggled only after the field animation has finished
        // therefore condition to focus/reset the field is reversed
        ( ! $filter.hasClass('open'))
            ? $field.focus()
            : $field.val('').blur().trigger('input');

    },

    ///////////////////////////////////////////////////////////////////////////
    clearFilterField: function(e) {
        e.preventDefault();

        $('#filter-field').val('').trigger('input').focus();
    },

    ///////////////////////////////////////////////////////////////////////////
    filterPoems: function(e) {
        var value   = $(this).val().toLowerCase(),
            $target = $('.aside-wrapper ol li');

        $target.filter(function() {
            var $link = $(this).find('a'),
                // keep only if title or data-dedication attribute matches
                match =    ($link.text().toLowerCase().indexOf(value) > -1)
                        || ($link.attr('data-dedication').toLowerCase().indexOf(value) > -1);

            $(this).toggle(match);
        });
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
        // strip host part from current URL
        var href = (window.location.href).replace(window.location.origin, '');

        // the ajax response was previously passed to pushState as a «state» property
        // however, if there's no state property, initiate AJAX request
        e.originalEvent.state
            ? App.fadeOutPoemWrapper(App.updatePoem, e.originalEvent.state)
            : App.fadeOutPoemWrapper(App.initPoemAjax, href);
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
            $comments   = $('.comments-wrapper'),
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

        // update comments – remove old comments, then append new ones (if any) 
        $comments.remove();
        if (response.comments) {
            $('.content-wrapper').append(response.comments);

            // update comment action url
            $('#comment-form').attr('action', response.url);
        }

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

    ///////////////////////////////////////////////////////////////////////////
    initGallerySwiper: function() {
        var element = $('#swipe-gallery')[0];
        
        App.swiperObject = new Swipe(element, {
            auto: 0,
            draggable: true,
            disableScroll: true,
            stopPropagation: true,
            callback: function(index, element) {
                // set respective thumbnail as active
                $('.thumb').removeClass('active');
                $('#thumb-' + index).addClass('active');

                // update the current image indicator
                $('#current-image').html(index+1);

                // lazy load images by copying data-src to src attribute
                var $img = $(element).find('img');
                $img.attr('src', $img.attr('data-src'));
            }
        });
    },

    ///////////////////////////////////////////////////////////////////////////
    jumpToImage: function(e) {
        e.preventDefault();
        var id = $(this).attr('id').replace('thumb-', '');

        App.swiperObject.slide(id);
    },

    ///////////////////////////////////////////////////////////////////////////
    navigateGallery: function(e) {
        e.preventDefault();

        $(this).hasClass('next') ?  App.swiperObject.next() : App.swiperObject.prev();
    },

    ///////////////////////////////////////////////////////////////////////////
    fadeObjectsOnLoad: function() {
        $('.op-0-fadein').animate({opacity: 1}, 150);
    },

    ///////////////////////////////////////////////////////////////////////////
    submitFormAjax: function(e) {
        e.preventDefault();

        // don't send new requests before finishing already started requests
        if (App.isAjaxInProgress) {
            return false;
        }

        var $form = $(this),
            url   = $form.attr('action'),
            type  = $form.attr('method'),
            data  = $form.serialize();

        // mark current request as «in progress»
        App.isAjaxInProgress = true;

        // reset all previous errors on submit
        $('.error-field').removeClass('error-field');
        $('.success-message').slideUp('fast');
        $('.error-message').fadeOut('fast');

        return $.ajax({
            url:      url,
            type:     type,
            data:     data,
            dataType: 'JSON',

            success: function(response) {

                // if the status is false, there were errors – show them
                if ( ! response.status) {

                    // if there's a general error, it has higher priority; don't show other errors
                    if ('general' in response.errors) {
                        $('.error-message').html(response.errors.general[0]).addClass('center').fadeIn();
                    }

                    // otherwise mark respective fields as erroneous
                    // and show all errors above the submit button
                    else {
                        // all error messages are stored in an array which then gets joined
                        var errorMsgs = [];
                        $.each(response.errors, function(field, errors) {
                            $('#' + field).addClass('error-field');
                            errorMsgs.push(errors);
                        });

                        // show all error messages on new lines
                        $('.error-message').html(errorMsgs.join('<br />')).removeClass('center').fadeIn();
                    }
                }

                // if the status was true, the request was successful
                else {
                    // show success message
                    $('.error-message').hide();
                    $('.success-message').html(response.success_msg).fadeIn();

                    // if the request was for new comment,
                    // reset form and display new comment
                    if (response.type === 'comment') {
                        App.resetFormFields($('.comment-form'));
                        App.showNewlyGeneratedComment(response.html);
                    }

                    // if the request was for new contact message,
                    // keep the form and reset all its fields
                    else if (response.type === 'contact_message') {
                        App.resetFormFields($('.contact-form'));
                    }

                    // reset captcha
                    $('.captcha').trigger('click');
                }
            },

            // on server error show a generic message (the error was logged)
            error: function() {
                $('.error-message').html(App.genericErrorMessage).addClass('center').fadeIn();
            }
        }).always(function() {
            App.isAjaxInProgress = false;
        });
    },

    ///////////////////////////////////////////////////////////////////////////
    showNewlyGeneratedComment: function(html) {
        var $commentSection  = $('.comments'),
            isSectionVisible = $commentSection.is(':visible'),
            counter          = $('.comment').length + 1,
            $comment         = $(html);

        // update the comment counter which is 0 by default on AJAX load
        $comment.find('.counter').html(counter);

        // if other comments are present, mark the new one as hidden to fade it in afterwards
        if (isSectionVisible) {
            $comment.hide();
        }

        // add comment to section
        $commentSection.append($comment)

        // fade in the new comment or the whole section based on section visibility
        isSectionVisible ? $comment.slideDown(2000) : $commentSection.slideDown(2000);
    },

    ///////////////////////////////////////////////////////////////////////////
    resetFormFields: function($form) {
        $form.find('input[type="text"], textarea').each(function() {
            $(this).val('');
        });
    },

    ///////////////////////////////////////////////////////
    initMagnificPopup: function() {
        $('.content-wrapper').magnificPopup({
            delegate:            'img:not(.no-zoom)',
            type:                'image',
            gallery:             {enabled: false},
            closeOnContentClick: true,
            closeBtnInside:      false,
            fixedContentPos:     true,
            image:               { verticalFit: true, titleSrc: 'title' },
            zoom: {
                enabled: true,
                duration: 300
            },
            // resize image if there's caption so that it all fits inside viewport
            callbacks: {
                elementParse: function(item) { item.src = item.el.attr('src'); },
                resize: function() {
                    var img           = this.content.find('img'),
                        $caption      = this.content.find('.mfp-title'),
                        captionText   = $caption.html(),
                        captionHeight = $caption.height();
                        $wrapper      = this.content.parent();


                    if (captionText !== '') {
                        img.css('max-height', parseFloat(img.css('max-height')) - captionHeight);
                        $wrapper.addClass('has-caption');
                    }
                    else {
                        $wrapper.removeClass('has-caption');
                    }
                }
            }
        });
    },

    ///////////////////////////////////////////////////////
    generateNewCaptcha: function(e) {
        e.preventDefault();
        var $img = $(this);

        return $.ajax({
            url:      root + 'captcha',
            dataType: 'JSON',
            success: function(response) {
                $img.attr('src', response.image);
            }
        });
    },
}

$(document).ready(function() {
    App.init();
});
window.onload = function() {
    App.fadeObjectsOnLoad();
}