<?php

/*
 * This file is part of the NGCSv1 library.
 *
 * (c) Tim Garrity <timgarrity89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NGCSv1;

use NGCSv1\Adapter\AdapterInterface;
use NGCSv1\Api\DVD;
use NGCSv1\Api\FirewallPolicy;
use NGCSv1\Api\Image;
use NGCSv1\Api\LoadBalancers;
use NGCSv1\Api\Logs;
use NGCSv1\Api\MonitorCenter;
use NGCSv1\Api\MonitoringPolicy;
use NGCSv1\Api\PrivateNetwork;
use NGCSv1\Api\PublicIP;
use NGCSv1\Api\Server;
use NGCSv1\Api\ServerAppliances;
use NGCSv1\Api\SharedStorages;
use NGCSv1\Api\Usage;
use NGCSv1\Api\Users;



/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class NGCSv1
{
    /**
     * @see http://semver.org/
     */
    const VERSION = '0.1.2-dev';

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

	public function Dvd()
	{
		return new DVD($this->adapter);
	}

	public function FirewallPolicy()
	{
		return new FirewallPolicy($this->adapter);
	}

	public function Image()
	{
		return new Image($this->adapter);
	}

	public function LoadBalancers()
	{
		return new LoadBalancers($this->adapter);
	}

	public function Logs()
	{
		return new Logs($this->adapter);
	}

	public function MonitorCenter()
	{
		return new MonitorCenter($this->adapter);
	}

	public function MonitoringPolicy()
	{
		return new MonitoringPolicy($this->adapter);
	}

	public function PrivateNetwork()
	{
		return new PrivateNetwork($this->adapter);
	}

	public function PublicIP()
	{
		return new PublicIP($this->adapter);
	}

	public function Server()
	{
		return new Server($this->adapter);
	}

	public function ServerAppliances()
	{
		return new ServerAppliances($this->adapter);
	}

	public function SharedStorages()
	{
		return new SharedStorages($this->adapter);
	}

	public function Usage()
	{
		return new Usage($this->adapter);
	}

	public function Users()
	{
		return new Users($this->adapter);
	}

	public function Appliances()
	{
		return $this->ServerAppliances();
	}

}
