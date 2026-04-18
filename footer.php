<?php
/**
 * Document footer. Maps to Hugo partials/footer.html + baseof.html close tags.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	</div><!-- .content -->

	<footer class="footer">
		<div class="footer__inner">
			<div class="copyright copyright--user">
				<span>
					<?php
					printf(
						/* translators: %1$s: current year, %2$s: site name */
						esc_html__( '© %1$s %2$s', 'terminal' ),
						esc_html( gmdate( 'Y' ) ),
						esc_html( get_bloginfo( 'name' ) )
					);
					?>
				</span>
				<span>:: <a href="https://github.com/panr/hugo-theme-terminal" target="_blank" rel="noopener"><?php esc_html_e( 'Theme', 'terminal' ); ?></a> <?php esc_html_e( 'ported by', 'terminal' ); ?> <a href="https://github.com/amd989" target="_blank" rel="noopener">amd989</a></span>
			</div>
		</div>
	</footer>

</div><!-- .container -->

<?php wp_footer(); ?>
</body>
</html>
