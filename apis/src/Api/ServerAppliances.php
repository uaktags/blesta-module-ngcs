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

use NGCSv1\Entity\Appliance as ApplianceEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class ServerAppliances extends AbstractApi
{
    /**
     * @return applicanceEntity[]
     */
    public function getAll()
    {
        $servers = $this->adapter->get(sprintf('%s/server_appliances', self::ENDPOINT));

        return array_map(function ($server) {
            return new ApplianceEntity($server);
        }, $servers);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return applicanceEntity
     */
    public function getById($id)
    {
        $server = $this->adapter->get(sprintf('%s/server_appliances/%s', self::ENDPOINT, $id));
        return new ApplianceEntity($server);
    }
}
