(function ($) {
    $.Master = function (settings) {
        var config = {
			weekstart : 0,
			contentPlugins: {},
            lang: {
                button_text: "Choose file...",
                empty_text: "No file...",
				monthsFull : '',
				monthsShort : '',
				weeksFull : '',
				weeksShort : '',
				today : "Today",
				delBtn : "Delete Record",
				clear : "Clear",
				selProject : "Select Project",
				delMsg1: "Are you sure you want to delete this record?",
				delMsg2: "This action cannot be undone!!!",
				working: "working..."
            }
        };

        if (settings) {
            $.extend(config, settings);
        }

        var itemid = ($.url(true).param('id')) ? $.url(true).param('id') : 0;
        var plugname = $.url(true).param('plugin');
        var modname = $.url(true).param('module');
        var posturl = (plugname ? "../plugins/" + plugname + "/controller.php" : (modname ? "../modules/" + modname + "/controller.php" : "controller.php"));
		

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
		
		$('.roundchart').easyPieChart({easing: 'easeOutQuad'});

		$(".filefield").filestyle({buttonText: config.lang.button_text});

		$('body [data-datepicker]').pickadate({
			firstDay: config.weekstart,
			formatSubmit: 'yyyy-mm-dd',
			monthsFull: config.lang.monthsFull,
			monthsShort: config.lang.monthsShort,
			weekdaysFull: config.lang.weeksFull,
			weekdaysShort: config.lang.weeksShort,
			today: config.lang.today,
			clear: config.lang.clear,
		});
		$('body [data-timepicker]').pickatime({
			formatSubmit: 'HH:i:00'
		});
		
		/* == Lightbox == */
		$('.lightbox').swipebox();

		/* == Scrollbox == */
		$(".chosen-results, .scrollbox").enscroll({
			showOnHover: true,
			addPaddingToPane: false,
			verticalTrackClass: 'scrolltrack',
			verticalHandleClass: 'scrollhandle'
		});

		/* == Help Sidebar == */
		$('body').on('click', 'a.helper', function () {
			var div = $(this).data('help');
			$('#helpbar').sidebar('toggle').addClass('loading');
			setTimeout(function () {
				$('#helpbar').load('help/help.php #' + div + '-help');
				$('#helpbar').removeClass('loading');
			}, 500);
			$('#helpbar').enscroll({
				addPaddingToPane: false
			});
		})

		/* == Close Message == */
		$('body').on('click', '.message i.close.icon', function () {
			var $msgbox = $(this).closest('.message')
			$msgbox.slideUp(500, function () {
				$(this).remove()
			});
		});

		/* == Close Note == */
		$('body').on('click', '.note i.close.icon', function () {
			var $msgbox = $(this).closest('.note')
			$msgbox.slideUp(500, function () {
				$(this).remove()
			});
		});
		
		/* == Language Switcher == */
		$('.langchange').on('click', function () {
			var target = $(this).attr('href');
			$.cookie("LANG_FM", $(this).data('lang'), {
				expires: 120,
				path: '/'
			});
			$('body').fadeOut(1000, function () {
				window.location.href = target;
			});
			return false
		});
  
		/* == Tabs == */
		$(".tab_content").hide();
		$("#tabs a:first").addClass("active").show();
		$(".tab_content:first").show();
		$("#tabs a").on('click', function () {
			$("#tabs a").removeClass("active");
			$(this).addClass("active");
			$(".tab_content").hide();
			var activeTab = $(this).data("tab");
			$(activeTab).show();
			//return false;
		});

		/* == Toggle Menu icons == */
		$('#scroll-icons').on('click', 'i', function () {
			var micon = $("input[name=icon]");
			$('#scroll-icons i.active').not(this).removeClass('active');
			$(this).toggleClass("active");
			micon.val($(this).hasClass('active') ? $(this).attr('data-icon-name') : "");
		});

		/* == Single File Picker == */
		$('body').on('click', '.filepicker', function () {
			type = $(this).prev('input').data('ext');
			Messi.load('controller.php', {
				pickFile: 1,
				ext: type
			}, {
				title: config.lang.button_text
			});
		});

		$("body").on("click", ".filelist a", function () {
			var path = $(this).data('path');
			$('input[name=filename], input[name=attr]').val(path);
			$('.messi-modal, .messi').remove();

		});

		/* == Editor == */
		$('.bodypost').redactor({
			observeLinks: true,
			wym: true,
			toolbarFixed: true,
			minHeight: 200,
			maxHeight: 500,
			plugins: ['fullscreen']
		});

		/* == Editor == */
		$('.fullpage').redactor({
			observeLinks: true,
			toolbarFixed: true,
			minHeight: 500,
			maxHeight: 800,
			iframe: true,
			focus: true,
			buttons: ['html', 'formatting', 'bold', 'italic', 'unorderedlist', 'orderedlist', 'outdent', 'indent', 'table'],
			plugins: ['fullscreen']
		});
		
		$('.altpost').redactor({
			observeLinks: true,
			minHeight: 100,
			buttons: ['formatting', 'bold', 'italic', 'unorderedlist', 'orderedlist', 'outdent', 'indent'],
			wym: true,
			plugins: ['fullscreen']
		});
		/* == Submit Search by date == */
		$("#doDates").on('click', function () {
			$("#admin_form").submit();
			return false;
		});

		/* == Master Form == */
		$('body').on('click', 'button[name=dosubmit]', function () {
			function showResponse(json) {
				$(".wojo.form").removeClass("loading");
				$("#msgholder").html(json.message);
			}

			function showLoader() {
				$(".wojo.form").addClass("loading");
			}
			var options = {
				target: "#msgholder",
				beforeSubmit: showLoader,
				success: showResponse,
				type: "post",
				url: posturl,
				dataType: 'json'
			};

			$('#wojo_form').ajaxForm(options).submit();
		});

		/* == Delete Multiple == */
		$('body').on('click', 'button[name=mdelete]', function () {
			function showResponse(json) {
				$("button[name='mdelete']").removeClass("loading");
				$('.wojo.table tbody tr').each(function () {
					if ($(this).find('input:checked').length) {
						$(this).fadeOut(400, function () {
							$(this).remove();
						});
					}
				});
				$("#msgholder").html(json.message);
			}

			function showLoader() {
				$("button[name='mdelete']").addClass("loading");
				$('.wojo.table tbody tr').each(function () {
					if ($(this).find('input:checked').length) {
						$(this).animate({
							'backgroundColor': '#FFBFBF'
						}, 400);
					}
				});

			}

			var options = {
				target: "#msgholder",
				beforeSubmit: showLoader,
				success: showResponse,
				type: "post",
				url: posturl,
				dataType: 'json'
			};

			$('#wojo_form').ajaxForm(options).submit();
		});

		/* == Delete Item == */
		$('body').on('click', 'a.delete', function () {
			var id = $(this).data('id');
			var name = $(this).data('name');
			var title = $(this).data('title');
			var option = $(this).data('option');
			var extra = $(this).data('extra');
			var parent = $(this).parent().parent();
			new Messi("<div class=\"messi-warning\"><i class=\"massive icon warn warning sign\"></i></p><p>" + config.lang.delMsg1  + "<br><strong>" + config.lang.delMsg2  + "</strong></p></div>", {
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
				callback: function (val) {
					$.ajax({
						type: 'post',
						url: posturl,
						dataType: 'json',
						data: {
							id: id,
							delete: option,
							extra: extra ? extra : null,
							title: encodeURIComponent(name)
						},
						beforeSend: function () {
							parent.animate({
								'backgroundColor': '#C33C36'
							}, 400);
						},
						success: function (json) {
							parent.fadeOut(400, function () {
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

		/* == Main Menu == */
		var MenuNav = (function () {
			var $listItems = $('nav > ul > li'),
				$menuItems = $listItems.children('a'),
				$body = $('body'),
				current = -1;
		
			function init() {
				$menuItems.on('click', open);
				$listItems.on('click', function (event) {
					event.stopPropagation();
				});
			}
		
			function open(event) {
				if (current !== -1) {
					$listItems.eq(current).removeClass('opened');
				}
		
				var $item = $(event.currentTarget).parent('li'),
					idx = $item.index();
		
				if (current === idx) {
					$item.removeClass('opened');
					current = -1;
				} else {
					$item.addClass('opened');
					current = idx;
					$body.off('click').on('click', close);
				}
		
			}
		
			function close(event) {
				$listItems.eq(current).removeClass('opened');
				current = -1;
			}
		
			return {
				init: init
			};
		
		})();

		MenuNav.init();


		/* == Submit Search by date == */
		$("#doDates").on('click', function () {
			$("#wojo_form").submit();
			return false;
		});

		/* == Inline Edit == */
		$('body').on('focus', 'div[contenteditable=true]:not(.redactor_editor)', function () {
			$(this).data("initialText", $(this).text());
			$('div[contenteditable=true]:not(.redactor_editor)').not(this).removeClass('active');
			$(this).toggleClass("active");
		}).on('blur', 'div[contenteditable=true]:not(.redactor_editor)', function () {
			if ($(this).data("initialText") !== $(this).text()) {
				title = $(this).text();
				type = $(this).data("edit-type");
				id = $(this).data("id")
				key = $(this).data("key")
				path = $(this).data("path")
				$this = $(this);
				$.ajax({
					type: "POST",
					url: posturl,
					data: ({
						'title': title,
						'type': type,
						'key': key,
						'path': path,
						'id': id,
						'quickedit': 1
					}),
					beforeSend: function () {
						$this.text(config.lang.working).animate({
							opacity: 0.2
						}, 800);
					},
					success: function (res) {
						$this.animate({
							opacity: 1
						}, 800);
						setTimeout(function () {
							$this.html(res).fadeIn("slow");
						}, 1000);
					}
				})
			}
		});

		(function($) {
			$.extend({
				app: {
					formatTimer: function(a) {
						if (a < 10) {
							a = '0' + a;
						}
						return a;
					},
					startTimer: function() {
						var a;
						$.app.d1 = new Date();
						switch ($.app.state) {
							case 'pause':
								$.app.t1 = $.app.d1.getTime() - $.app.td;
								break;
							default:
								$.app.t1 = $.app.d1.getTime();
								break;
						}
						$.app.state = 'alive';
						$('#sw_status').html('Running');
						$.app.loopTimer();
					},
					pauseTimer: function() {
						$.app.dp = new Date();
						$.app.tp = $.app.dp.getTime();
						$.app.td = $.app.tp - $.app.t1;
						$('#sw_start').val('Resume');
						$.app.state = 'pause';
						$('#sw_status').html('Paused');
	
					},
					stopTimer: function() {
						$('#sw_start').val('Restart');
						$.app.state = 'stop';
						$('#sw_status').html('Stopped');
					},
					resetTimer: function() {
						$('#sw_ms,#sw_s,#sw_m,#sw_h').html('00');
						$('#sw_start').val('Start');
						$.app.state = 'reset';
						$('#sw_status').html('Reset & Idle again');
					},
					makeRecord: function() {
						hours = $('#sw_h').html();
						minutes = $('#sw_m').html();
						$.cookie("FM_TBIL", hours + minutes, {
							path: '/'
						});
						$.app.state = 'reset';
						$('#sw_ms,#sw_s,#sw_m,#sw_h').html('00');
						Messi.load("controller.php", {
							loadProjects: 1
						}, {
							title: config.lang.selProject
						});	
					},
					loopTimer: function() {
						var td;
						var d2, t2;
						var ms = 0;
						var s = 0;
						var m = 0;
						var h = 0;
	
						if ($.app.state === 'alive') {
							d2 = new Date();
							t2 = d2.getTime();
							td = t2 - $.app.t1;
							ms = td % 1000;
							if (ms < 1) {
								ms = 0;
							} else {
								// calculate seconds
								s = (td - ms) / 1000;
								if (s < 1) {
									s = 0;
								} else {
									// calculate minutes   
									var m = (s - (s % 60)) / 60;
									if (m < 1) {
										m = 0;
									} else {
										// calculate hours
										var h = (m - (m % 60)) / 60;
										if (h < 1) {
											h = 0;
										}
									}
								}
							}
	
							// substract elapsed minutes & hours
							ms = Math.round(ms / 100);
							s = s - (m * 60);
							m = m - (h * 60);
	
							// update display
							$('#sw_ms').html($.app.formatTimer(ms));
							$('#sw_s').html($.app.formatTimer(s));
							$('#sw_m').html($.app.formatTimer(m));
							$('#sw_h').html($.app.formatTimer(h));
	
							$.app.t = setTimeout($.app.loopTimer, 1);
						} else {
							// kill loop
							clearTimeout($.app.t);
							return true;
						}
					}
				}
			});
	
			$('#sw_start').on('click', function() {
				$.app.startTimer('sw');
			});
	
			$('#sw_stop').on('click', function() {
				$.app.stopTimer();
			});
	
			$('#sw_reset').on('click', function() {
				$.app.resetTimer();
			});
	
			$('#sw_pause').on('click', function() {
				$.app.pauseTimer();
			});
	
			$('#sw_make').on('click', function() {
				$.app.makeRecord();
			});
			$('body').on('click', '#pList a', function() {
				id = $(this).data('id');
				window.location.href = "index.php?do=timebilling&action=add&id=" + id;
			});
		})($);
	
        $(window).on('resize', function () {
            $(".slrange").ionRangeSlider('update');
        });

        $(document).on('dragover', function (e) {
            var dropZone = $('#drop'),
                timeout = window.dropZoneTimeout;
            if (!timeout) {
                dropZone.addClass('in');
            } else {
                clearTimeout(timeout);
            }
            var found = false,
                node = e.target;
            do {
                if (node === dropZone[0]) {
                    found = true;
                    break;
                }
                node = node.parentNode;
            } while (node != null);
            if (found) {
                dropZone.addClass('hover');
            } else {
                dropZone.removeClass('hover');
            }
            window.dropZoneTimeout = setTimeout(function () {
                window.dropZoneTimeout = null;
                dropZone.removeClass('in hover');
            }, 100);
        });

        function formatFileSize(bytes) {
            if (typeof bytes !== 'number') {
                return '';
            }

            if (bytes >= 1000000000) {
                return (bytes / 1000000000).toFixed(2) + ' GB';
            }

            if (bytes >= 1000000) {
                return (bytes / 1000000).toFixed(2) + ' MB';
            }

            return (bytes / 1000).toFixed(2) + ' KB';
        }
    };
})(jQuery);