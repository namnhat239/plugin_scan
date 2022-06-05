<?php
/**
 * <p>ThrowsSpamAway</p> Comments Class
 * WordPress's Plugin
 * @author Takeshi Satoh@GTI Inc. 2022
 * @version 3.3.2
 */

require_once "throws_spam_away.class.php";

function tsa_comment_main() {
	global $wpdb;
	$nonce    = isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : null;
	$verified = $nonce && wp_verify_nonce( $nonce );
	
	$_spam_delete_flg = false;
	$_pend_delete_flg = false;
	
	$param_c_all = ! isset( $_POST['c_all'] ) ? "" : $_POST['c_all'];
	$param_tsa_kind = ! isset( $_POST['tsa_kind'] ) ? "" : $_POST['tsa_kind'];
	$param_c_pend = ! isset( $_POST['c_pend'] ) ? "" : $_POST['c_pend'];
	$param_pend = ! isset( $_POST['pend'] ) ? "" : $_POST['pend'];
	
	
	if ( $verified ) {
		if ( $param_c_all == 'a' && $param_tsa_kind == 's' ) {
			$spam_comment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved =  'spam'" );
			if ( $spam_comment_count == 0 ) {
			} else {
				$_spam_delete_flg = $wpdb->query( "DELETE FROM $wpdb->comments WHERE comment_approved =  'spam'" );
			}
		}
		if ( $param_c_pend == 'p' && $param_pend == 'p' ) {
			$delete_count = $wpdb->query( "DELETE FROM $wpdb->comments WHERE comment_approved = '0'" );
			$_pend_delete_flg = $delete_count >= 0;
		}
	}
	
	$c_all   = $wpdb->get_var( "SELECT count( comment_ID ) FROM $wpdb->comments" );
	$c_pend  = $wpdb->get_var( "SELECT count( comment_ID ) FROM $wpdb->comments WHERE comment_approved = '0'" );
	$s_count = $wpdb->get_var( "SELECT count( comment_ID ) FROM $wpdb->comments WHERE comment_approved =  'spam'" );
	?>
	<div class="wrap">
		<h2>スパムコメント処理</h2>
		<p>Akismet などのコメントフィルタによって spam マークがついているコメントを削除します。</p>
		<?php
			if ( $verified ) {
				if ( $param_c_all == 'a' && $param_tsa_kind == 's' ) {
					if ( $s_count == 0 ) {
						echo "<p style='color:green'><strong>" . __( 'You have no comment spam.', ThrowsSpamAway::DOMAIN ) . "</strong></p>";
					} else {
						if ( $_spam_delete_flg != false ) {
							echo "<p style='color:green'><strong>" . __( 'Spam comments have been deleted.', ThrowsSpamAway::DOMAIN ) . "</strong></p>";
						} else {
							echo "<p style='color:red'><strong>" . __( 'Something Went Wrong,Please Try Again!', ThrowsSpamAway::DOMAIN ) . "</strong></p>";
						}
					}
				}
			}
		?>
		<h4><?php _e( 'Number of all Comments', ThrowsSpamAway::DOMAIN ); ?> : <?php echo $c_all; ?></h4>
		<h4><?php _e( 'Number of Spam Comments', ThrowsSpamAway::DOMAIN ); ?> : <?php echo $s_count; ?></h4>
		
		<?php if ( $c_all > 0 ) { ?>
			<form name="dce" method="post">
				<?php wp_nonce_field(); ?>
				<input type="hidden" name="c_all" value="a">
				<label><input type="checkbox" name="tsa_kind"
				              value="s"> <?php _e( 'Delete all spam comments', ThrowsSpamAway::DOMAIN ); ?></label>
				<p class="submit">
					<input type="submit" name="Submit"
					       value="<?php _e( 'Delete all spam comments', ThrowsSpamAway::DOMAIN ); ?>"
					       onclick="return confirm('<?php _e( 'I will send. Is it OK?', ThrowsSpamAway::DOMAIN ); ?>');">
				</p>
			</form>
			<?php
		} else {
			echo "<p><strong>" . __( 'All comments have been deleted.', ThrowsSpamAway::DOMAIN ) . "</strong></p>";
		}
			if ( $param_c_pend == 'p' && $param_pend == 'p' && $verified ) {
				if ( $_pend_delete_flg === true ) {
					echo "<p style='color:green'><strong> " . __( 'All Pending comments have been deleted.', ThrowsSpamAway::DOMAIN ) . "</strong></p>";
				} else {
					echo "<p style='color:red'><strong>" . __( 'Something Went Wrong,Please Try Again!', ThrowsSpamAway::DOMAIN ) . "</strong></p>";
				}
			} else {
				?>
				<h4><?php _e( 'Number of Pending Comments', ThrowsSpamAway::DOMAIN ); ?> : <?php echo $c_pend; ?></h4>
				
				<?php if ( $c_pend > 0 ) { ?>
					<form name="dcep" method="post"
					      action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>">
						<?php wp_nonce_field(); ?>
						<input type="hidden" name="c_pend" value="p">
						<label><input type="checkbox" name="pend"
						              value="p"> <?php _e( 'Delete all pending comments', ThrowsSpamAway::DOMAIN ); ?></label>
						<p class="submit">
							<input type="submit" name="Submit"
							       value="<?php _e( 'Delete all pending Comments', ThrowsSpamAway::DOMAIN ); ?>">
						</p>
					</form>
					<?php
				} else {
					?>
					<p>
						<strong><?php _e( 'All pending comments have been deleted.', ThrowsSpamAway::DOMAIN ); ?></strong>
					</p>
					<?php
				}
			}
		?>
		<h4><?php _e( 'Warning : Once Comment Deleted can\'t be restored!', ThrowsSpamAway::DOMAIN ); ?></h4>
	</div>
	<?php
}

// プロセス
tsa_comment_main();