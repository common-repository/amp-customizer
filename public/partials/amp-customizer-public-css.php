<?php if( $header_bg = $this->av_get_option_value( 'header_bg' ) ): ?>
	nav.amp-wp-title-bar{
		background: <?php echo $header_bg; ?>;
	}
<?php endif; ?>

<?php if( $title_text_color = $this->av_get_option_value( 'title_text_color' ) ): ?>
	.amp-wp-title{
		color: <?php echo $title_text_color; ?>;
	}
<?php endif; ?>


<?php if( $content_text_color = $this->av_get_option_value( 'content_text_color' ) ): ?>
	.amp-wp-content{
		color: <?php echo $content_text_color; ?>;
	}
<?php endif; ?>

<?php if( $select_logo = $this->av_get_option_value( 'select_logo' ) ): ?>
	nav.amp-wp-title-bar a {
		background-image: url( '<?php echo wp_get_attachment_url( $select_logo ); ?>' );
		background-repeat: no-repeat;
		background-size: contain;
		display: block;
		margin: 0 auto;
		text-indent: -9999px;
	}
<?php endif; ?>
