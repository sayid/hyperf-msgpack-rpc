<?php


namespace Hyperf\RpcConvert;


use Hyperf\Di\Aop\AroundInterface;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\RpcClient\Client;
use Hyperf\Utils\Coroutine;
use Hyperf\Utils\Serializer\SimpleNormalizer;

class RpcAspect implements AroundInterface
{
    public $classes = [
        SimpleNormalizer::class . '::denormalize', //服务器端接收到数据后参数转化
        Client::class . '::send',
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
       if ($proceedingJoinPoint->className == ProtocolManager::class && $proceedingJoinPoint->methodName === 'getPacker') {
            return RpcPacker::class;
        }
    }
}