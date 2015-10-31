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

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class IP extends AbstractEntity
{
    /**
     * @var int
     */
    public $id;
    public $ip;
    public $type;
    public $reverse_dns;
    public $firewall_policy;
    public $load_balancers;

    /**
     * @param \stdClass|array $parameters
     */
    public function build($parameters)
    {
        foreach ($parameters as $property => $value) {
            switch ($property) {
                case 'id':
                    $this->id = $value;
                    break;
                case 'ip':
                    $this->ip = $value;
                    break;
                case 'type':
                    $this->type = $value;
                    break;
                case 'reverse_dns':
                    $this->reverse_dns = $value;
                    break;
                case 'firewall_policy':
                    $this->firewall_policy = $value;
                    break;
                case 'load_balancers':
                    $this->load_balancers = $value;
                    break;
                default:
                    $this->{\NGCSv1\convert_to_camel_case($property)} = $value;
                    break;
            }
        }
    }
}