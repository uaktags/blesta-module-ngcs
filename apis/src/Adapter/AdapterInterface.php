<?php

/*
 * This file is part of the NGCSv1 library.
 *
 * (c) Tim Garrity <timgarrity89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NGCSv1\Adapter;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
interface AdapterInterface
{
    /**
     * @param string $url
     *
     * @throws \RuntimeException|ExceptionInterface
     *
     * @return string
     */
    public function get($url);

    /**
     * @param string $url
     * @param array $content (optional)
     *
     * @throws \RuntimeException|ExceptionInterface
     */
    public function delete($url, $content = '');

    /**
     * @param string $url
     * @param array  $headers (optional)
     * @param string $content (optional)
     *
     * @throws \RuntimeException|ExceptionInterface
     *
     * @return string
     */
    public function put($url, $content = '');

    /**
     * @param string $url
     * @param array  $headers (optional)
     * @param string $content (optional)
     *
     * @throws \RuntimeException|ExceptionInterface
     *
     * @return string
     */
    public function post($url, $content = '');
}
