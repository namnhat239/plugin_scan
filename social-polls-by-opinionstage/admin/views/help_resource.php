<?php
/**
 * Opinionstage Get Help Admin page
 *
 * @package OpinionStageWordPressPlugin
 */

defined( 'ABSPATH' ) || die();

$videos_data = [
        [
            'title' => __('How to Use the Plugin', 'social-polls-by-opinionstage'),
            'title_link' => 'https://help.opinionstage.com/en/articles/2557183-how-to-use-the-wordpress-plugin/',
            'video_id' => 'DMcosYCBFDs',
        ],
        [
            'title' => __('How to Create a Quiz', 'social-polls-by-opinionstage'),
            'video_id' => 'PPNIezl_wu0',
        ],
        [
            'title' => __('How to Create a Poll', 'social-polls-by-opinionstage'),
            'video_id' => 'xFUAwszhiuo',
        ],
        [
            'title' => __('How to Create a Survey', 'social-polls-by-opinionstage'),
            'video_id' => 'sUcGbCGwn_Q',
        ],
]
?>
<div id="opinionstage-content">
	<div class="opinionstage-header-wrapper">
		<div class="opinionstage-logo-wrapper">
			<div class="opinionstage-logo"></div>
			<div class="opinionstage-connectivity-status"><?php echo esc_html( $os_options['email'] ); ?>
				<form method="POST" action="<?php echo esc_url( get_admin_url( null, 'admin.php?page=' . OPINIONSTAGE_DISCONNECT_PAGE ) ); ?>" class="opinionstage-connect-form">
					<button class="opinionstage-disconnect" type="submit">Disconnect</button>
				</form>
			</div>
		</div>
	</div>
	<?php if ( $os_client_logged_in ) { ?>
        <div class="opinionstage-tutorials-and-help">
            
            <div class="opinionstage-tutorials-and-help__hero opinionstage-bg-blue">
                <div class="opinionstage-tutorials-and-help__container">
                    <h1 class="opinionstage-tutorials-and-help__hero__header"><?php _e('Tutorials & Help', 'social-polls-by-opinionstage'); ?></h1>
                    
                    <div class="opinionstage-tutorials-and-help__buttons">
                        <a href="https://help.opinionstage.com" class="opinionstage-tutorials-and-help__button opinionstage-tutorials-and-help__button__help-center opinionstage-blue-btn" target="_blank">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 17C11 17.5523 11.4477 18 12 18C12.5523 18 13 17.5523 13 17C13 16.4477 12.5523 16 12 16C11.4477 16 11 16.4477 11 17ZM12 2C10.6868 2 9.38642 2.25866 8.17317 2.7612C6.95991 3.26375 5.85752 4.00035 4.92893 4.92893C3.05357 6.8043 2 9.34784 2 12C2 14.6522 3.05357 17.1957 4.92893 19.0711C5.85752 19.9997 6.95991 20.7362 8.17317 21.2388C9.38642 21.7413 10.6868 22 12 22C14.6522 22 17.1957 20.9464 19.0711 19.0711C20.9464 17.1957 22 14.6522 22 12C22 10.6868 21.7413 9.38642 21.2388 8.17317C20.7362 6.95991 19.9997 5.85752 19.0711 4.92893C18.1425 4.00035 17.0401 3.26375 15.8268 2.7612C14.6136 2.25866 13.3132 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM12 6C10.9391 6 9.92172 6.42143 9.17157 7.17157C8.65947 7.68368 8.30056 8.32034 8.12435 9.01035C7.98769 9.54547 8.44772 10 9 10C9.55228 10 9.98007 9.52581 10.2448 9.04113C10.3354 8.87534 10.4498 8.72178 10.5858 8.58579C10.9609 8.21071 11.4696 8 12 8C12.5304 8 13.0391 8.21071 13.4142 8.58579C13.7893 8.96086 14 9.46957 14 10C14 11.7695 11.6517 11.7777 11.1104 14.0069C10.98 14.5436 11.4477 15 12 15C12.5523 15 12.9768 14.5295 13.2172 14.0323C13.9066 12.6067 16 12.0884 16 10C16 8.93913 15.5786 7.92172 14.8284 7.17157C14.0783 6.42143 13.0609 6 12 6Z" fill="white"/>
                            </svg>
                            <span><?php _e('Go To Help Center', 'social-polls-by-opinionstage'); ?></span>
                        </a>
                        <a href="<?php echo esc_url(opinionstage_utm_url('dashboard/content/templates')); ?>" class="opinionstage-tutorials-and-help__button opinionstage-tutorials-and-help__button__templates opinionstage-blue-btn" target="_blank">
                            <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="1" y="1" width="19" height="18" rx="1" stroke="white" stroke-width="2"/>
                                <rect x="5" y="5" width="11" height="6" stroke="white" stroke-width="2"/>
                                <rect x="4.5" y="14.5" width="12" height="1" rx="0.5" stroke="white"/>
                            </svg>
                            <span><?php _e('Templates & Examples', 'social-polls-by-opinionstage'); ?></span>
                        </a>
                        <a href="<?php echo esc_url(opinionstage_utm_url('live-chat/')); ?>" class="opinionstage-tutorials-and-help__button opinionstage-tutorials-and-help__button__live-chat opinionstage-blue-btn" target="_blank">
                            <svg width="25" height="23" viewBox="0 0 25 23" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M20.819 1.5605e-05H3.90347C1.74784 1.5605e-05 0 1.74786 0 3.90369V13.7417C0 15.8977 1.74784 17.6456 3.90347 17.6456H14.4043C15.6827 18.7951 17.0711 20.0397 18.1989 21.0387C19.1075 21.8442 19.3234 22.1227 19.6173 22.1227C19.7034 22.1227 19.7965 22.0986 19.9154 22.0565C20.4405 21.8729 20.6026 21.6087 20.6026 20.6995V17.6456H20.8192C22.9752 17.6456 24.7227 15.8977 24.7227 13.7417V3.90367C24.7227 1.74784 22.9752 0 20.8192 0L20.819 1.5605e-05ZM20.8192 1.95191C21.8957 1.95191 22.7709 2.82754 22.7709 3.90362V13.7416C22.7709 14.8179 21.8957 15.6938 20.8192 15.6938H18.6508V18.829C17.8232 18.0917 16.8383 17.2092 15.7094 16.1944L15.1529 15.6938H3.90385C2.82777 15.6938 1.95214 14.8179 1.95214 13.7416V3.90362C1.95214 2.82754 2.82777 1.95191 3.90385 1.95191H20.8194"
                                      fill="white"/>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M19.1928 5.85522H5.53067C4.99162 5.85522 4.55469 6.29213 4.55469 6.83121C4.55469 7.37026 4.9916 7.80698 5.53067 7.80698H19.1928C19.7318 7.80698 20.1688 7.37028 20.1688 6.83121C20.1688 6.29216 19.7318 5.85522 19.1928 5.85522Z"
                                      fill="white"/>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M13.3378 10.0842H5.53067C4.99162 10.0842 4.55469 10.5211 4.55469 11.06C4.55469 11.5991 4.9916 12.036 5.53067 12.036H13.3378C13.8769 12.036 14.3136 11.5991 14.3136 11.06C14.3136 10.5212 13.8769 10.0842 13.3378 10.0842Z"
                                      fill="white"/>
                            </svg>
                            <span><?php _e('Live Chat Support', 'social-polls-by-opinionstage'); ?></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="opinionstage-tutorials-and-help__videos">
                <div class="opinionstage-tutorials-and-help__container">
                    <?php foreach ($videos_data as $videos_datum) {
                        ?>
                        <div class="opinionstage-tutorials-and-help__video">
                            <?php if (isset($videos_datum['title_link'])) { ?>
                            <a href="<?php echo esc_url($videos_datum['title_link']) ?>" target="_blank" class="opinionstage-tutorials-and-help__video__title__link">
                                <?php } ?>
                                <h3 class="opinionstage-tutorials-and-help__video__title"><?php echo $videos_datum['title']; ?></h3>
                                <?php if (isset($videos_datum['title_link'])) { ?>
                            </a>
                        <?php } ?>
                            <iframe width="600" height="337"
                                    src="https://www.youtube.com/embed/<?php echo esc_attr($videos_datum['video_id']) ?>?controls=0&showinfo=0"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                        <?php
                    } ?>
                </div>
            </div>
        </div>
	<?php } ?>
</div>
