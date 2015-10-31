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

use NGCSv1\Entity\Usage as UsageEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class Usage extends AbstractApi
{
    /**
     * @return usageEntity[]
     */
    public function getAll()
    {
        $usage = $this->adapter->get(sprintf('%s/usages', self::ENDPOINT));

        return array_map(function ($usage) {
            return new UsageEntity($usage);
        }, $usage);
    }

    
}
