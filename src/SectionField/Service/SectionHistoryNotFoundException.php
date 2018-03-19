<?php

/*
 * This file is part of the SexyField package.
 *
 * (c) Dion Snoeijen <hallo@dionsnoeijen.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tardigrades\SectionField\Service;

class SectionHistoryNotFoundException extends \Exception
{
    public function __construct($message = "", $code = 404, \Throwable $previous = null)
    {
        $message = !empty($message) ? $message : 'Section history not found';

        parent::__construct($message, $code, $previous);
    }
}
