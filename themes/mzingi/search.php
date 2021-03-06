<?php namespace Habari; ?>
<?php if ( !defined( 'HABARI_PATH' ) ) { die('No direct access'); } ?>
<?php $theme->display ( 'header' ); ?>
<!--begin content-->
	<div id="content">
		<!--begin primary content-->
		<div id="primaryContent">
			<!--begin loop-->
			<h2><?php _e('Results for search of'); ?> "<?php echo Utils::htmlspecialchars( $criteria ); ?>"</h2>
			<?php if (isset($post)) : ?>
<?php foreach ( $posts as $post ): ?>
				<div id="post-<?php echo $post->id; ?>" class="<?php echo $post->statusname; ?>">
						<h2><a href="<?php echo $post->permalink; ?>" title="<?php echo $post->title; ?>"><?php echo $post->title_out; ?></a></h2>
					<div class="entry">
					<?php /* @locale Date formats according to http://php.net/manual/en/function.date.php */ $post->pubdate->out( _t( 'F j, Y' ) ); ?> -	<?php echo $post->content_excerpt; ?>
					</div>
					<div class="entryMeta">

						<?php if ( count( $post->tags ) ) { ?>
						<div class="tags"><?php _e('Tagged:'); ?> <?php echo $post->tags_out; ?></div>
						<?php } ?>
						<div class="commentCount"><?php echo $theme->comments_link($post,'%d Comments','%d Comment','%d Comments'); ?></div>
					</div><br>
					<?php if ( $loggedin ) { ?>
					<a href="<?php echo $post->editlink; ?>" title="<?php _e('Edit post'); ?>"><?php _e('Edit'); ?></a>
					<?php } ?>
				</div>
<?php endforeach; ?>
			<!--end loop-->
			<div id="pagenav">
				<?php echo $theme->prev_page_link('&laquo; ' . _t('Newer Results')); ?> <?php echo $theme->page_selector( null, array( 'leftSide' => 2, 'rightSide' => 2 ) ); ?> <?php echo $theme->next_page_link('&raquo; ' . _t('Older Results')); ?>
			<?php else: ?>
				<p><em><?php _e('No results for'); ?> <?php echo Utils::htmlspecialchars( $criteria ); ?></em></p>
			<?php endif; ?>
			</div>
			</div>

		<!--end primary content-->
		<?php $theme->display ( 'sidebar' ); ?>
	</div>
	<!--end content-->
	<?php $theme->display ( 'footer' ); ?>
