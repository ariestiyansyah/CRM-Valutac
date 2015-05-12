function loadList() {
    $.ajax({
        type: 'post',
        url: "controller.php",
        data: 'getmenus=1',
        cache: false,
        success: function (html) {
            $("#menusort").html(html);
        }
    });
}
$(document).ready(function () {
    $("button[name='domenu']").click(function () {
        $(".wojo.form").addClass("loading");
        var str = $('#wojo_form').serialize()
        $.ajax({
            type: "post",
            url: "controller.php",
            dataType: 'json',
            data: str,
            cache: false,
            success: function (json) {
                if (json.type == "success") {
                    $(".wojo.form").removeClass("loading");
                    $("#msgholder").html(json.message);
                    loadList();
                } else {
                    $(".wojo.form").removeClass("loading");
                    $("#msgholder").html(json.message);
                }
            }
        });
    });

    $('body').on('click', "#serialize", function () {
        serialized = $('#menusort').nestedSortable('serialize');
        serialized += '&doMenuSort=1';
        $.ajax({
            type: 'post',
            url: "controller.php",
            data: serialized,
            success: function (msg) {
                $("#msgalt").html(msg);
                setTimeout(function () {
                    $(loadList()).fadeIn("slow");
                }, 2000);
                $("html, body").animate({
                    scrollTop: 0
                }, 600);
            }
        });
    })

    $('div#menusort').nestedSortable({
        forcePlaceholderSize: true,
        listType: 'ul',
        handle: 'div',
        helper: 'clone',
        items: 'li',
        opacity: .6,
        placeholder: 'placeholder',
        tabSize: 25,
        tolerance: 'pointer',
        toleranceElement: '> div'
    });

    $('#contenttype').change(function () {
        var option = $(this).val();
        $.ajax({
            type: 'get',
            url: "controller.php",
            dataType: 'json',
            data: {
                contenttype: option
            },
            success: function (json) {
                if (json.type == "module") {
                    $("#contentid").show();
                    $("#webid").hide();
                    $('#page_id').html(json.message).trigger("chosen:updated");
                    $('#page_id').prop('name', 'mod_id');
                } else if (json.type == "page") {
                    $("#contentid").show();
                    $("#webid").hide();
                    $('#page_id').html(json.message).trigger("chosen:updated");
                    $('#page_id').prop('name', 'page_id');
                } else {
                    $("#webid").show();
                    $("#contentid").hide();
                    $(json.message).appendTo('#wojo_form');
                }
            }
        });
    });

    $("input[name=cols]").ionRangeSlider({
		min: 1,
		max: 4,
        step: 1,
		postfix: " col",
        type: 'single',
        hasGrid: true
    });
});