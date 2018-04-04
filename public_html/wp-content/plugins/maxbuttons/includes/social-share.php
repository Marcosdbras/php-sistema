<?php
		$action = 'install-plugin';
		$slug = 'share-button';
		$url = wp_nonce_url(
			add_query_arg(
				array(
				    'action' => $action,
				    'plugin' => $slug
				),
				admin_url( 'update.php' )
			),
			$action.'_'.$slug
		);
?>


<div class='social-share-move option-container'>
	<div class='inside'>
		<h3>MaxButtons Social Share is moving </h3>

		<p>We created a brand new plugin to make your Social Share experiences better. </p>

		<p><a href='<?php echo $url ?>' class='button-primary large '>Get it here </a></p>
	</div>
</div>
