<?php
/**
 * Widgets List template.
 *
 * @package OpinionStageWordPressPlugin
 */

?>
<div class='page-content'>
	<div class='content-actions'>
		<div class='content-actions__left'>
			<h1 class="main-title"><?php esc_html_e( 'My Items', 'social-polls-by-opinionstage' ); ?></h1>
		</div>
		<div class="content-actions__right">
			<div class='filter'>
				<div class="dropdown dropdown_items">
					<button class="dropbtn"><span>{{ selectedWidgetTitle }}</span></button>
					<div class="dropdown-content">
						<div class='filter__itm'
							 @click="selectWidgetType('all')"
							 :class="{ active: selectedWidgetType === 'all' }"
						><?php esc_html_e( 'all items', 'social-polls-by-opinionstage' ); ?></div>
						<div class='filter__itm'
							 @click="selectWidgetType('poll')"
							 :class="{ active: selectedWidgetType === 'poll' }"
						><?php esc_html_e( 'poll', 'social-polls-by-opinionstage' ); ?></div>
						<div class='filter__itm'
							 @click="selectWidgetType('survey')"
							 :class="{ active: selectedWidgetType === 'survey' }"
						><?php esc_html_e( 'survey', 'social-polls-by-opinionstage' ); ?></div>
						<div class='filter__itm'
							 @click="selectWidgetType('trivia')"
							 :class="{ active: selectedWidgetType === 'trivia' }"
						><?php esc_html_e( 'trivia quiz', 'social-polls-by-opinionstage' ); ?></div>
						<div class='filter__itm'
							 @click="selectWidgetType('outcome')"
							 :class="{ active: selectedWidgetType === 'outcome' }"
						><?php esc_html_e( 'personality quiz', 'social-polls-by-opinionstage' ); ?></div>
						<div class='filter__itm'
							 @click="selectWidgetType('form')"
							 :class="{ active: selectedWidgetType === 'form' }"
						><?php esc_html_e( 'standard form', 'social-polls-by-opinionstage' ); ?></div>
					</div>
				</div>
			</div>
			<div class="os-search" :class='{ hidden: !showSearch }'>
				<input
					class='os-search__input'
					placeholder='Search...'
					type='search'
					v-model='widgetTitleSearch'
				>
				<span class="os-search__icon icon-os-plugin-common-search"></span>
			</div>
			<div class="content-actions__sep"></div>

			<a href="<?php echo esc_url( add_query_arg( 'w_type', 'all', OPINIONSTAGE_REDIRECT_CREATE_WIDGET_API_UTM ) ); ?>" class="opinionstage-blue-btn" target="_blank"><?php esc_html_e( 'Create', 'social-polls-by-opinionstage' ); ?></a>
		</div>
	</div>
	<div class='content__list'>
		<div v-if='hasData'>
			<div class='content__itm' v-for="widget in widgets">
				<a target="_blank" :href='widget.landingPageUrl'>
					<div class='content__image'>
						<img :src='widget.imageUrl'>
						<div class='content__label'>{{ widget.type }}</div>
					</div>
				</a>
				<div class='content__info'>
					<span v-if="widget.isDraft"
						  class="opinionstage-draft"><?php esc_html_e( 'draft', 'social-polls-by-opinionstage' ); ?></span>
					<a target="_blank" :href='widget.editUrl'>
						<span class="content__info-title">{{ widget.title }}</span>
						<div class="content__info-details">
							<span class="os-icon-plugin icon-os-common-date"></span>
							{{ widget.updatedAt | moment('DD MMMM YYYY') }}
							<span v-if="widget.isClosed">
							<span class="opinionstage-with-separator">
								<span class="icon-os-status-closed"></span>
								<?php esc_html_e( 'closed', 'social-polls-by-opinionstage' ); ?>
							</span>
						</span>
							<span v-if="widget.isOpen">
							<span class="opinionstage-with-separator">
								<span class="icon-os-status-open"></span>
								<?php esc_html_e( 'open', 'social-polls-by-opinionstage' ); ?>
							</span>
						</span>
						</div>
					</a>
				</div>
				<?php if ( $is_my_items_admin_page ) { ?>
					<div class="opinionstage-item-action-container">
						<a href="#" @click="select(widget)"
						   class="opinionstage-blue-bordered-btn opinionstage-edit-content "><?php esc_html_e( 'Add To Site', 'social-polls-by-opinionstage' ); ?></a>
						<a :href='widget.editUrl' class="opinionstage-blue-bordered-btn opinionstage-edit-content "
						   target="_blank"><?php esc_html_e( 'Edit', 'social-polls-by-opinionstage' ); ?></a>
						<a :href='widget.statsUrl' class="opinionstage-blue-bordered-btn opinionstage-edit-content "
						   target="_blank"><?php esc_html_e( 'Results', 'social-polls-by-opinionstage' ); ?></a>
					</div>
				<?php } else { ?>
					<div class='content__links'>
						<button class='popup-content-btn content__links-itm'
								@click="select(widget)"><?php $is_my_items_admin_page ? esc_html_e( 'Add to site', 'social-polls-by-opinionstage' ) : esc_html_e( 'insert', 'social-polls-by-opinionstage' ); ?></button>
						<div class="dropdown dropdown-popup-action">
							<div class="popup-action popup-content-btn"></div>
							<div class="popup-action-dropdown dropdown-content">
								<a class='content__links-itm' target="_blank"
								   :href='widget.landingPageUrl'><?php esc_html_e( 'view', 'social-polls-by-opinionstage' ); ?></a>
								<a class='content__links-itm' target="_blank" :href='widget.editUrl'
								   v-show="!widget.template"><?php esc_html_e( 'edit', 'social-polls-by-opinionstage' ); ?></a>
								<a class='content__links-itm' target="_blank" :href='widget.statsUrl'
								   v-show="!widget.template"><?php esc_html_e( 'Results', 'social-polls-by-opinionstage' ); ?></a>
							</div>
						</div>
					</div>

				<?php } ?>
			</div>
			<div class='content__loading' v-if='dataLoading'>
				<?php esc_html_e( 'loading...', 'social-polls-by-opinionstage' ); ?>
			</div>
			<div v-else>
				<button
					class='btn-show-more'
					v-if='!noMoreData'
					@click='showMore'
				><?php esc_html_e( 'Click for more', 'social-polls-by-opinionstage' ); ?></button>
			</div>
		</div>
		<div v-else>
			<?php esc_html_e( 'No items found', 'social-polls-by-opinionstage' ); ?>
		</div>
	</div>
	<div class="selected-draft" v-if="selectedDraftWidget.editUrl">
		<div class="selected-draft__container">
			<div>
				<span id="opinionstage-dialog-close" class="opinionstage-close" @click="selectedDraftWidget = !selectedDraftWidget"></span>
				<div class="selected-draft__message">
					<p>
						<?php
						printf(
							'%s <a :href="selectedDraftWidget.editUrl" target="_ blank">%s</a> %s',
							esc_html__( 'Widget is not published yet. Please', 'social-polls-by-opinionstage' ),
							esc_html__( 'edit', 'social-polls-by-opinionstage' ),
							esc_html__( 'the widget to publish it', 'social-polls-by-opinionstage' )
						);
						?>
					</p>
					<p>
						<?php esc_html_e( 'Need Help?', 'social-polls-by-opinionstage' ); ?>
						<a href="<?php echo esc_url( OPINIONSTAGE_LIVE_CHAT_URL_UTM ); ?>" target="_blank"><?php esc_html_e( 'Contact Us' ); ?></a></p>
				</div>
			</div>
		</div>
	</div>
</div>
