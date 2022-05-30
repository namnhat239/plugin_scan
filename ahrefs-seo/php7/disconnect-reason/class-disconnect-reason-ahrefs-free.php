<?php

declare(strict_types=1);

namespace ahrefs\AhrefsSeo\Disconnect_Reason;

use ahrefs\AhrefsSeo\Messages\Message;

/**
 * Disconnect reason for Ahrefs with Free account class.
 *
 * @since 0.8.4
 */
class Disconnect_Reason_Ahrefs_Free extends Disconnect_Reason_Ahrefs {

	/**
	 * Get message from text string
	 *
	 * @param string $token Ahrefs token.
	 * @return Message|null
	 */
	protected function text_to_message( string $token ) : ?Message {
		return Message::account_expired( $token, true );
	}
}
