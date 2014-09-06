;(function($){

	"use strict";

	var self = {

		defaultProjectId: 6926969, // Test
		// defaultProjectId: 6901958, // Orientation

		// also in controller.php... should be consolidated
		employeePlaceholder: "**Example Team Member**",
		companyId: $("#company_id").val(),

		$form: $("form#send_todos"),
		$templates: $("#templates input"),
		$employeeNameInput: $("#employee_name"),
		$response: $("#response"),
		$project: $("#project"),
		$employeeNames: null,

		init: function(){

			self.markEmployeeNames();
			self.loadProjects();

			self.$employeeNameInput.keyup( self.updateEmployeeNames );
			self.$form.submit( self.onFormSubmit );

		},

		loadProjects: function(){

			$.ajax({
				url: "/projects",
				dataType: "json",
				success: function( data ){

					self.$project.html("");
					$.each( data, function( index, project ) {
						self.$project.append(
							"<option value=" + project.id + ">" + project.name + "</option>"
						);
					});

					// Set default project
					self.$project.find("option[value=" + self.defaultProjectId + "]").attr("selected", true);

				}
			});

		},

		markEmployeeNames: function(){
			self.$employeeNames = $( "label span:contains(" + self.employeePlaceholder + ")" );
			self.$employeeNames = self.$employeeNames.find("span");

			self.$employeeNames.each( function(){
				$(this).html(
					$(this).html().replace( employeePlaceholder, "<span>" + employeePlaceholder + "</span>" )
				);
			} );
		},

		updateEmployeeNames: function(){
			var inputVal = $(this).val();

			self.$employeeNames.each( function(){
				$(this).text( inputVal );
			});
		},

		onFormSubmit: function( e ){
			e.preventDefault();
			self.$templates.filter(":checked").each( self.addTodoList );
		},

		addTodoList: function() {
			$.ajax({
				url:      '/projects/' + self.$project.val() + '/todolist',
				type:     "POST",
				dataType: "json",
				data:     {
					name: $(this).next().text(),
					description: "",
					file: $(this).val(),
					find_replace: self.$form.find("input[name^='find_replace']").serialize()
				},

				success: function( list ){

					var url = "https://basecamp.com/" + self.companyId + "/projects/" + self.$project.val() + "/todolists/" + list.id;
					self.$response.prepend(
						"<p id='response-" + list.id + "' class='success' role='alert'>Created List: <a target='_blank' href='" + url + "'>" + list.name + "</a></div>"
					);

				}

			});
		}

	};

	self.init();

})(jQuery);