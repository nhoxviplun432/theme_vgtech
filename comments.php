<?php
if ( post_password_required() ) return;
?>
<div id="comments" class="mt-5">
  <?php if ( have_comments() ) : ?>
    <h2 class="h5 mb-4">
      <?php
      printf( _nx( 'One comment', '%1$s comments', get_comments_number(), 'comments title', 'vgtech-bs5' ),
        number_format_i18n( get_comments_number() ) );
      ?>
    </h2>
    <ol class="list-unstyled">
      <?php wp_list_comments( ['style' => 'ol', 'short_ping' => true] ); ?>
    </ol>
    <?php the_comments_pagination([
        'class' => 'pagination',
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
    ]); ?>
  <?php endif; ?>

  <?php if ( comments_open() || get_comments_number() ) : ?>
    <div class="card mt-4">
      <div class="card-body">
        <?php comment_form([
          'class_submit' => 'btn btn-primary',
          'class_form'   => 'row g-3',
        ]); ?>
      </div>
    </div>
  <?php endif; ?>
</div>
