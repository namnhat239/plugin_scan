<?php
/**
 * Migration Notice Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Notices;

/**
 * Migration Notice Class.
 */
class Migration {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'admin_notices', array( $this, 'notice' ) );
	}

	/**
	 * Migration Notice.
	 *
	 * @return void|string
	 */
	public function notice() {

		$installed_version = get_option( rttlp_team()->options['installed_version'] );
		$migration_version = rttlp_team()->migration_version;

		if ( ! $installed_version ) {
			return;
		}

		if ( ! version_compare( $installed_version, $migration_version, '<' ) ) {
			return;
		}

		if ( get_option( 'tlp_migrated_data_3_0_3' ) ) {
			return;
		}

		?>

		<div class="notice notice-warning is-dismissible">
			<p>
				<strong><?php esc_html_e( 'We\'ve major updated in our Team plugin. That\'s why we need to migrate your data with the latest version', 'tlp-team' ); ?></strong>
				<div style="margin: 5px 0;"></div>
				<button class="button button-primary" id="tlp-team-migrate-data"><?php esc_html_e( 'Migrate Data', 'tlp-team' ); ?></strong></button>
			</p>
		</div>
		<script type="text/javascript">
			jQuery(document).on("click", "#tlp-team-migrate-data", function(e) {
				e.preventDefault();
				$this = jQuery(this);
				jQuery.ajax({
					type : "POST",
					dataType : "json",
					url : "<?php echo admin_url( 'admin-ajax.php' ); ?>",
					data : { action: "tlp_migrate_data" },
					success: function(response) {
						jQuery("<p><?php esc_html_e( 'Migration Successful', 'tlp-team' ); ?></p>").insertAfter($this);
						$this.remove();
					}
				});

			});
		</script>
		<?php
	}
}
