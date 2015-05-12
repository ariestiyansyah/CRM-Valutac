$(document).ready(function () {
	loadList();
    $('#access_id').change(function () {
        var option = $(this).val();
        var result = 'mid=' + $(this).data('id');;
        result += '&membershiplist=' + option;
        $.ajax({
            type: "post",
            url: "controller.php",
            data: result,
            cache: false,
            success: function (res) {
                (option == "Membership") ? $('#memrow').show() : $('#memrow').hide();
                $('#membership').html(res);
                $("select").chosen({
                    disable_search_threshold: 10,
                    width: "100%"
                });
            }
        });
    });
	
    $('#modulename').change(function () {
        var option = $(this).val();
		var result = 'module_data=' + $(this).data('module');
        result += '&modulelist=' + option;
        $.ajax({
            type: "post",
            url: "controller.php",
            data: result,
            cache: false,
            success: function (res) {
                (option == 0) ? $('#modshow').hide() : $('#modshow').show();
                $('#modshow').html(res);
                $("select").chosen({
                    disable_search_threshold: 10,
                    width: "100%"
                });
            }
        });
    });
});
function loadList() {
	module_data = $('#modulename').data('module');
	module_id = $('#modulename').data('id');
	var option = $('#modulename').val();
	var result = 'module_data=' + module_data;
	result += '&modulelist=' + option;
	$.ajax({
		type: "post",
		url: "controller.php",
		data: result,
		cache: false,
		success: function (res) {
			(option == module_id) ? $('#modshow').show() : $('#modshow').hide();
			$('#modshow').html(res);
			$("select").chosen({
				disable_search_threshold: 10,
				width: "100%"
			});
		}
	});
}