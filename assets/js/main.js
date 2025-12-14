;(function($){
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
        $(document).on('click', '.sht-modal-btn', function (e) {
            e.preventDefault();
            $('#sht-rating-modal').addClass('sht-modal-show');
            $('body').addClass('sht-modal-open');
        });
        $(document).on("click", '.sht-modal-close', function () {
            $('.sht-modal').removeClass('sht-modal-show');
            $('body').removeClass('sht-modal-open');
        });
        $(document).on("click", function (event) {
            if (!$(event.target).closest(".sht-modal-content,.sht-modal-btn, .tf-rating-wrapper, .flatpickr-calendar,.media-item").length) {
                $("body").removeClass("sht-modal-open");
                $(".sht-modal").removeClass("sht-modal-show");
            }
        });

        $(window).on('load', function () {
            $("#tf-rating-modal").remove();
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

        if ($('.tf-testimonial-slider').hasClass('no-slide')) {
            return;
        }

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
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        spaceBetween: 32,
                    },
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        spaceBetween: 0,
                    },
                },
                {
                    breakpoint: 575,
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
                return index > 12;
            }).removeClass("hidden");
            $this.hide();

            $this.parent('.sht-filter').find('.see-less').css('display', 'block');
        });

        /* see less checkbox filter started */
        $('.tf-booking-sidebar a.see-less').on('click', function (e) {
            var $this = $(this);
            e.preventDefault();
            $this.parent('.sht-filter').find('.sht-filter-item').filter(function (index) {
                return index > 12;
            }).addClass("hidden");
            $this.hide();
            $this.parent('.sht-filter').find('.see-more').css('display', 'block');
        });

        $('.tf-booking-sidebar .sht-filter').each(function () {
            var len = $(this).find('ul').children().length;
            $(this).find('.see-more').hide();
            if (len > 12) {
                $(this).find('.see-more').css('display', 'block');
            }
            //hide items if crossed showing limit
            $(this).find('.sht-filter-item').filter(function (index) {
                return index > 12;
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
        $(document).on('click', '.sht-archive-view button', function () {
            var $this = $(this);
            var isList = $this.hasClass('list-view');
            var isMap = $this.hasClass('map-view');

            // Toggle active button in the view switcher
            $this.addClass('active').siblings().removeClass('active');

            // Toggle active class on the related sections
            if (isList) {
                $('.sht-hotel-view').addClass('list-view');
                $('.sht-hotel-view').removeClass('map-view');
            } else if (isMap) {
                $('.sht-hotel-view').addClass('map-view');
                $('.sht-hotel-view').removeClass('list-view');
            }
        });

        
        // google map
        var zoomLvl = 5;
        var zoomChangeEnabled = false;
        var centerLvl = new google.maps.LatLng(23.8697847, 90.4219536);
        var markersById = {};
        var markers = [];
        var mapChanged = false;
        var hotelMap;

        const spaGoogleMapInit = (mapLocations, mapLat = 23.8697847, mapLng = 90.4219536) => {
            // Clear existing markers
            clearMarkers();

            var locations = mapLocations ? JSON.parse(mapLocations) : [];

            if(!hotelMap){
                hotelMap = new google.maps.Map(document.getElementById("spa-hotel-archive-map"), {
                    zoom: zoomLvl,
                    minZoom: 3,
                    maxZoom: 18,
                    center: new google.maps.LatLng(mapLat, mapLng),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    styles: [
                        {elementType: 'labels.text.fill', stylers: [{color: '#44348F'}]},
                    ],
                    fullscreenControl: false
                });
            }

            var infowindow = new google.maps.InfoWindow({
                maxWidth: 262,
                disableAutoPan: true,
            });

            var bounds = new google.maps.LatLngBounds();
            locations.map(function (location, i) {
                var marker = new MarkerWithLabel({
                    position: new google.maps.LatLng(location['lat'], location['lng']),
                    map: hotelMap,
                    icon: {
                        url: document.getElementById('map-marker').dataset.marker,
                        scaledSize: new google.maps.Size(sht_params.map_marker_width, sht_params.map_marker_height),
                    },
                    labelContent: '<div class="tf_price_inner" data-post-id="' + location['id'] + '">' + window.atob(location['price']) + '</div>',
                    labelAnchor: new google.maps.Point(0, 0),
                    labelClass: "tf_map_price",
                });

                markersById[location['id']] = marker;
                markers.push(marker);
                bounds.extend(marker.position);

                // Define an OverlayView to use the projection for pixel calculation
                const overlay = new google.maps.OverlayView();
                overlay.draw = function () {};
                overlay.setMap(hotelMap);

                google.maps.event.addListener(marker, 'mouseover', function () {
                    const infoContent = `<div class="custom-info-window">
                            <button class="info-close-btn" type="button"><i class="fa-solid fa-xmark"></i></button>
                            <div class="info-content-inner">
                                ${window.atob(location['content'])}
                            </div>
                        </div>
                    `;
                    infowindow.setContent(infoContent);

                    // Convert LatLng to pixel coordinates
                    const markerPosition = marker.getPosition();
                    const markerProjection = overlay.getProjection();
                    const markerPixel = markerProjection.fromLatLngToDivPixel(markerPosition);

                    // Infowindow dimensions
                    const infoWindowHeight = 646;
                    const infoWindowWidth = 480;

                    // Check each edge
                    const isNearLeftEdge = markerPixel.x <= -120;
                    const isNearRightEdge = markerPixel.x >= 120;
                    const isNearTopEdge = (markerPixel.y - (infoWindowHeight+40)) <= -infoWindowHeight;

                    let anchorX = 0.5;
                    let anchorY = 0;

                    if (isNearLeftEdge) {
                        anchorX = 0.9;
                    } else if (isNearRightEdge) {
                        anchorX = 0.1;
                    }

                    if (isNearTopEdge) {
                        anchorY = infoWindowHeight+90
                    }

                    infowindow.setOptions({
                        pixelOffset: new google.maps.Size((anchorX - 0.5) * infoWindowWidth, anchorY)
                    });

                    infowindow.open(hotelMap, marker);

                    google.maps.event.addListenerOnce(infowindow, 'domready', function() {
                        const closeBtn = document.querySelector('.info-close-btn');
                        if (closeBtn) {
                            closeBtn.addEventListener('click', function(e) {
                                e.stopPropagation();
                                infowindow.close();
                            });
                        }
                    });

                });

                // Hide the infowindow on mouse leave
                google.maps.event.addListener(marker, 'mouseout', function () {
                    infowindow.close();
                });

                google.maps.event.addListener(marker, 'click', function () {
                    window.open(location?.url, '_blank')
                });
            });

           

            // Trigger filter on map drag
            google.maps.event.addListener(hotelMap, "dragend", function () {
                zoomLvl = hotelMap.getZoom();
                centerLvl = hotelMap.getCenter();
                mapChanged = true;

                filterVisibleHotels(hotelMap);
            });

            google.maps.event.addListener(hotelMap, "zoom_changed", function () {
                if (zoomChangeEnabled) return;

                zoomLvl = hotelMap.getZoom();
                centerLvl = hotelMap.getCenter();
                mapChanged = true;

                filterVisibleHotels(hotelMap);

            });
          

            var listener = google.maps.event.addListener(hotelMap, "idle", function() {
                zoomChangeEnabled = true;
                if (!mapChanged) {
                    hotelMap.fitBounds(bounds);
                    centerLvl = bounds.getCenter();
                    hotelMap.setCenter(centerLvl);

                } else {
                    hotelMap.setZoom(zoomLvl);
                    hotelMap.setCenter({lat: centerLvl.lat(), lng: centerLvl.lng()});
                    google.maps.event.removeListener(listener);
                }
                zoomChangeEnabled = false;
            });
        }

        function filterVisibleHotels(map) {
            var bounds = map.getBounds();
            
            if (bounds) {
                var sw = bounds.getSouthWest();
                var ne = bounds.getNorthEast();
            }
        
            spaMakeFilter('', [sw.lat(), sw.lng(), ne.lat(), ne.lng()]);
        }

        function clearMarkers() {
            markers.forEach(marker => marker.setMap(null)); // Remove each marker from the map
            markers = []; // Clear the array to prevent duplication
        }

        // GOOGLE MAP INITIALIZE
        var mapLocations = $('#map-datas').html();
        if ($('#map-datas').length && mapLocations.length) {
            spaGoogleMapInit(mapLocations);
        }

         /*
        * Hotel hover effect on map marker
        * */
        $(document).on('mouseover', '.spa-hotel-archive-template .sht-hotel-single-item', function () {
            let id = $(this).data('id');
            $('.tf_map_price .tf_price_inner[data-post-id="' + id + '"]').addClass('active');

            if (markersById[id]) {
                markersById[id].setAnimation(google.maps.Animation.BOUNCE);
            }
        });
        $(document).on('mouseleave', '.spa-hotel-archive-template .sht-hotel-single-item', function () {
            let id = $(this).data('id');
            $('.tf_map_price .tf_price_inner[data-post-id="' + id + '"]').removeClass('active');

            if (markersById[id]) {
                markersById[id].setAnimation(null);
            }
        });

        /*
        * Map toggle btn for mobile
        */
        $(document).on('click', '.spa-hotel-archive-template .tf-mobile-map-btn, .tf-archive-listing__three .tf-mobile-map-btn', function (e) {
            e.preventDefault();
            $('.spa-hotel-archive-template .tf-details-right').css('display', 'block');
            $('.tf-archive-listing__three .tf-details-right').css('display', 'block');
        });
        $(document).on('click', '.spa-hotel-archive-template .tf-mobile-list-btn, .tf-archive-listing__three .tf-mobile-list-btn', function (e) {
            e.preventDefault();
            $('.spa-hotel-archive-template .tf-details-right').css('display', 'none');
            $('.tf-archive-listing__three .tf-details-right').css('display', 'none');
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

        const spaMakeFilter = (page = 1, mapCoordinates = []) => {
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
            const formData = new FormData();
            formData.append('action', 'sht_archive_filter');
            formData.append('_nonce', sht_params.nonce);
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
                url: sht_params.ajax_url,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('.tf-archive-hotels').block({
                        message: null,
                        overlayCSS: { background: "#fff", opacity: 0.7 }
                    });
                    $('#tf_ajax_searchresult_loader').show();

                },
                success: (data) => {
                    if (Array.isArray(data.data.posts) && data.data.posts.length > 0) {
                        $('.tf-archive-hotels .sht-hotel-content-inner').html(
                            '<div class="sht-hotels-content">' + data.data.posts.join('') + '</div>'
                        );
                        $('.tf-archive-hotels .sht-no-result').hide();
                    } else {
                        $('.tf-archive-hotels .sht-hotel-content-inner').html('');
                        $('.tf-archive-hotels .sht-no-result').show();
                    }
                    let locations = data.data.locations;
                    spaGoogleMapInit(locations);
                    
                },
                complete: () => {
                    $('.tf-archive-hotels').unblock();
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
            spaMakeFilter();
            setTimeout(() => {
                $('.tf-sidebar').removeClass('active');
                $('.mobile-sidebar-overlay').removeClass('active');
            }, 500);
        });

        $(document).on('click', '.tf-sidebar .sht-sidebar-reset', function (e) {
            e.preventDefault();
            $('.sht-filter input[type="checkbox"]').prop('checked', false);
            $('.sht-filter li').removeClass('active');
            spaMakeFilter();

            setTimeout(() => {
                $('.tf-sidebar').removeClass('active');
                $('.mobile-sidebar-overlay').removeClass('active');
            }, 500);
        });

        // Trigger filter on form submit
        $(document).on('submit', '.tf-archive-search #tf_hotel_aval_check', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');

            $submitBtn.addClass('tf-btn-loading')
            if ($form.find('#tf-location').val() != '') {
                spaMakeFilter();
            } 
        });

        const $openBtn = $('#openMobileSidebar');
        const $closeBtn = $('#closeMobileSidebar');
        const $sidebar = $('.tf-sidebar');
        const $overlay = $('.mobile-sidebar-overlay');

        // Open sidebar
        $openBtn.on('click', function() {
            $sidebar.addClass('active');
            $overlay.addClass('active');
        });

        // Close sidebar on close button click
        $closeBtn.on('click', function() {
            $sidebar.removeClass('active');
            $overlay.removeClass('active');
        });


        $overlay.on('click', function() {
            $sidebar.removeClass('active');
            $overlay.removeClass('active');
        });

        $(document).on('click', '#sht-comment-submit', function(e) {
            e.preventDefault();

            let formValid = true;
            const form = $('#sht-review-form');

            $('.rating-error, .field-error').remove();


            $('.ratings-container').each(function() {
                const container = $(this);
                const radios = container.find('input[type="radio"]');
                if (!radios.is(':checked')) {
                    formValid = false;
                    if (container.next('.rating-error').length === 0) {
                        container.after('<div class="rating-error" style="color:#f44; font-size:15px; margin-top:5px;">Please select a rating</div>');
                    }
                }
            });


            const requiredFields = [
                { selector: '#first_name', name: 'First Name' },
                { selector: '#user-email', name: 'Email', type: 'email' },
                { selector: '#sht_comment', name: 'Review Description', minLength: 50 }
            ];

            requiredFields.forEach(function(field) {
                const input = form.find(field.selector);
                const value = $.trim(input.val());
                
                if (!value || (field.minLength && value.length < field.minLength)) {
                    formValid = false;
                    if (input.next('.field-error').length === 0) {
                        input.after('<div class="field-error" style="color:red; font-size:13px; margin-top:5px;">' + field.name + (field.minLength ? ` must be at least ${field.minLength} characters.` : ' is required.') + '</div>');
                    }
                }

                // Email validation
                if (field.type === 'email' && value) {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(value)) {
                        formValid = false;
                        if (input.next('.field-error').length === 0) {
                            input.after('<div class="field-error" style="color:red; font-size:13px; margin-top:5px;">Please enter a valid email address.</div>');
                        }
                    }
                }
            });

            if (!formValid) return false;

            let formData = new FormData(form[0]);
            formData.append('action', 'sht_submit_review');
            $.ajax({
                url: sht_params.ajax_url,
                type: 'POST',
                data: formData,
                contentType: false, 
                processData: false,
                beforeSend: () => {
                    $('.sht-modal-content').block({
                        message: null,
                        overlayCSS: { background: "#fff", opacity: 0.7 }
                    });
                    $('.sht-modal-content .loader').show();

                },
                success: function(res) {
                    const modalContent = $('.sht-modal-content'); 
                    let messageElement;

                    if (res.success) {
                        $('#sht-review-form')[0].reset();
                        $('#media-preview').html('');
                        $('.review-success-message').show();
                        messageElement = $('.review-success-message');
                    } else {
                        $('.review-error-message').text(res.data.message).show();
                        messageElement = $('.review-error-message');
                    }

                    // Scroll smoothly to the message
                    if (messageElement.length && modalContent.length) {
                        modalContent.animate({
                            scrollTop: modalContent.scrollTop() + messageElement.offset().top - modalContent.offset().top
                        }, 500);
                    }

                    // Hide the message after 7 seconds
                    setTimeout(() => {
                        if (res.success) {
                            $('.review-success-message').hide();
                        } else {
                            $('.review-error-message').hide();
                        }
                    }, 7000);
                },
                complete: () => {
                    $('.sht-modal-content').unblock();
                    $('.sht-modal-content .loader').hide();
                }
            });
        });

    });

   
})(jQuery);



document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.sht-location-video-item');
    const prevBtn = document.querySelector('.sht-location-video-slider .sht-prev');
    const nextBtn = document.querySelector('.sht-location-video-slider .sht-next');
    const pagination = document.querySelector('.sht-location-video-slider .sht-pagination');

    let currentIndex = 0; 
    const totalSlides = slides.length;

    for (let i = 0; i < totalSlides; i++) {
        const bullet = document.createElement('span');
        bullet.classList.add('sht-pagination-bullet');
        if (i === currentIndex) bullet.classList.add('sht-pagination-bullet-active');
        bullet.addEventListener('click', () => goToSlide(i));
        pagination.appendChild(bullet);
    }
    const bullets = document.querySelectorAll('.sht-pagination-bullet');

    updateSlider();

    if (nextBtn) {
	  nextBtn.addEventListener('click', () => {
		currentIndex = (currentIndex + 1) % totalSlides;
		updateSlider();
	  });
	}

	if (prevBtn) {
	  prevBtn.addEventListener('click', () => {
		currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
		updateSlider();
	  });
	}

    function goToSlide(index) {
        currentIndex = index;
        updateSlider();
    }

    function updateSlider() {
        const prevIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        const nextIndex = (currentIndex + 1) % totalSlides;

        slides.forEach((slide, index) => {
            slide.classList.remove('left-slide', 'active-slide', 'right-slide');
            slide.style.opacity = '';
            slide.style.width = '';
            slide.style.zIndex = '';

            if (index === currentIndex) slide.classList.add('active-slide');
            else if (index === (currentIndex + 1) % totalSlides) slide.classList.add('right-slide');
            else if (index === (currentIndex - 1 + totalSlides) % totalSlides) slide.classList.add('left-slide');
            else {
                slide.style.opacity = '0';
                slide.style.width = '0';
                slide.style.zIndex = '0';
            }
        });

        bullets.forEach((bullet, index) => {
            bullet.classList.toggle('sht-pagination-bullet-active', index === currentIndex);
        });
    }


});


let selectedFiles = []; 
const MAX_FILE_SIZE = 1 * 1024 * 1024; 
const MAX_FILE_COUNT = 5;

function previewFiles(event) {
    const previewContainer = document.getElementById('media-preview');
    const input = event.target;

    selectedFiles = Array.from(input.files); 


    if (selectedFiles.length > MAX_FILE_COUNT) {
        alert(`You can upload maximum ${MAX_FILE_COUNT} files.`);
        input.value = "";
        selectedFiles = [];
        previewContainer.innerHTML = "";
        return;
    }

    // Check file sizes
    for (let i = 0; i < selectedFiles.length; i++) {
        if (selectedFiles[i].size > MAX_FILE_SIZE) {
            alert(`${selectedFiles[i].name} is too large. Maximum file size is 1MB.`);
            input.value = "";
            selectedFiles = [];
            previewContainer.innerHTML = "";
            return;
        }
    }

    previewContainer.innerHTML = "";

    selectedFiles.forEach((file, index) => {
        const fileType = file.type;
        const reader = new FileReader();

        reader.onload = function(e) {
            const wrapper = document.createElement("div");
            wrapper.classList.add("media-item");

            // Media element
            const mediaElement = document.createElement(
                fileType.startsWith("image") ? "img" : "video"
            );
            mediaElement.src = e.target.result;
            mediaElement.classList.add("preview-item");

            if (fileType.startsWith("video")) mediaElement.controls = true;

            // Close icon
            const closeBtn = document.createElement("span");
            closeBtn.classList.add("close-icon");
            closeBtn.innerHTML = "&#10005;";

            closeBtn.onclick = function () {
                removeFile(index);
            };

            wrapper.appendChild(mediaElement);
            wrapper.appendChild(closeBtn);
            previewContainer.appendChild(wrapper);
        };

        reader.readAsDataURL(file);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1); 

    const input = document.getElementById("review_media");
    const dataTransfer = new DataTransfer();

    selectedFiles.forEach(f => dataTransfer.items.add(f));
    input.files = dataTransfer.files;

    previewFiles({ target: input }); 
}



jQuery(document).on("click", "#sht_visit_date", function () {
    if (!this._flatpickr) {
        jQuery(this).flatpickr({
            dateFormat: "Y-m-d",
            minminDate: "today",
        });
    }
});