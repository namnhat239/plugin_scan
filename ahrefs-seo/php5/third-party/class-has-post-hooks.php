<?php

namespace ahrefs\AhrefsSeo\Third_Party;

use ahrefs\AhrefsSeo\Post_Tax;
/**
 * Register/unregisted post hooks for getting url of post.
 *
 * @since 0.9.1
 */
interface Has_Post_Hooks {

	/**
	 * Register post hooks.
	 *
	 * @return void
	 */
	public function register_post_hooks();
	/**
	 * Unregister post hooks.
	 *
	 * @return void
	 */
	public function unregister_post_hooks();
}
