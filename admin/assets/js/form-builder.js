(function ($) {
    $.forms = function (settings) {
        var $form_holder = "#renderForm";
        var rownumber;
        var content = '';
        var html = '';

        var config = {
            url: "controller.php",
            formid: 0,
			doVisualForm: 1,
            msg: {
                btnsubmit: "Insert Field",
                selfile: "Select File"
            }
        };

        if (settings) {
            $.extend(config, settings);
        }

        // Add Row
        $('#addRow').on('click', '.item', function () {
            type = $(this).data('type');
            items = '';
            if (type == 'four') {
                items = 4;
            } else if (type == 'three') {
                items = 3;
            } else if (type == 'two') {
                items = 2;
            } else {
                items = 1;
            }

            var field_row =
                '<div data-class="' + type + '" class="' + type + ' fields">';
            for (var i = 0; i < items; i++) {
                field_row += '<div class="field"><i class="icon add sign"></i></div>';
            }
            field_row += '</div>';
            $($form_holder).append(field_row);
        });

        // Add Field
        $($form_holder).on('click', '.add.sign', function () {
            $parent = $(this).closest('.field');
            Messi.load(config.url, {
                doVisualForm: 1,
                loadFormFields: 1
            }, {
                title: config.msg.btnsubmit
            });
        });

        $("body").on('click', '#allfields a.insertfield', function () {
            id = $(this).data("id");
            name = $(this).data("name");
            $($parent).html("<label data-id=\"" + id + "\">" + name + "</label>");
            $('.messi-modal, .messi').remove();
        });

        // Remove Field
        $($form_holder).on('doubletap', 'label', function () {
            $parent = $(this).closest('.field');
            $(this).remove();
            $($parent).html("<i class=\"icon add sign\"></i>");
        });

        // Sort Rows
        $($form_holder).sortable({
            placeholder: 'placeholder2',
            opacity: .6,
        }).draggable();

        $("#removerows").droppable({
            accept: ".fields",
            activeClass: "state-highlight",
            hoverClass: "state-active",
            tolerance: "pointer",
            over: function(event, ui) {
                var width = $(this).width();
                var height = $(this).height()
                ui.draggable.css({
                    height: height,
                    width: width,
                    top: 0,
                    left: 0
                });
            },
            out: function(event, ui) {
                ui.draggable.css({
                    width: "auto"
                });
            },
            drop: function(event, ui) {
                $(ui.draggable).remove()
            }
        });

        // Edit Field
        $('select[name=editfield]').change(function () {
            var option = $(this).val();
            var caption = $("select[name=editfield] option:selected").text();
            var buttons = ['html', '|', 'formatting', '|', 'bold', 'italic'];
            $.ajax({
                type: 'post',
                url: config.url,
                dataType: 'json',
                data: {
                    doVisualForm: 1,
                    editField: 1,
                    id: option,
                    ftitle: caption
                },
                success: function (json) {
                    $(".fieldarea").html(json.data)
                    $("#fieldOptions").slideDown()
					$('.htmlpost').redactor({
						observeLinks: true,
						focus: true,
						buttons: buttons
					});
                }
            });
        });

        // Add Field
        $('select[name=newfield]').change(function () {
            var option = $(this).val();
            var caption = $("select[name=newfield] option:selected").text();
            var buttons = ['html', '|', 'formatting', '|', 'bold', 'italic'];
            $.ajax({
                type: 'post',
                url: config.url,
                dataType: 'json',
                data: {
                    doVisualForm: 1,
                    addField: 1,
                    type: option,
                    ftitle: caption
                },
                success: function (json) {
                    $(".fieldarea").html(json.data)
                    $("#fieldOptions").slideDown()
					$('.htmlpost').redactor({
						observeLinks: true,
						focus: true,
						buttons: buttons
					});
                }
            });
        });

        // Process Field
        $('body').on('click', 'button[name=dofields]', function () {
            function response(json) {
                $("#fieldOptions .segment").removeClass("loading");
                    $("#msgholder").html(json.message);
                if (json.option) {
                    $(json.option).insertAfter("select[name=editfield] option:first")
                    $('select[name=editfield]').trigger("chosen:updated");
                }

            }

            function showSpinner() {
                $("#fieldOptions .segment").addClass("loading");
            }
            var options = {
                target: "#msgholder",
                beforeSubmit: showSpinner,
                success: response,
                type: "post",
                url: config.url,
                dataType: 'json'
            };

            $('#wojo_form').ajaxForm(options).submit();
        });

        // Image Select
        $("body").on("click", "#filebrowser", function () {
            Messi.load("controller.php", {
                pickFile: 1,
                ext: "images"
            }, {
                title: config.msg.selfile
            });
        });
        $("body").on("click", ".filelist a", function () {
            var path = $(this).data('path');
            $('#filename').val(path);
            $('.messi-modal, .messi').remove();

        });

        // Clone Select Options
        $("body").on("click", "#btncAdd", function () {
            $("#btnDel").show();
            var checkFieldHtml = function () {
                var num = $('.clonedInput').length;
                var newNum = new Number(num + 1);
                var name = $('#container1 input').prop('name');
                field = '<div id="container' + newNum + '" class="two fields clonedInput">';
                field += '<div class="field"> ';
                field += '<label class="input">';
                field += '<input type="text" name="' + name + '">';
                field += '</label>';
                field += '</div>';
                field += '<div class="field">';
                field += '<label class="checkbox">';
                field += '<input type="checkbox" name="defval[]" value="' + newNum + '">';
                field += '<i></i>&nbsp;</label>';
                field += '</div>';
                field += '</div>';
                return field;
            };
            $(".fieldholder").append(checkFieldHtml());
        });

        // Clone Radio Options
        $("body").on("click", "#btnrAdd", function () {
            $("#btnDel").show();
            var radioFieldHtml = function () {
                var num = $('.clonedInput').length;
                var newNum = new Number(num + 1);
                var name = $('#container1 input').prop('name');
                field = '<div id="container' + newNum + '" class="two fields clonedInput">';
                field += '<div class="field">';
                field += '<label class="input">';
                field += '<input type="text" name="' + name + '">';
                field += '</label>';
                field += '</div>';
                field += '<div class="field">';
                field += '<label class="radio">';
                field += '<input type="radio" name="defval" value="' + newNum + '">';
                field += '<i></i>&nbsp;</label>';
                field += '</div>';
                field += '</div>';
                return field;
            };
            $(".fieldholder").append(radioFieldHtml());
        });

        // Remove cloned items
        $("body").on("click", "#btnDel", function () {
            var num = $('.clonedInput').length;
            $('#container' + num).remove();
            if (num - 1 == 1) $(this).hide();
        });

		 $("body").on("click", ".itemcheck", function () {
			 isChecked = $(this).is(':checked');
			 $('.itemcheck').prop('checked', false);
			 var $showhide = $(".temps");
			 if (isChecked) {
				 $(this).prop('name') == "other" ? $showhide.hide() : $showhide.show();
				 $(this).prop('checked', true);
			 } else {
				 $showhide.show();
				 $(this).prop('checked', false);
			 }
		 });
		 
        // Save Form
        $("#serialize").on("click", function () {
            var $but = $(this);
            $($but).addClass("loading");
            generatecode();
            html = content;
            html = html.replace(/undefined/g, "");
            html = html.replace(/%%null%%/g, "&nbsp;");
			var order = [];
            $($form_holder + ' label').each(function(){
                order.push($(this).attr('data-id').replace(/_/g, '[]='))
            });
            $.ajax({
                type: "post",
                url: config.url,
                dataType: 'json',
                data: {
                    doVisualForm: 1,
                    saveFormData: 1,
                    formcode: html,
                    id: config.formid,
					layids: order.join(','),
                    htmlcode: $($form_holder).html()
                },
                success: function (json) {
                    $($but).removeClass("loading");
                    $("#smsgholder").html(json.message);
                }
            });

            content = '';
            block_html = '';
            html = '';
        });

        var generatecode = function () {
            var $this = '';
            // the first loop for rows
            $($form_holder + ' .fields').each(function (rownumber) {
                var blockobject = '';

                $this = $(this); //cache
                data_divide = $this.data('class');
                $this.removeAttr("style");
				cls = (data_divide == "one") ? data_divide + ' blank' : data_divide + ' fields'
                content = content + '\t<div class="' + cls + '">\n';

                // the second loop for blocks
                $this.children('.field').each(function () {
                    blockobject = $(this);
                    block_html = '\n\t\t<div class="field">\n';
                    var datafield = blockobject.find("label").data("id");
                    datafield = '%%' + datafield + '%%';
                    content = content + block_html + datafield + '\n\t\t</div>'; //close field
                });
                content = content + '\n\t</div>\n'; //close rows
                rownumber = rownumber + 1;
            });
        }
    };
})(jQuery);