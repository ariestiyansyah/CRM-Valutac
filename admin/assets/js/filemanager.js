(function ($) {
    $.fileManager = function (settings) {

        var config = {
            'url': "controller.php",
            msg: {
                renempty: "File Name can not be empty",
                renfile: "Rename File",
                delfilea: "Be careful what you delete! Some files might be linked to certain pages, plugins, etc...",
                delfileb: "This action cannot be undone!!!",
                delfilec: "Delete File",
                deldirc: "Delete Directory",
                del: "Delete"
            }
        };

        if (settings) {
            $.extend(config, settings);
        }

        // Create File
        $('a#newFile').on("click", function () {
            $('a#newFile').addClass('loading')
            var path = $(this).data('path');
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'controller.php',
                data: {
                    fmact: 'newFile',
                    doFM: 1,
                    fdirectory: path,
                    filename: $('input[name="newfile"]').val()
                },
                success: function (json) {
                    if (json.status == "success") {
                        $("#msgholder").html(json.info);
                        $("#tableEdit tbody").prepend(json.msg);
                        $("#fcount").html(json['fcount']);
                    } else {
                        $.sticky(decodeURIComponent(json.msg), {
                            type: json.status,
                            title: json.title
                        });
                    }
                    $('a#newFile').removeClass('loading')
                }
            });
        });

        // Create Directory
        $('a#newFolder').on("click", function () {
            $('a#newFolder').addClass('loading')
            var path = $(this).data('path');
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'controller.php',
                data: {
                    fmact: 'newDir',
                    doFM: 1,
                    fdirectory: path,
                    dirname: $('input[name="newdir"]').val()
                },
                success: function (json) {
                    if (json.status == "success") {
                        $("#msgholder").html(json.info);
                        $("#dirlist").append(json.msg);
                        $("#dcount").html(json.dcount);
                    } else {
                        $.sticky(decodeURIComponent(json.msg), {
                            type: json.status,
                            title: json.title
                        });
                    }
                    $('a#newFolder').removeClass('loading')
                }
            });
        });

        /* == Delete File == */
        $('body').on('click', 'a.remove', function () {
            var name = $(this).data('name');
            var path = $(this).data('path');
            var parent = $(this).parent().parent();
            new Messi('<div class=\"messi-warning\" style="max-width:400px"><i class=\"massive icon warn warning sign\"></i></p><p>' + config.msg.delfilea + '<br><strong>' + config.msg.delfileb + '</strong></p></div>', {
                title: config.msg.delfilec,
                modal: true,
                closeButton: true,
                buttons: [{
                    id: 0,
                    label: config.msg.del,
                    class: 'negative',
                    val: 'Y'
                }],
                callback: function (val) {
                    $.ajax({
                        type: 'post',
                        url: 'controller.php',
                        dataType: 'json',
                        data: {
                            fmact: 'deleteFile',
                            doFM: 1,
                            path: path,
                            name: name
                        },
                        beforeSend: function () {
                            parent.animate({
                                'backgroundColor': '#FFBFBF'
                            }, 400);
                        },
                        success: function (json) {
                            if (json.status == "success") {
                                $("#fcount").html(json.fcount);
                                parent.fadeOut(400, function () {
                                    parent.remove();
                                });
                            }

                            $.sticky(decodeURIComponent(json.msg), {
                                type: json.status,
                                title: json.title
                            });

                        }
                    });
                }

            });
        });

        /* == Delete Directory == */
        $('body').on('click', 'a.remdir', function () {
            var name = $(this).data('name');
            var path = $(this).data('path');
            var parent = $(this).parent();
            new Messi('<div class=\"messi-warning\" style="max-width:400px"><i class=\"massive icon warn warning sign\"></i></p><p>' + config.msg.delfilea + '<br><strong>' + config.msg.delfileb + '</strong></p></div>', {
                title: config.msg.deldirc,
                modal: true,
                closeButton: true,
                buttons: [{
                    id: 0,
                    label: config.msg.del,
                    class: 'negative',
                    val: 'Y'
                }],
                callback: function (val) {
                    $.ajax({
                        type: 'post',
                        url: 'controller.php',
                        dataType: 'json',
                        data: {
                            fmact: 'deleteDir',
                            doFM: 1,
                            path: path,
                            name: name
                        },
                        beforeSend: function () {
                            parent.animate({
                                'backgroundColor': '#FFBFBF'
                            }, 400);
                        },
                        success: function (json) {
                            if (json.status == "success") {
                                $("#dcount").html(json.dcount);
                                parent.fadeOut(400, function () {
                                    parent.remove();
                                });
                            }
                            $.sticky(decodeURIComponent(json.msg), {
                                type: json.status,
                                title: json.title
                            });

                        }
                    });
                }

            });
        });

        /* == Upload File == */
        var ul = $('#upload ul');
        $('#drop a').click(function () {
            $(this).parent().find('input').click();
        });

        $('#upload').fileupload({
            dropZone: $('#drop'),
            limitMultiFileUploads: 5,
            sequentialUploads: true,

            add: function (e, data) {
                var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48" data-fgColor="#62A8DB" data-readOnly="1" data-bgColor="#ffffff" /><p><small></small></p><span></span></li>');

                tpl.find('p').text(data.files[0].name)
                    .append('<small></small>')
                    .append('<i>' + formatFileSize(data.files[0].size) + '</i>');

                data.context = tpl.appendTo(ul);
                tpl.find('input').knob();
                tpl.find('span').click(function () {

                    if (tpl.hasClass('working')) {
                        jqXHR.abort();
                    }

                    tpl.fadeOut(function () {
                        tpl.remove();
                    });

                });

                var jqXHR = data.submit().success(function (result, textStatus, jqXHR) {
                    var json = JSON.parse(result);
                    var status = json['status'];

                    if (status == 'error') {
                        data.context.addClass('error');
                        data.context.find('span').addClass('ferror');
                        data.context.find('small').append(json['msg']);
                    } else {
                        $("#tableEdit tbody").append(json['msg']);
                        $("#fcount").html(json['fcount']);
                    }
                    //console.log(json)
                });
            },

            progress: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                data.context.find('input').val(progress).change();
                if (progress == 100) {
                    data.context.removeClass('working');
                }
            },

            fail: function (e, data) {
                data.context.addClass('error');
            }

        });
    };
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
})(jQuery);