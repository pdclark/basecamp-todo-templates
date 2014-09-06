<?php echo View::make( 'header' ); ?>

<form class="form-horizontal" id="send_todos" action="#" role="form">

	<input id="company_id" type="hidden" value="<?php echo $_ENV['basecamp_company_id'] ?>" />

	<div class="form-group">
		<label for="project" class="col-sm-3 control-label">
			Project
		</label>
		<div class="col-sm-6">
			<select class="form-control" id="project" name="project">
				<option value="*">Loading...</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="employee_name" class="col-sm-3 control-label">
			Find &amp; Replace
		</label>
		<div class="col-sm-6">
			<input class="form-control" id="employee_name" name="find_replace[**Example Team Member**]" type="text" value="**Example Team Member**" />
		</div>
	</div>

	<div id="templates" class="form-group">
		<div class="col-sm-offset-3 col-sm-10">

			<h4>Templates</h4>

			<?php foreach ( $templates->getTemplates() as $file => $template ) : $list_count++ ?>
				<div class="checkbox">
					<label>
						<input id="list-<?php echo $list_count ?>" type="checkbox" name="lists[]" checked="checked" value="<?php echo $template['file'] ?>" />
						<span><?php echo $template['title'] ?></span>
					</label>
				</div>
			<?php endforeach; ?>

		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-10">
			<button type="submit" class="btn btn-primary">Add Todo Lists</button>
		</div>
	</div>

</form>


<div id="response"> </div>

<script src="/js/form.js"></script>

<?php echo View::make( 'footer' ); ?>