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
class Hardware extends AbstractEntity
{
    /**
     * @var int
     */
    public $fixed_instance_size_id;
    public $vcore;
    public $cores_per_processor;
    public $ram;
    public $hdds;
    public $id;
    public $name;


    /**
     * @param \stdClass|array $parameters
     */
    public function build($parameters)
    {
        foreach ($parameters as $property => $value) {
            switch ($property) {
                case 'fixed_instance_size_id':
                    $this->fixed_instance_size_id= $value;
                    break;
                case 'vcore':
                    $this->vcore = $value;
                    break;
                case 'cores_per_processor':
                    $this->cores_per_processor = $value;
                    break;
                case 'ram':
                    $this->ram = $value;
                    break;
                case 'hdds':
                    $this->hdds = $value;
                    break;

                default:
                    $this->{\NGCSv1\convert_to_camel_case($property)} = $value;
                    break;
            }
        }
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $this->convertDateTime($createdAt);
    }
}
