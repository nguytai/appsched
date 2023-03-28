@extends("master")
@section("index_nav","active")
@section("body")
    <div class="slider-with-overlay">
        <div class="slider-container rev_slider_wrapper" style="height: 560px;">
            <div id="revolutionSlider" class="slider rev_slider" data-plugin-revolution-slider data-plugin-options='{"delay": 9000, "gridwidth": 1170, "gridheight": 560}'>
                <ul>
                    <li data-transition="fade">
                        <img src="http://www.northcountrymedispa.com/yahoo_site_admin/assets/images/o-MANICURE-facebook.230215407.jpg"
                             alt=""
                             data-bgposition="center center"
                             data-bgfit="cover"
                             data-bgrepeat="no-repeat"
                             class="rev-slidebg">

                        <div class="tp-caption top-label" style="color:black"
                             data-x="827"
                             data-y="180"
                             data-start="500"
                             data-transform_in="y:[-300%];opacity:0;s:500;">LOOKING TO GET A</div>

                        <div class="tp-caption main-label" style="color:black"
                             data-x="735"
                             data-y="210"
                             data-start="1500"
                             data-whitespace="nowrap"
                             data-transform_in="y:[100%];s:500;"
                             data-transform_out="opacity:0;s:500;"
                             data-mask_in="x:0px;y:0px;">MANICURE?</div>

                        <div class="tp-caption bottom-label" style="color:black"
                             data-x="785"
                             data-y="280"
                             data-start="2000"
                             data-transform_in="y:[100%];opacity:0;s:500;">Book an appointment now!</div>

                    </li>
                </ul>
            </div>
        </div>
        <div class="home-intro" id="home-intro">
            <div class="container">

            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h2>A place where amazing things <strong>Get Done!</strong></h2>
                <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut feugiat urna arcu, vel molestie nunc commodo non. Nullam vestibulum odio vitae fermentum rutrum.</p>

                <p>Mauris lobortis nulla ut aliquet interdum. Donec commodo ac elit sed placerat. Mauris rhoncus est ac sodales gravida. In eros felis, elementum aliquam nisi vel, pellentesque faucibus nulla.</p>

                <div class="row mt-xlg mb-xl">
                    <div class="col-md-6">
                        <div class="feature-box feature-box-style-2">
                            <div class="feature-box-icon">
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="feature-box-info">
                                <h4 class="heading-primary mb-xs">12 years in business</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing <span class="alternative-font">metus.</span> elit. Quisque rutrum pellentesque imperdiet.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-box feature-box-style-2">
                            <div class="feature-box-icon">
                                <i class="fa fa-heart"></i>
                            </div>
                            <div class="feature-box-info">
                                <h4 class="heading-primary mb-xs">Loved by customers</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum pellentesque imperdiet. Nulla lacinia iaculis nulla.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="tall">

                <h4>Work <strong>Space</strong></h4>

                <div data-plugin-lightbox data-plugin-options='{"delegate": "a", "type": "image", "gallery": {"enabled": true}}'>
                    <div class="row">
                        <div class="col-md-4">
                            <a class="img-thumbnail mb-xl" href="img/our-office-1.jpg" data-plugin-options='{"type":"image"}'>
                                <img class="img-responsive" src="img/our-office-1.jpg" alt="Office">
											<span class="zoom">
												<i class="fa fa-search"></i>
											</span>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a class="img-thumbnail mb-xl" href="img/our-office-2.jpg" data-plugin-options='{"type":"image"}'>
                                <img class="img-responsive" src="img/our-office-2.jpg" alt="Office">
											<span class="zoom">
												<i class="fa fa-search"></i>
											</span>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a class="img-thumbnail mb-xl" href="img/our-office-3.jpg" data-plugin-options='{"type":"image"}'>
                                <img class="img-responsive" src="img/our-office-3.jpg" alt="Office">
											<span class="zoom">
												<i class="fa fa-search"></i>
											</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-5">
                <h4>Our <strong>Location</strong></h4>

                <!-- Google Maps - Go to the bottom of the page to change settings and map location. -->
                <div id="googlemaps" class="google-map small"></div>

                <ul class="list list-icons list-icons-style-3 mt-xlg">
                    <li><i class="fa fa-map-marker"></i> <strong>Address:</strong> 1234 Street Name, City Name, United States</li>
                    <li><i class="fa fa-phone"></i> <strong>Phone:</strong> (123) 456-789</li>
                    <li><i class="fa fa-envelope"></i> <strong>Email:</strong> <a href="mailto:mail@example.com">mail@example.com</a></li>
                </ul>

                <hr>

                <h4>Business <strong>Hours</strong></h4>
                <ul class="list list-icons list-dark mt-xlg">
                    <li><i class="fa fa-clock-o"></i> Monday - Friday 9am to 5pm</li>
                    <li><i class="fa fa-clock-o"></i> Saturday - 9am to 2pm</li>
                    <li><i class="fa fa-clock-o"></i> Sunday - Closed</li>
                </ul>
            </div>
        </div>

    </div>
@endsection
@section("custom_scripts")
    <script src="http://maps.google.com/maps/api/js"></script>
    <script>

        /*
         Map Settings

         Find the Latitude and Longitude of your address:
         - http://universimmedia.pagesperso-orange.fr/geo/loc.htm
         - http://www.findlatitudeandlongitude.com/find-address-from-latitude-and-longitude/

         */

        // Map Markers
        var mapMarkers = [{
            address: "New York, NY 10017",
            html: "<strong>New York Office</strong><br>New York, NY 10017",
            icon: {
                image: "img/pin.png",
                iconsize: [26, 46],
                iconanchor: [12, 46]
            },
            popup: true
        }];

        // Map Initial Location
        var initLatitude = 40.75198;
        var initLongitude = -73.96978;

        // Map Extended Settings
        var mapSettings = {
            controls: {
                draggable: (($.browser.mobile) ? false : true),
                panControl: true,
                zoomControl: true,
                mapTypeControl: true,
                scaleControl: true,
                streetViewControl: true,
                overviewMapControl: true
            },
            scrollwheel: false,
            markers: mapMarkers,
            latitude: initLatitude,
            longitude: initLongitude,
            zoom: 16
        };

        var map = $("#googlemaps").gMap(mapSettings),
                mapRef = $("#googlemaps").data('gMap.reference');

        // Create an array of styles.
        var mapColor = "#0088cc";

        var styles = [{
            stylers: [{
                hue: mapColor
            }]
        }, {
            featureType: "road",
            elementType: "geometry",
            stylers: [{
                lightness: 0
            }, {
                visibility: "simplified"
            }]
        }, {
            featureType: "road",
            elementType: "labels",
            stylers: [{
                visibility: "off"
            }]
        }];

        var styledMap = new google.maps.StyledMapType(styles, {
            name: "Styled Map"
        });

        mapRef.mapTypes.set('map_style', styledMap);
        mapRef.setMapTypeId('map_style');

    </script>
@endsection