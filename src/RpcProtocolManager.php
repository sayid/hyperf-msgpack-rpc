<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Scjc\Interfaces;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Rpc\ProtocolManager;
use Hyperf\Utils\Str;
use InvalidArgumentException;

class RpcProtocolManager extends ProtocolManager
{
    /**
     * @var \Hyperf\Contract\ConfigInterface
     */
    private $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        parent::__construct($config);
    }

    public function getPacker(string $name): string
    {
        return $this->getTarget($name, 'packer');
    }

    private function getTarget(string $name, string $target)
    {
        if ($target == 'packer') {
            $this->config->set('protocols.' . Str::lower($name) . '.' . Str::lower($target), RpcPacker::class);
        }
        $result = $this->config->get('protocols.' . Str::lower($name) . '.' . Str::lower($target));
        if (! is_string($result)) {
            throw new InvalidArgumentException(sprintf('%s is not exists.', Str::studly($target, ' ')));
        }
        return $result;
    }
}
