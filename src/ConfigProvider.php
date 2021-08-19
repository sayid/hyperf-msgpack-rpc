<?php
namespace Hyperf\MsgPackRpc;


use Hyperf\Contract\NormalizerInterface;
use Hyperf\RpcConvert\RpcAspect;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'aspects' => [
                RpcAspect::class
            ]
        ];
    }
}