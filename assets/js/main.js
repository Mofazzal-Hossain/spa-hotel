(function($){
    $(document).ready(function(){
        new Swiper(".sht-hotels-slider", {
            slidesPerView: 2.4,
            spaceBetween: 32,
            speed: 2000,
            autoplay: {
                delay: 4000,
            },
            loop: true,
            pagination: {
                el: ".sht-hotels-slider .sht-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".sht-hotels-slider .sht-next",
                prevEl: ".sht-hotels-slider .sht-prev",
            },
            breakpoints: {
                1200: {
                    slidesPerView: 2.4,
                },
                1024: {
                    slidesPerView: 2.1,
                },
                991: {
                    slidesPerView: 1.8,
                },
                767: {
                    slidesPerView: 1.5,
                },
                640: {
                    slidesPerView: 1.2,
                },
                480: {
                    slidesPerView: 1.1,
                    spaceBetween: 16,
                },
                0: {
                    slidesPerView: 1,
                    spaceBetween: 16,
                },
            }
        });

         // Full Description Showing
        $('.tf-single-description .tf-see-description').on('click', function () {
            $('.tf-single-description .tf-short-description').slideUp();
            $('.tf-single-description .tf-full-description').slideDown();
        });

        // See Less Description Showing
        $('.tf-single-description .tf-see-less-description').on('click', function () {
            $('.tf-single-description .tf-full-description').slideUp();
            $('.tf-single-description .tf-short-description').slideDown();
        });

        // review modal 
        $(document).on('click', '.tf-modal-btn', function (e) {
            e.preventDefault();
            var dataTarget = $(this).attr('data-target');
            $(dataTarget).addClass('tf-modal-show');
            $('body').addClass('tf-modal-open');
        });
        $(document).on("click", '.tf-modal-close', function () {
            $('.tf-modal').removeClass('tf-modal-show');
            $('body').removeClass('tf-modal-open');
        });
        $(document).on("click", function (event) {
            if(!$('.tf-map-modal').length) {
                if (!$(event.target).closest(".tf-modal-content,.tf-modal-btn").length) {
                    $("body").removeClass("tf-modal-open");
                    $(".tf-modal").removeClass("tf-modal-show");
                }
            }
        });

        // Apartment full description toggle
        $(document).on('click', '.tf-hotel-show-more', function (e) {
            if ($(this).siblings('.tf-full-description')) {
                $(this).siblings('.tf-full-description').show();
                $(this).siblings('.tf-description').hide();
                $(this).text("Show Less");
                $(this).addClass('tf-apartment-show-less');
            }
        });
        
        // Apartment less description toggle
        $(document).on('click', '.tf-hotel-show-less', function (e) {
            if ($(this).siblings('.tf-full-description')) {
                $(this).siblings('.tf-full-description').hide();
                $(this).siblings('.tf-description').show();
                $(this).text("Show More");
                $(this).removeClass('tf-apartment-show-less');
            }
        });

        // faq 
        $('.tf-faq-item-title').on('click', function (e) {
            e.preventDefault();
            var $item = $(this).closest('.tf-faq-item');
            var $content = $item.find('.tf-faq-item-content');

            if ($item.hasClass('active')) {
                $item.removeClass('active');
                $content.stop(true, true).slideUp(400);
            } else {
                $('.tf-faq-item.active').removeClass('active')
                    .find('.tf-faq-item-content').stop(true, true).slideUp(400);

                $item.addClass('active');
                $content.stop(true, true).slideDown(400);
            }
        });


        $('.tf-testimonial-slider').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            adaptiveHeight: false,
            speed: 1500,
            centerMode: true,
            centerPadding: '0',
            prevArrow: '.tf-testimonial-wrapper .sht-prev',
            nextArrow: '.tf-testimonial-wrapper .sht-next',
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        spaceBetween: 32,
                    },
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1.1,
                        slidesToScroll: 1,
                        spaceBetween: 16,
                    },
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
        });

        
         /* see more checkbox filter started */

        $('.tf-booking-sidebar a.see-more').on('click', function (e) {
            var $this = $(this);
            e.preventDefault();
            $this.parent('.sht-filter').find('.sht-filter-item').filter(function (index) {
                return index > 10;
            }).removeClass("hidden");
            $this.hide();

            $this.parent('.sht-filter').find('.see-less').css('display', 'block');
        });

        /* see less checkbox filter started */
        $('.tf-booking-sidebar a.see-less').on('click', function (e) {
            console.log('clicked');
            var $this = $(this);
            e.preventDefault();
            $this.parent('.sht-filter').find('.sht-filter-item').filter(function (index) {
                return index > 10;
            }).addClass("hidden");
            $this.hide();
            $this.parent('.sht-filter').find('.see-more').css('display', 'block');
        });

        $('.tf-booking-sidebar .sht-filter').each(function () {
            var len = $(this).find('ul').children().length;
            $(this).find('.see-more').hide();
            if (len > 10) {
                $(this).find('.see-more').css('display', 'block');
            }
            //hide items if crossed showing limit
            $(this).find('.sht-filter-item').filter(function (index) {
                return index > 10;
            }).addClass("hidden");

        });

        // sticky archive sidebar
        // var $sidebar = $('.tf-sidebar');
        // if (!$sidebar.length) return;

        // var $adminBar = $('#wpadminbar');

        // if ($adminBar.length && $adminBar.hasClass('nojq')) {
        //     $sidebar.css('top', '42px');
        // } else {
        //     $sidebar.css('top', '10px');
        // }

        // view switcher
        $('.sht-archive-view button').on('click', function () {
            var $this = $(this);
            var isList = $this.hasClass('list-view');
            var isMap = $this.hasClass('map-view');

            // Toggle active button in the view switcher
            $this.addClass('active').siblings().removeClass('active');

            // Toggle active class on the related sections
            if (isList) {
                $('.sht-list-view').addClass('active');
                $('.sht-map-view').removeClass('active');
            } else if (isMap) {
                $('.sht-map-view').addClass('active');
                $('.sht-list-view').removeClass('active');
            }
        });

       let filter_xhr;

        // Get term IDs by field name
        const termIdsByFieldName = (fieldName) => {
            let termIds = [];
            $(`[name*="${fieldName}"]`).each(function () {
                if ($(this).is(':checked')) {
                    termIds.push($(this).val());
                }
            });
            return termIds.join();
        };

        const makeFilter = (page = 1, mapCoordinates = []) => {
            const features = termIdsByFieldName('sht_features');
            const facilities = termIdsByFieldName('sht_facilities');
            const ratings = termIdsByFieldName('ratings');
            const score = termIdsByFieldName('score');

            const taxonomy = $('.tf-sidebar').find('.tf-archive-taxonomy').val();
            const term = $('.tf-sidebar').find('.tf-archive-slug').val();

            const placeLocation = $('.tf-archive-search').find('#tf-search-hotel').val();
            var dates = $('.tf-check-in-out-date').val();

           

            var datesArr = [];
            if (dates) {
                dates = dates.replace(' to ', ' - ');
                datesArr = dates.split(' - ');
            }

            var checkin  = (datesArr[0] || '').trim().replaceAll('-', '/');
            var checkout = (datesArr[1] || '').trim().replaceAll('-', '/');
            console.log(checkin, checkout);
            const formData = new FormData();
            formData.append('action', 'sht_archive_filter');
            formData.append('_nonce', tf_params.nonce);
            formData.append('features', features);
            formData.append('facilities', facilities);
            formData.append('ratings', ratings);
            formData.append('score', score);
            formData.append('taxonomy', taxonomy);
            formData.append('term', term);
            formData.append('placeLocation', placeLocation);
            formData.append('checkin', checkin);
            formData.append('checkout', checkout);
            formData.append('page', page);

            if(mapCoordinates.length === 4){
                formData.append('mapCoordinates', mapCoordinates.join(','));
                formData.append('mapFilter', true);
            }

            // Abort previous request if still running
            if (filter_xhr && filter_xhr.readyState !== 4) {
                filter_xhr.abort();
            }

            filter_xhr = $.ajax({
                type: 'POST',
                url: tf_params.ajax_url,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('.archive_ajax_result').block({
                        message: null,
                        overlayCSS: { background: "#fff", opacity: 0.7 }
                    });
                    $('#tf_ajax_searchresult_loader').show();

                },
                success: (data) => {
                    if(data.success == true){
                        $('.archive_ajax_result .sht-list-view .sht-hotels-content').html(data.data.posts.join('')); 
                        $('.archive_ajax_result .sht-map-view .sht-hotels-content').html(data.data.posts.join(''));
                        
                    }else{
                        $('.archive_ajax_result .sht-list-view').html('<p class="sht-no-results">' + data.data.message + '</p>');
                        $('.archive_ajax_result .tf-map-posts-wrapper').html('<p class="sht-no-results">' + data.data.message + '</p>'); 
                        $('.archive_ajax_result #map-datas').html(data.data.map); 
                    }
                    
                },
                complete: () => {
                    $('.archive_ajax_result').unblock();
                    $('#tf_ajax_searchresult_loader').hide();
                    $('.tf-archive-search button[type="submit"]').removeClass('tf-btn-loading');
                },
                error: function() {
                    if (typeof callback === 'function') callback();
                }
            });
        };

        // Trigger filter on checkbox change
        $(document).on('change', '.sht-filter input[type="checkbox"]', function () {
            makeFilter();
        });

        $(document).on('click', '.tf-sidebar .sht-sidebar-reset', function (e) {
            e.preventDefault();
            $('.sht-filter input[type="checkbox"]').prop('checked', false);
            $('.sht-filter li').removeClass('active');
            makeFilter();
        });

        // Trigger filter on form submit
        $(document).on('submit', '.tf-archive-search #tf_hotel_aval_check', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
    
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');

            $submitBtn.addClass('tf-btn-loading')
            if ($form.find('#tf-location').val() != '') {
                makeFilter(1);
            } 
        });

    });
})(jQuery);

