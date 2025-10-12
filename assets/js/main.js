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
        });

        $(document).on('click', '.tf-sidebar .sht-sidebar-reset', function (e) {
            e.preventDefault();
            $('.sht-filter input[type="checkbox"]').prop('checked', false);
            $('.sht-filter li').removeClass('active');
            spaMakeFilter();
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

    nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % totalSlides;
        updateSlider();
    });

    prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        updateSlider();
    });

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
