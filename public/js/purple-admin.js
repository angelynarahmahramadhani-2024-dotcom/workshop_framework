/**
 * Purple Admin Template JavaScript
 * Based on BootstrapDash Purple Admin Free Template
 */

(function($) {
    'use strict';
    
    $(function() {
        // Toggle sidebar
        $('[data-toggle="minimize"]').on("click", function() {
            $('body').toggleClass('sidebar-icon-only');
        });

        // Sidebar toggle for mobile
        $('[data-toggle="offcanvas"]').on("click", function() {
            $('.sidebar').toggleClass('active');
        });

        // Close sidebar when clicking outside on mobile
        $(document).on('click', function(e) {
            if ($(window).width() < 992) {
                var sidebar = $('.sidebar');
                var toggler = $('[data-toggle="offcanvas"]');
                
                if (!sidebar.is(e.target) && 
                    sidebar.has(e.target).length === 0 && 
                    !toggler.is(e.target) && 
                    toggler.has(e.target).length === 0) {
                    sidebar.removeClass('active');
                }
            }
        });

        // Add active class to current sidebar item
        var current = location.pathname;
        $('.sidebar .nav .nav-item a').each(function() {
            var $this = $(this);
            if ($this.attr('href').indexOf(current) !== -1) {
                $this.parents('.nav-item').addClass('active');
            }
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });

        // Auto-dismiss alerts
        setTimeout(function() {
            $('.alert:not(.alert-persistent)').fadeOut('slow');
        }, 5000);

        // Form validation styles
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Focus on first invalid input
        $('form').on('submit', function() {
            var firstInvalid = $(this).find(':invalid').first();
            if (firstInvalid.length) {
                firstInvalid.focus();
            }
        });

        // Scroll to top button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('#scroll-to-top').fadeIn();
            } else {
                $('#scroll-to-top').fadeOut();
            }
        });

        $('#scroll-to-top').click(function() {
            $('html, body').animate({ scrollTop: 0 }, 500);
            return false;
        });

        // Checkbox toggle all
        $('[data-check="all"]').on('change', function() {
            var checked = $(this).is(':checked');
            $($(this).data('target')).prop('checked', checked);
        });

        // Confirm delete
        $('[data-confirm]').on('click', function(e) {
            if (!confirm($(this).data('confirm'))) {
                e.preventDefault();
            }
        });

        // File input preview
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        });

        // DataTable default config (if DataTables is loaded)
        if (typeof $.fn.DataTable !== 'undefined') {
            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                    paginate: {
                        previous: '<i class="mdi mdi-chevron-left"></i>',
                        next: '<i class="mdi mdi-chevron-right"></i>'
                    }
                }
            });
        }
    });

})(jQuery);

/**
 * Chart helper functions
 */
var ChartHelper = {
    // Get gradient for chart backgrounds
    getGradient: function(ctx, color1, color2) {
        var gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    },

    // Common chart options
    lineChartOptions: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                grid: {
                    borderDash: [3, 3]
                }
            }
        }
    },

    doughnutChartOptions: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        cutout: '70%'
    },

    barChartOptions: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                grid: {
                    borderDash: [3, 3]
                }
            }
        }
    }
};
