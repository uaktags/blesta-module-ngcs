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

use NGCSv1\Entity\PublicIP as PublicIPEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class PublicIP extends AbstractApi
{
    /**
     * GET Functions
     */
    /**
     * @return array
     */
    public function getAll()
    {
        $ips = $this->adapter->get(sprintf('%s/public_ips', self::ENDPOINT));

        return array_map(function ($ip) {
            return new PublicIPEntity($ip);
        }, $ips);
    }

    /**
     * @param $id
     * @return PublicIPEntity
     */
    public function getById($id)
    {
        $ip = $this->adapter->get(sprintf('%s/public_ips/%s', self::ENDPOINT, $id));
        return new PublicIPEntity($ip);
    }

    /**
     * End GET Functions
     */
    /**
     * DELETE Functions
     */

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->adapter->delete(sprintf('%s/public_ips/%s', self::ENDPOINT, $id));
    }

    /**
     * End DELETE Functions
     */

    /**
     * PUT Functions
     */

    /**
     * @param $id
     * @param $rdns
     * @return string
     */
    public function setRDNS($id, $rdns)
    {
        return $this->adapter->put(sprintf('%s/public_ips/%s', self::ENDPOINT, $id), ['reverse_dns'=>$rdns]);
    }

    /**
     * End PUT Functions
     */

    /**
     * POST Functions
     */

    /**
     * @param string $rdns
     * @return string
     */
    public function create($rdns=''/*, $type='IPV4' */)
    {
        return $this->adapter->post(sprintf('%s/public_ips', self::ENDPOINT), ['reverse_dns'=>$rdns, 'type'=>'IPv4']);
    }

    /**
     * End POST Functions
     */


}
