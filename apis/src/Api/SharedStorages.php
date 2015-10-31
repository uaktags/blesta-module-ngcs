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

use NGCSv1\Entity\SharedStorage as SharedEntity;
use NGCSv1\Entity\Server as ServerEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class SharedStorages extends AbstractApi
{
    /**
     * @return SharedEntity[]
     */
    public function getAll()
    {
        $images = $this->adapter->get(sprintf('%s/shared_storages', self::ENDPOINT));

        return array_map(function ($image) {
            return new SharedEntity($image);
        }, $images);
    }

    /**
     * @param int $id
     *
     * @return SharedEntity
     */
    public function getById($id)
    {
        $image = $this->adapter->get(sprintf('%s/shared_storages/%s', self::ENDPOINT, $id));

        return new SharedEntity($image);
    }

    /**
     * @param $name
     * @param null $description
     * @param int $size
     * @return string
     */
    public function create($name, $description = Null, $size= 200)
    {
        $data = [
            'name' => $name,
            'description' => $description,
            'size' => $size
        ];
        return $this->adapter->post(sprintf('%s/shared_storages', self::ENDPOINT), $data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->adapter->delete(sprintf('%s/shared_storages/%s', self::ENDPOINT, $id));
    }

    /**
     * @param $id
     * @param $name
     * @param $description
     * @param $size
     * @return string
     */
    public function modify($id, $name, $description, $size)
    {
        $content=[];
        if($name!==false)
            $content['name'] = $name;
        if($description!==false)
            $content['description'] = $description;
        if($size!==false)
            $content['size']=$size;
        return $this->adapter->put(sprintf('%s/shared_storages/%s', self::ENDPOINT, $id), $content);
    }

    /**
     * @param $id
     * @param $name
     * @return string
     */
    public function modifyName($id, $name)
    {
        return $this->modify($id, $name, false, false);
    }

    /**
     * @param $id
     * @param $description
     * @return string
     */
    public function modifyDescription($id, $description)
    {
        return $this->modify($id, false, $description, false);
    }

    /**
     * @param $id
     * @param $size
     * @return string
     */
    public function modifySize($id, $size)
    {
        return $this->modify($id, false, false, $size);
    }

    /**
     * @param $id
     * @return string
     */
    public function getServers($id)
    {
        return $this->adapter->get(sprintf('%s/shared_storages/%s/servers', self::ENDPOINT, $id));
    }

    /**
     * @param $id
     * @param array $servers
     * @return string
     */
    public function attachServer($id, $servers = [])
    {
        $data = [
            'servers' => $servers
        ];
        return $this->adapter->post(sprintf('%s/shared_storages/%s/servers', self::ENDPOINT, $id), $data);
    }

    /**
     * @param $ssid
     * @param $sid
     * @return mixed
     */
    public function removeAttachedServer($ssid, $sid)
    {
        return $this->adapter->delete(sprintf('%s/shared_storages/%s/servers/%s', self::ENDPOINT, $ssid, $sid));
    }

    /**
     * @param $id
     * @param $sid
     * @return array
     */
    public function getAttachedServerByID($id, $sid)
    {
        $servers = $this->adapter->get(sprintf('%s/shared_storages/%s/servers/%s', self::ENDPOINT, $id, $sid));

        return array_map(function ($server) {
            return new ServerEntity($server);
        }, $servers);
    }

    /**
     * @return string
     */
    public function getCredentials()
    {
        return $this->adapter->get(sprintf('%s/shared_storages/access', self::ENDPOINT));
    }

    /**
     * @param $password
     * @return string
     */
    public function changePassword($password)
    {
        return $this->adapter->put(sprintf('%s/shared_storages/access', self::ENDPOINT), ['password'=>$password]);
    }
}
