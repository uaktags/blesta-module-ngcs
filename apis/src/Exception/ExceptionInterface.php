<?php

/*
 * This file is part of the NGCSv1 library.
 *
 * (c) Tim Garrity <timgarrity89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NGCSv1\Exception;

/**
 * @author liverbool <nukboon@gmail.com>
 */
interface ExceptionInterface
{
    /**
     * Create an exception.
     *
     * @param string     $message
     * @param int        $code     (optional)
     * @param \Exception $previous (optional)
     *
     * @return ExceptionInterface
     */
    public static function create($message, $code = 0, \Exception $previous = null);
}
