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
            $('.tf-short-description').slideUp();
            $('.tf-full-description').slideDown();
        });

        // See Less Description Showing
        $('.tf-single-description .tf-see-less-description').on('click', function () {
            $('.tf-full-description').slideUp();
            $('.tf-short-description').slideDown();
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

    });
})(jQuery);
