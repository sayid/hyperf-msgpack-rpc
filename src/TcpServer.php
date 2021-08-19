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
namespace Hyperf\MsgPackRpc;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandlerDispatcher;
use Hyperf\Rpc\Protocol;
use Hyperf\Rpc\ProtocolManager;
use Hyperf\RpcServer\RequestDispatcher;
use Psr\Container\ContainerInterface;

class TcpServer extends \Hyperf\JsonRpc\TcpServer
{
    public function __construct(
        ContainerInterface $container,
        RequestDispatcher $dispatcher,
        ExceptionHandlerDispatcher $exceptionDispatcher,
        ProtocolManager $protocolManager,
        StdoutLoggerInterface $logger
    ) {
        parent::__construct($container, $dispatcher, $exceptionDispatcher, $protocolManager, $logger);
    }

    protected function initProtocol()
    {
        $protocol = 'msgpackrpc-tcp';
        if ($this->isLengthCheck()) {
            $protocol = 'msgpackrpc-tcp-length-check';
        }

        $this->protocol = new Protocol($this->container, $this->protocolManager, $protocol, $this->serverConfig);
        $this->packer = $this->protocol->getPacker();
        $this->responseBuilder = make(ResponseBuilder::class, [
            'dataFormatter' => $this->protocol->getDataFormatter(),
            'packer' => $this->packer,
        ]);
    }
}
