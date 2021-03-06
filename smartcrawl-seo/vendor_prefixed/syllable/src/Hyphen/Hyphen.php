<?php

namespace Smartcrawl_Vendor\Vanderlee\Syllable\Hyphen;

interface Hyphen
{
    public function joinText($parts);
    public function joinHtmlDom($parts, \DOMNode $node);
    public function stripHtml($html);
}
