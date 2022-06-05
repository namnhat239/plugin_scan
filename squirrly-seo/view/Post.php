<?php
defined('ABSPATH') || die('Cheatin\' uh?');

SQ_Classes_ObjController::getClass('SQ_Models_LiveAssistant')->init();
$view->show_view('Blocks/LiveAssistant');
