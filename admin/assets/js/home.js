(function ($) {
    $.Home = function (settings) {
        var config = {
			lat: 43.652527,
			long: -79.381961,
            temp: 'c',
            city: 'Toronto'
        };

        if (settings) {
            $.extend(config, settings);
        }

        var map = null;
        loadMap = function (latitude, longitude) {
            var latlng = new google.maps.LatLng(latitude, longitude);
            var myOptions = {
                zoom: 13,
                center: latlng,
                mapTypeControl: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("gmap"), myOptions);
        }
        setupMarker = function (latitude, longitude) {
            var pos = new google.maps.LatLng(latitude, longitude);
            var image = new google.maps.MarkerImage('../assets/pin.png');
            var marker = new google.maps.Marker({
                position: pos,
                map: map,
                raiseOnDrag: false,
                icon: image,
                title: name
            });
        }

        var geocoder;
        geocoder = new google.maps.Geocoder();
        var latitude = config.lat;
        var longitude = config.long;
        loadMap(latitude, longitude);
        setupMarker(latitude, longitude);

        $('#address').on("keyup", function (e) {
            if (e.keyCode == 13) {
                var address = document.getElementById('address').value;
                geocoder.geocode({
                    'address': address
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        var image = new google.maps.MarkerImage('../assets/pin.png');
                        var marker = new google.maps.Marker({
                            map: map,
                            raiseOnDrag: false,
                            icon: image,
                            position: results[0].geometry.location
                        });
                    } else {
                        alert('Geocode was not successful for the following reason: ' + status);
                    }

                });
            }
        });

        var city = config.city;
        $.simpleWeather({
            location: city,
            woeid: '',
            unit: config.temp,
            success: function (weather) {
                city = weather.city;
                region = weather.country;
                tomorrow_date = weather.tomorrow.date;
                $(".weather-city").html(city);
                $(".weather-currently").html(weather.currently);
                $(".today-img").html('<i class="big-img-weather wicon-' + weather.code + '"></i>');
                $(".today-temp").html(weather.low + '&deg; / ' + weather.high + '&deg;');
                $(".weather-region").html(region);
                $(".weather-day").html(tomorrow_date);
                $(".1-days-day").html(weather.forecasts.one.day);
                $(".1-days-image").html('<i class="wicon-' + weather.forecasts.one.code + '"></i>');
                $(".1-days-temp").html(weather.forecasts.one.low + '&deg; / ' + weather.forecasts.one.high + '&deg;');
                $(".2-days-day").html(weather.forecasts.two.day);
                $(".2-days-image").html('<i class="wicon-' + weather.forecasts.two.code + '"></i>');
                $(".2-days-temp").html(weather.forecasts.two.low + '&deg; / ' + weather.forecasts.two.high + '&deg;');
                $(".3-days-day").html(weather.forecasts.three.day);
                $(".3-days-image").html('<i class="wicon-' + weather.forecasts.three.code + '"></i>');
                $(".3-days-temp").html(weather.forecasts.three.low + '&deg; / ' + weather.forecasts.three.high + '&deg;');
                $(".4-days-day").html(weather.forecasts.four.day);
                $(".4-days-image").html('<i class="wicon-' + weather.forecasts.four.code + '"></i>');
                $(".4-days-temp").html(weather.forecasts.four.low + '&deg; / ' + weather.forecasts.four.high + '&deg;');

            }
        });

        $('#location').on("keyup", function (e) {
            if (e.keyCode == 13) {
                city = $('#location').val();
                $.simpleWeather({
                    location: city,
                    woeid: '',
                    unit: config.temp,
                    success: function (weather) {
                        city = weather.city;
                        region = weather.country;
                        tomorrow_date = weather.tomorrow.date;
                        $(".weather-city").html(city);
                        $(".weather-currently").html(weather.currently);
                        $(".today-img").html('<i class="big-img-weather wicon-' + weather.code + '"></i>');
                        $(".today-temp").html(weather.low + '&deg; / ' + weather.high + '&deg;');
                        $(".weather-region").html(region);
                        $(".weather-day").html(tomorrow_date);
                        $(".1-days-day").html(weather.forecasts.one.day);
                        $(".1-days-image").html('<i class="wicon-' + weather.forecasts.one.code + '"></i>');
                        $(".1-days-temp").html(weather.forecasts.one.low + '&deg; / ' + weather.forecasts.one.high + '&deg;');
                        $(".2-days-day").html(weather.forecasts.two.day);
                        $(".2-days-image").html('<i class="wicon-' + weather.forecasts.two.code + '"></i>');
                        $(".2-days-temp").html(weather.forecasts.two.low + '&deg; / ' + weather.forecasts.two.high + '&deg;');
                        $(".3-days-day").html(weather.forecasts.three.day);
                        $(".3-days-image").html('<i class="wicon-' + weather.forecasts.three.code + '"></i>');
                        $(".3-days-temp").html(weather.forecasts.three.low + '&deg; / ' + weather.forecasts.three.high + '&deg;');
                        $(".4-days-day").html(weather.forecasts.four.day);
                        $(".4-days-image").html('<i class="wicon-' + weather.forecasts.four.code + '"></i>');
                        $(".4-days-temp").html(weather.forecasts.four.low + '&deg; / ' + weather.forecasts.four.high + '&deg;');

                    }
                });
            }
        });
		
        $("[data-select-range]").on('click', '.item', function () {
            v = $("input[name=range]").val();
            getVisitsChart(v)
        });

        function getVisitsChart(range) {
            $.ajax({
                type: 'GET',
                url: 'controller.php?getSaleStats=1&timerange=' + range,
                dataType: 'json',
                async: false,
                success: function (json) {
                    var option = {
                        shadowSize: 0,
                        lines: {
                            show: true,
							fill: true
                        },
                        points: {
                            show: true
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
							borderColor: {
								top: "rgba(255,255,255,0.2)",
								left: "rgba(255,255,255,0.2)"
							}
                        },
                        xaxis: {
                            ticks: json.xaxis,
							font: {
								color: "#fff"
							}
                        },
                        yaxis: {
							color: "rgba(255,255,255,0.2)",
							font: {
								color: "#fff"
							}
                        },
						legend: {
							backgroundColor:"#FFF",
							backgroundOpacity: .5,
						}
                    }
					$.plot($('#chart'), [json.order], option);
                }
            });
        }

        getVisitsChart('year');

    };
})(jQuery);