<?php


namespace Hyperf\MsgPackRpc;


use Hyperf\Di\Aop\AroundInterface;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Rpc\ProtocolManager;
use Hyperf\RpcClient\Client;
use Hyperf\Utils\Coroutine;
use Hyperf\Utils\Serializer\SimpleNormalizer;

class Aspect implements AroundInterface
{
    public $classes = [
        ProtocolManager::class . '::getPacker', //服务器端接收到数据后参数转化
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
       if ($proceedingJoinPoint->className == ProtocolManager::class && $proceedingJoinPoint->methodName === 'getPacker') {
            return RpcPacker::class;
        }
    }
}