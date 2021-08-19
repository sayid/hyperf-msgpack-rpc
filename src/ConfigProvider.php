<?php
namespace Hyperf\MsgPackRpc;


use Hyperf\MsgPackRpc\Listener\RegisterProtocolListener;
use Hyperf\MsgPackRpc\Listener\RegisterServiceListener;
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