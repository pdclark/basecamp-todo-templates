<?php if ( empty( $this->assignments ) ) : ?>

	No changes made.

<?php else: ?>

	<ul>
	<?php foreach( $this->assignments as $user_id => $todo ) : ?>

		<li>
			Todo <span class="bg-success"><?php echo $todo['content'] ?></span> assigned to <?php echo $this->get_user_name_from_id( $user_id ) ?>.
		</li>

	<?php endforeach; ?>
	</ul>

<?php endif; ?>