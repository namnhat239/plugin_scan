<?php

declare(strict_types=1);

namespace ahrefs\AhrefsSeo\Disconnect_Reason;

/**
 * Disconnect reason for Google API class.
 *
 * @since 0.8.4
 */
abstract class Disconnect_Reason_Google extends Disconnect_Reason {

	protected const OPTION_NAME = 'ahrefs-seo-has-gsc-disconnect-reason';
}
