<?php
namespace Scjc\Lib;


use Hyperf\Contract\NormalizerInterface;
use Scjc\Lib\Listener\RegisterProtocolListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'aspects' => [
                RpcAspect::class,
            ]
        ];
    }
}