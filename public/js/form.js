;(function($){

	var employeePlaceholder = "**Example Team Member**"; // also in controller.php... should be consolidated

	var $employeeNames = $("label span:contains(" + employeePlaceholder + ")"),
			comanyId = $("#company_id").val(),
			$form = $("form#send_todos"),
			$templates = $("#templates input"),
			$response = $("#response"),
			$project = $("#project");

	$employeeNames.each( function(){

		$(this).html(
			$(this).html().replace( employeePlaceholder, "<span>" + employeePlaceholder + "</span>" )
		);

	});

	$employeeNames = $employeeNames.find("span");

	$("#employee_name").keyup(function(){

		var inputVal = $(this).val();

		$employeeNames.each( function(){
			$(this).text( inputVal );
		});

	});

	$form.submit(function( e ){
		e.preventDefault();

		$templates.filter(":checked").each(function(){

			$.ajax({
				url:      '/projects/' + $project.val() + '/todolist',
				type:     "POST",
				dataType: "json",
				data:     {
					name: $(this).next().text(),
					description: "",
					file: $(this).val()
				},

				success: function( list ){
					var url = "https://basecamp.com/" + comanyId + "/projects/" + $project.val() + "/todolists/" + list.id;
					$response.prepend(
						"<p class='success' role='alert'>Added List: <a target='_blank' href='" + url + "'>" + list.name + "</a></div>"
					);
				}

			});

		});

	});

	// Projects
	$.ajax({
		url: "/projects/",
		dataType: "json",
		success: function( data ){

			$project.html("");
			$.each( data, function( index, project ) {
				$project.append(
					"<option value=" + project.id + ">" + project.name + "</option>"
				);
			});

			// Set default project
			// $project.find("option[value=6901958]").attr("selected", true);
			$project.find("option[value=6926969]").attr("selected", true);

		}
	});


})(jQuery);