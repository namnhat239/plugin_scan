<?php
/**
 * Content Popup Template
 *
 * @package OpinionStageWordPressPlugin
 */

defined( 'ABSPATH' ) || die();

require_once plugin_dir_path( __FILE__ ) . '../includes/opinionstage-client-session.php';

$opinionstage_user_logged_in = opinionstage_user_logged_in();
$os_options                  = (array) get_option( OPINIONSTAGE_OPTIONS_KEY );
$is_my_items_admin_page      = opinionstage_is_my_items_admin_page();
// Note: all html put here (not moved to js build system) in order to preserve ability to use WordPress translate APIs.
?>
<style type="text/css">
	.content__image {
			background-image: url(<?php echo esc_url( plugins_url( '', dirname( __FILE__ ) ) . '/admin/images/form-not-found.png' ); ?>);
			background-repeat: no-repeat;
			background-size: cover;
		}
</style>
<template data-opinionstage-content-popup-template>
	<div class='opinionstage-content-popup-contents' data-opinionstage-content-popup data-opinionstage-client-logged-in="<?php echo esc_attr( $opinionstage_user_logged_in ); ?>">
		<header class='header'>
			<div class='header__container <?php echo $is_my_items_admin_page ? 'mw-1000' : ''; ?>'>
				<div class='header__logo'>
					<a href='<?php echo esc_url( OPINIONSTAGE_SERVER_BASE ); ?>' target='_blank'>
						<img src='<?php echo esc_url( plugins_url( 'admin/images/os-logo-header.png', plugin_dir_path( __FILE__ ) ) ); ?>'>
					</a>
				</div>
				<div class='header__action'>
					<?php if ( $is_my_items_admin_page ) { ?>
						<div class="opinionstage-connectivity-status"><?php echo esc_html( $os_options['email'] ); ?>
							<form method="POST" action="<?php echo esc_url( get_admin_url( null, 'admin.php?page=' . OPINIONSTAGE_DISCONNECT_PAGE ) ); ?>" class="opinionstage-connect-form">
								<button class="opinionstage-disconnect" type="submit"><?php esc_html_e( 'Disconnect', 'social-polls-by-opinionstage' ); ?></button>
							</form>
						</div>
					<?php } else { ?>
						<div class='btn-close' @click="closePopup">&#x2715;</div>
					<?php } ?>
				</div>
			</div>
		</header>
		<section>
			<popup-content
				:client-is-logged-in="isClientLoggedIn"
				:modal-is-opened="isModalOpened"
				:is-my-items-page="isMyItemsPage"
				:widget-type="widgetType"
				@widget-selected="selectWidgetAndExit"
				client-widgets-url="<?php echo esc_url( OPINIONSTAGE_CONTENT_POPUP_CLIENT_WIDGETS_API ); ?>"
				client-widgets-has-new-url="<?php echo esc_url( OPINIONSTAGE_CONTENT_POPUP_CLIENT_WIDGETS_API_RECENT_UPDATE ); ?>"
				access-key="<?php echo esc_js( opinionstage_user_access_token() ); ?>"
				plugin-version="<?php echo esc_js( OPINIONSTAGE_WIDGET_VERSION ); ?>"
			>
			</popup-content>
		</section>
	</div>
</template>

<template id="opinionstage-widget-list">
	<?php require dirname( __FILE__ ) . '/template-parts/vue/widget-list.php'; ?>
</template>

<template id="opinionstage-popup-content">
	<div v-if="clientIsLoggedIn">
		<div v-if="newWidgetsAvailable" class="notification-container">
			<notification v-on:update-btn-click='reloadAndRestartCheckingForUpdates'>
		</div>
		<div v-if="widgets == undefined">
			<p class="failed-load-items-request"><?php esc_html_e( 'An error occurred while loading the items.', 'social-polls-by-opinionstage' ); ?>
				<a href="<?php echo esc_url( OPINIONSTAGE_LIVE_CHAT_URL_UTM ); ?>" target="_blank"><?php esc_html_e( 'Please contact our chat support for help', 'social-polls-by-opinionstage' ); ?></a></p>
		</div>
		<template v-else>
			<div v-if="!dataLoading && isMyItemsPage && widgets !== undefined && widgets.length === 0 && searchCriteria.type === 'all' && searchCriteria.title === ''">
				<?php require dirname( __FILE__ ) . '/template-parts/vue/create-screen.php'; ?>
			</div>
			<widget-list v-else
						 :widgets='widgets'
						 :pre-selected-widget-type='searchCriteria.type'
						 :data-loading='dataLoading'
						 :show-search='true'
						 :no-more-data='noMoreData'
						 @widget-selected="widgetSelected"
						 @widgets-search-update='reloadData'
						 @load-more-widgets='appendData'
			>
		</template>
	</div>
	<div class='page-content' v-else>
			<h1 class='main-title'>
				<b><?php esc_html_e( 'Connect WordPress with Opinion Stage to get started', 'social-polls-by-opinionstage' ); ?></b>
			</h1>
			<a id="os-start-login" data-os-login="" href="<?php echo esc_url( admin_url( 'admin.php?page=opinionstage-getting-started' ) ); ?>" class="opinionstage-blue-btn"><?php esc_html_e( 'CONNECT', 'social-polls-by-opinionstage' ); ?></a>
	</div>
</template>

<template id="opinionstage-notification">
	<div class="opinionstage-section-notification">
		<p class="opinionstage-section-notification__title">
			<?php esc_html_e( 'Your content has been updated, please click the button to update your view', 'social-polls-by-opinionstage' ); ?>
		</p>
		<div class="opinionstage-section-notification__controls">
			<button class='btn-blue' @click="initiateUpdate"><?php esc_html_e( 'Update view', 'social-polls-by-opinionstage' ); ?></button>
		</div>
	</div>
</template>

