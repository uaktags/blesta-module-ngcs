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
class Image extends AbstractEntity
{
	public $id;
	public $name;
	public $os_family;
	public $os; //Windows or Linux
	public $os_version; // CentOS, Debian, Ubuntu, Red Hat, Windows2008, or Windows 2012
	public $architecture;
	public $os_image_type;
	public $type; //Images or My_Images
	public $min_hdd_size;
	public $licenses;
	public $cloudpanel_id;
	public $state;
	public $hdds;
	public $server_id;
	public $frequency; //Once, Daily, Weekly
	public $num_images;
	public $creation_date;
	
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
					
				case 'hdds':
					if(is_object($value))
					{
						array_map(function ($hdd) {
							return new Image($hdd);
						}, $value);
					}
					break;
					
                default:
                    $this->{\NGCSv1\convert_to_camel_case($property)} = $value;
                    break;
            }
        }
    }
}
