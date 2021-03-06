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
namespace Hyperf\MsgPackRpc\Listener;

use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\JsonRpc\DataFormatter;
use Hyperf\JsonRpc\JsonRpcHttpTransporter;
use Hyperf\JsonRpc\JsonRpcTransporter;
use Hyperf\JsonRpc\Packer\JsonEofPacker;
use Hyperf\JsonRpc\Packer\JsonLengthPacker;
use Hyperf\JsonRpc\PathGenerator;
use Hyperf\MsgPackRpc\MsgPackLengthPacker;
use Hyperf\Rpc\ProtocolManager;
use Hyperf\Utils\Packer\JsonPacker;

class RegisterProtocolListener implements ListenerInterface
{
    /**
     * @var ProtocolManager
     */
    private $protocolManager;

    public function __construct(ProtocolManager $protocolManager)
    {
        $this->protocolManager = $protocolManager;
    }

    public function listen(): array
    {
        return [
            BootApplication::class,
        ];
    }

    /**
     * All official rpc protocols should register in here,
     * and the others non-official protocols should register in their own component via listener.
     */
    public function process(object $event)
    {
        $this->protocolManager->register('msgpackrpc', [
            'packer' => MsgPacker::class,
            'transporter' => JsonRpcTransporter::class,
            'path-generator' => PathGenerator::class,
            'data-formatter' => DataFormatter::class,
        ]);

        $this->protocolManager->register('msgpackrpc-tcp-length-check', [
            'packer' => MsgPackLengthPacker::class,
            'transporter' => JsonRpcTransporter::class,
            'path-generator' => PathGenerator::class,
            'data-formatter' => DataFormatter::class,
        ]);
    }
}
