$('#post-form').submit(function (e){

	e.preventDefault();

	$.ajax({

		type:"post",

		url: "classes/action.php",

		data: $(this).serializeArray(),

		success: function (response) {

			alert('success');

		},

		error: function (response){

			alert('error');

		}

	});

});


$('refresh-cache').click(function(e){

	e.preventDefault();

	$.ajax({

		type:"post",

		url:"classes/action.php",

		data: {
			"action":"refresh"
		},

		success: function (response) {

			alert('success');

		},

		error: function (response) {

			alert('success');

		}

	});

});