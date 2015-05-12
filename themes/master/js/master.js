(function($) {
    $.Master = function(settings) {
        var config = {
            weekstart: 0,
            contentPlugins: {},
            lang: {
                button_text: "Choose file...",
                empty_text: "No file...",
                monthsFull: '',
                monthsShort: '',
                weeksFull: '',
                weeksShort: '',
                today: "Today",
                delBtn: "Delete Record",
                clear: "Clear",
                selProject: "Select Project",
                delMsg1: "Are you sure you want to delete this record?",
                delMsg2: "This action cannot be undone!!!",
                working: "working..."
            }
        };

        if (settings) {
            $.extend(config, settings);
        }

        $("select").chosen({
            disable_search_threshold: 10,
            width: "100%"
        });

        $('.wojo.dropdown').dropdown();
        $('body [data-content]').popover({
            trigger: 'hover',
            placement: 'auto'
        });

        $("table.sortable").tablesort();

        $('.filefield').filestyle({
            buttonText: 'Choose file...'
        });

        /* == Lightbox == */
        $('.lightbox').swipebox();

        /* == Scrollbox == */
        $(".chosen-results, .scrollbox, #lpanel2").enscroll({
            showOnHover: true,
            addPaddingToPane: false,
            verticalTrackClass: 'scrolltrack',
            verticalHandleClass: 'scrollhandle'
        });

        $("#latest-news .inner").enscroll({
            showOnHover: false,
            addPaddingToPane: false,
            verticalScrollerSide: 'left',
            verticalTrackClass: 'scrolltrack-white',
            verticalHandleClass: 'scrollhandle-white'
        });

        /* == Carousel == */
        $(".wojo-carousel").owlCarousel();

        /* == Date/Time Picker == */
        $('body [data-datepicker]').pickadate();
        $('body [data-timepicker]').pickatime({
            formatSubmit: 'HH:i:00'
        });

        /* == Close Message == */
        $('body').on('click', '.message i.close.icon', function() {
            var $msgbox = $(this).closest('.message')
            $msgbox.slideUp(500, function() {
                $(this).remove()
            });
        });

        $('body').on('click', '.toggle', function() {
            $('.toggle.active').not(this).removeClass('active');
            $(this).toggleClass("active");
        });

        /* == Language Switcher == */
        $('#langmenu').on('click', 'a', function() {
            var target = $(this).attr('href');
            $.cookie("LANG_FM", $(this).data('lang'), {
                expires: 120,
                path: '/'
            });
            $('body').fadeOut(1000, function() {
                window.location.href = SITEURL + "/" + target;
            });
            return false
        });

        /* == Tabs == */
        $(".wtabs .wojo.tab.content").hide();
        $(".wojo.tabs").find('a:first').addClass("active").show();
        $('.wtabs').each(function() {
            $(this).find('.wojo.tab.content:first').show();
        });
        $(".wojo.tabs a").on('click', function() {
            id = $(this).closest(".wtabs").attr("id");
            $("#" + id + " .wojo.tabs a").removeClass("active");
            $(this).addClass("active");
            $("#" + id + " .wojo.tab.content").hide();
            var activeTab = $(this).data("tab");
            $(activeTab).show();
        });

        /* == Accordion == */
        $('.accordion .header').toggleClass('inactive');
        $('.accordion .header').first().toggleClass('active').toggleClass('inactive');
        $('.accordion .content').first().slideDown().toggleClass('open');
        $('.accordion .header').click(function() {
            if ($(this).is('.inactive')) {
                $('.accordion .active').toggleClass('active').toggleClass('inactive').next().slideToggle().toggleClass('open');
                $(this).toggleClass('active').toggleClass('inactive');
                $(this).next().slideToggle().toggleClass('open');
            } else {
                $(this).toggleClass('active').toggleClass('inactive');
                $(this).next().slideToggle().toggleClass('open');
            }
        });

        $('.bodypost').redactor({
            observeLinks: true,
            toolbarFixed: false,
            minHeight: 200,
            maxHeight: 500,
			wym: true,
            focus: true,
            buttons: ['html', 'formatting', 'bold', 'italic', 'unorderedlist', 'orderedlist', 'outdent', 'indent'],
            plugins: ['fullscreen']
        });

        /* == Master Form == */
        $('body').on('click', 'button[name=dosubmit]', function() {
            posturl = $(this).data('url')

            function showResponse(json) {
                if (json.status == "success") {
                    $(".wojo.form").removeClass("loading").slideUp();
                    $("#msgholder").html(json.message);
                } else {
                    $(".wojo.form").removeClass("loading");
                    $("#msgholder").html(json.message);
                }
            }

            function showLoader() {
                $(".wojo.form").addClass("loading");
            }
            var options = {
                target: "#msgholder",
                beforeSubmit: showLoader,
                success: showResponse,
                type: "post",
                url: SITEURL + posturl,
                dataType: 'json'
            };

            $('#wojo_form').ajaxForm(options).submit();
        });

        $('body').on('click', 'a.delete', function() {
			posturl = $(this).data('url')
            var id = $(this).data('id');
            var name = $(this).data('name');
            var title = $(this).data('title');
            var option = $(this).data('option');
            var extra = $(this).data('extra');
            var parent = $(this).parent().parent();
            new Messi("<div class=\"messi-warning\"><i class=\"massive icon warn danger sign\"></i></p><p>" + config.lang.delMsg1 + "<br><strong>" + config.lang.delMsg2 + "</strong></p></div>", {
                title: title,
                titleClass: '',
                modal: true,
                closeButton: true,
                buttons: [{
                    id: 0,
                    label: config.lang.delBtn,
                    class: 'negative',
                    val: 'Y'
                }],
                callback: function(val) {
                    $.ajax({
                        type: 'post',
                        url: SITEURL + '/ajax/controller.php',
                        dataType: 'json',
                        data: {
                            id: id,
                            delete: option,
                            extra: extra ? extra : null,
                            title: encodeURIComponent(name)
                        },
                        beforeSend: function() {
                            parent.animate({
                                'backgroundColor': '#C33C36'
                            }, 400);
                        },
                        success: function(json) {
                            parent.fadeOut(400, function() {
                                parent.remove();
                            });
                            $.sticky(decodeURIComponent(json.message), {
                                type: json.type,
                                title: json.title
                            });
                        }

                    });
                }
            });
        });

        $.browser = {};
        $.browser.version = 0;
        if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
            $.browser.version = RegExp.$1;
            if ($.browser.version < 10) {
                new Messi("<p class=\"wojo red segment\" style=\"width:300px\">It appears that you are using a <em>very</em> old version of MS Internet Explorer (MSIE) v." + $.browser.version + ".<br />If you seriously want to continue to use MSIE, at least <a href=\"http://www.microsoft.com/windows/internet-explorer/\">upgrade</a></p>", {
                    title: "Old Browser Detected",
                    modal: true,
                    closeButton: true
                });
            }
        }


        $(window).on('resize', function() {
            $(".slrange").ionRangeSlider('update');
        });
    };
})(jQuery);