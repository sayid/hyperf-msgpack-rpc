<?php
namespace Hyperf\MsgPackRpc;


use Hyperf\Contract\NormalizerInterface;
use Hyperf\JsonRpc\Listener\RegisterProtocolListener;
use Hyperf\JsonRpc\Listener\RegisterServiceListener;
use Hyperf\RpcConvert\RpcAspect;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                DataFormatter::class => DataFormatterFactory::class,
            ],
            'listeners' => [
                RegisterProtocolListener::class,
                value(function () {
                    if (class_exists(ServiceManager::class)) {
                        return RegisterServiceListener::class;
                    }
                    return null;
                }),
            ],
        ];
    }
}