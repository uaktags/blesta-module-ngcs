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
class Harddrive extends AbstractEntity
{
    /**
     * @var int
     */
    public $id;
    public $size;
    public $is_main;

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
                case 'size':
                    $this->size = $value;
                    break;
                case 'is_main':
                    $this->is_main = $value;
                    break;
                default:
                    $this->{\NGCSv1\convert_to_camel_case($property)} = $value;
                    break;
            }
        }
    }
}