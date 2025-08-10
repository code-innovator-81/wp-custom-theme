/**
 * Main JavaScript file for WP Custom Theme
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Initialize mobile menu
        initMobileMenu();
        
        // Initialize smooth scrolling
        initSmoothScrolling();
        
        // Initialize back to top button
        initBackToTop();
        
        // Initialize project filter
        initProjectFilter();
        
        // Initialize lazy loading for images
        initLazyLoading();
    });

    /**
     * Mobile Navigation Menu
     */
    function initMobileMenu() {
        // Create mobile menu toggle button
        if ($('.main-navigation .container').length && !$('.mobile-menu-toggle').length) {
            $('.main-navigation .container').prepend('<button class="mobile-menu-toggle" aria-label="Toggle Navigation"><span></span><span></span><span></span></button>');
        }

        // Handle mobile menu toggle
        $(document).on('click', '.mobile-menu-toggle', function(e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $('#primary-menu').toggleClass('active');
        });

        // Handle submenu toggles on mobile
       $(document).on('click', '.main-navigation .menu-item-has-children > a', function(e) {
            if ($(window).width() <= 768) {
                e.preventDefault();
                $(this).parent().toggleClass('mobile-active');
                $(this).next('ul').slideToggle();
            }
        });

        // Close mobile menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.main-navigation').length) {
                $('.mobile-menu-toggle').removeClass('active');
                $('.main-navigation ul').removeClass('mobile-menu-open');
            }
        });

        // Handle window resize
        $(window).resize(function() {
            if ($(window).width() > 768) {
                $('.mobile-menu-toggle').removeClass('active');
                $('.main-navigation ul').removeClass('mobile-menu-open');
                $('.main-navigation ul ul').removeAttr('style');
            }
        });
    }

    /**
     * Smooth Scrolling for Anchor Links
     */
    function initSmoothScrolling() {
        $('a[href*="#"]:not([href="#"])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 1000);
                    return false;
                }
            }
        });
    }

    /**
     * Back to Top Button
     */
    function initBackToTop() {
        // Add back to top button
        if (!$('#back-to-top').length) {
            $('body').append('<button id="back-to-top" title="Back to Top">↑</button>');
        }

        // Show/hide back to top button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });

        // Handle back to top click
        $('#back-to-top').click(function() {
            $('html, body').animate({scrollTop: 0}, 800);
            return false;
        });
    }

    /**
     * Project Filter Functionality
     */
    function initProjectFilter() {
        const filterForm = $('#project-filter-form');
        const projectsGrid = $('#projects-grid');
        const loading = $('#loading');

        if (!filterForm.length) return;

        // Handle form submission
        filterForm.on('submit', function(e) {
            e.preventDefault();
            filterProjects();
        });

        // Handle clear filters
        $('#clear-filters').on('click', function() {
            filterForm[0].reset();
            filterProjects();
        });

        // Auto-filter on input change (with debounce)
        let filterTimeout;
        filterForm.find('input, select').on('change', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(filterProjects, 500);
        });

        function filterProjects() {
            const formData = filterForm.serialize();
            
            // Show loading state
            projectsGrid.addClass('loading');
            loading.show();

            // Make AJAX request
            $.ajax({
                url: wp_custom_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'filter_projects',
                    nonce: wp_custom_ajax.nonce,
                    ...Object.fromEntries(new URLSearchParams(formData))
                },
                success: function(response) {
                    projectsGrid.html(response);
                    projectsGrid.removeClass('loading');
                    loading.hide();
                    
                    // Reinitialize lazy loading for new images
                    initLazyLoading();
                },
                error: function() {
                    projectsGrid.html('<div class="no-projects-found"><h2>Error loading projects</h2><p>Please try again later.</p></div>');
                    projectsGrid.removeClass('loading');
                    loading.hide();
                }
            });
        }
    }

    /**
     * Lazy Loading for Images
     */
    function initLazyLoading() {
        // Simple lazy loading implementation
        const images = $('img[data-src]');
        
        if (images.length === 0) return;

        const imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.each(function() {
            imageObserver.observe(this);
        });
    }

    /**
     * Form Validation
     */
    function validateForm(form) {
        let isValid = true;
        const requiredFields = form.find('[required]');

        requiredFields.each(function() {
            const field = $(this);
            const value = field.val().trim();

            if (!value) {
                field.addClass('error');
                isValid = false;
            } else {
                field.removeClass('error');
            }
        });

        return isValid;
    }

   
    // Throttle function
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }

    // Check if element is in viewport
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Add animation classes when elements come into view
    function initScrollAnimations() {
        const animatedElements = $('.animate-on-scroll');
        
        if (animatedElements.length === 0) return;

        const handleScroll = throttle(function() {
            animatedElements.each(function() {
                if (isInViewport(this) && !$(this).hasClass('animated')) {
                    $(this).addClass('animated');
                }
            });
        }, 100);

        $(window).on('scroll', handleScroll);
        handleScroll(); // Check on load
    }

    // Initialize scroll animations
    initScrollAnimations();

})(jQuery);

