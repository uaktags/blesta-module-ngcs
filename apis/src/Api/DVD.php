<?php

/*
 * This file is part of the NGCSv1 library.
 *
 * (c) Tim Garrity <timgarrity89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NGCSv1\Api;

use NGCSv1\Entity\DVD as DVDEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class DVD extends AbstractApi
{
    /**
     * @return dvdEntity[]
     */
    public function getAll()
    {
        $servers = $this->adapter->get(sprintf('%s/dvd_isos', self::ENDPOINT));

        return array_map(function ($server) {
            return new DVDEntity($server);
        }, $servers);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return DVDEntity
     */
    public function getById($id)
    {
        $server = $this->adapter->get(sprintf('%s/dvd_isos/%s', self::ENDPOINT, $id));
        return new DVDEntity($server);
    }
}
