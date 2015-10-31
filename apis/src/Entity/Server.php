<?php

/*
 * This file is part of the NGCSv1 library.
 *
 * (c) Tim Garrity <timgarrity89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NGCSv1\Entity;
use NGCSv1\Api\MonitoringPolicy;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class Server extends AbstractEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $cloudpanelId;

    /**
     * @var string
     */
    public $description;

    /**
     * @var date
     */
    public $creationDate;

    /**
     * @var string
     */
    public $first_password;

    /**
     * @var object
     * Get from Status Object.
     * Contains State and Percent
     */
    public $status;

    /**
     * @var object
     * Get from Hardware Object
     * Contains InstanceIDs, vCores, etc
     */
    public $hardware;

    /**
     * @var object
     */
    public $image;

    /**
     * @var string
     */
    public $dvd = 'Not Loaded';

    /**
     * @var object
     */
    public $ips;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $snapshot;

    /**
     * @var array
     */
    public $alerts = [];

    /**
     * @var
     */
    public $monitoringPolicy;

    /**
     * @var
     */
    public $privateNetworks;


    /**
     * @param \stdClass|array $parameters
     */
    public function build($parameters)
    {
        foreach ($parameters as $property => $value) {
            switch ($property) {
                case 'status':
                    if (is_object($value)) {
                        if (property_exists($value, 'state')) {
                            $this->status['state'] = $value->state;
                        }elseif(property_exists($value, 'percent')){
                            $this->status['percent']= $value->percent;
                        }
                    }
                    break;

                case 'hardware':
                    if (is_object($value)) {
                        $this->hardware = new Hardware($value);
                    }
                    break;

                case 'dvd':
                    $this->dvd = $value;
                    break;

                case 'first_password':
                    $this->first_password = $value;
                    break;

                case 'privateNetworks':
                    $this->privateNetworks = $value;
                    break;

                case 'monitoringPolicy':
                    $this->monitoringPolicy = $value;
/*
                case 'size':
                    if (is_object($value)) {
                        $this->size = new Size($value);
                    }
                    break;

                case 'region':
                    if (is_object($value)) {
                        $this->region = new Region($value);
                    }
                    break;

                case 'image':
                    if (is_object($value)) {
                        $this->image = new Image($value);
                    }
                    break;

                case 'next_backup_window':
                    $this->nextBackupWindow = new NextBackupWindow($value);
                    break;
                */
                default:
                    $this->{\NGCSv1\convert_to_camel_case($property)} = $value;
            }
        }

        /*if (is_array($this->features) && count($this->features)) {
            $this->backupsEnabled = in_array("backups", $this->features);
            $this->virtIOEnabled = in_array("virtio", $this->features);
            $this->privateNetworkingEnabled = in_array("private_networking", $this->features);
            $this->ipv6Enabled = in_array("ipv6", $this->features);
        }*/
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $this->convertDateTime($createdAt);
    }
}
