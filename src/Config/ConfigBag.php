<?php

namespace Climb\Config;

use Climb\Bag\Bag;
use Climb\Exception\AppException;

class ConfigBag extends Bag
{
    /**
     * @var string
     */
    private string $configPath;

    /**
     * @param string     $configPath
     * @param array|null $data
     */
    public function __construct(string $configPath, ?array $data = null)
    {
        parent::__construct($data);
        $this->configPath = $configPath;
    }

    /**
     * @param      $item
     * @param bool $required
     *
     * @return false|mixed
     *
     * @throws AppException
     */
    public function get($item, bool $required = false)
    {
        if ($this->has($item)) {
            return $this->bag[$item];
        }

        if ($required) {
            throw new AppException(
                AppException::TYPE_CONFIG,
                'Required config parameter not found',
                sprintf('Config path: "%s", Parameter: "%s"', $this->configPath, $item)
            );
        }

        return false;
    }
}
