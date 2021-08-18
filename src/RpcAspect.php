<?php


namespace Hyperf\RpcConvert;


use Hyperf\Di\Aop\AroundInterface;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Framework\Bootstrap\WorkerStartCallback;
use Hyperf\JsonRpc\HttpServer;
use Hyperf\JsonRpc\TcpServer;
use Hyperf\RpcClient\AbstractServiceClient;
use Hyperf\RpcClient\Client;
use Hyperf\ServiceGovernanceConsul\ConsulDriver;
use Hyperf\Tracer\SpanStarter;
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
        if ($proceedingJoinPoint->className == Client::class && $proceedingJoinPoint->methodName === 'send') {
            return $this->ClientSend($proceedingJoinPoint);
        } else if ($proceedingJoinPoint->className == SimpleNormalizer::class && $proceedingJoinPoint->methodName === 'denormalize') {
            $result = $proceedingJoinPoint->process();

            //        foreach ($definitions as $pos => $row) {
//            if ($row->getName() && strpos($row->getName(), "\\")) {
//                //是对象
//                try {
//                    $arguments[$pos] = arrayToEntity($row->getName(), json_decode(json_encode($arguments[$pos])));
//                } catch (\Throwable $exception) {
//                    print_r($exception->getMessage());
//                    throw $exception;
//                }
//
//            }
//        }
            return $result;
        }
    }

    private function ClientSend(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $result = $proceedingJoinPoint->process();
        //根据协程栈 找到具体调用的rpc方法，检测返回值是否为对象，如果为的对象则进行准换
        $swooleTrace = \Swoole\Coroutine::getBackTrace(Coroutine::id(), DEBUG_BACKTRACE_IGNORE_ARGS, 15);
        for ($i = 1; $i < count($swooleTrace); $i++) {
            $row = $swooleTrace[$i];
            $ref = new \ReflectionClass($row['class']);
            if ($ref->isSubclassOf(\Hyperf\RpcClient\Proxy\AbstractProxyService::class)) {
                $method = $ref->getMethod($row['function']);
                if ($method->hasReturnType() && strpos($method->getReturnType()->getName(), "Scjc\\Lib\\") === 0) {
                    $returnType = $method->getReturnType()->getName();
                    $mapper = new \JsonMapper();
                    $result['result'] = $mapper->map(json_decode(json_encode($result['result'])), new $returnType);
                }
            }
            unset($ref);
        }
        return $result;
    }
}