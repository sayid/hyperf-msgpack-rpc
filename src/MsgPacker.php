<?php

namespace Hyperf\MsgPackRpc;


use Hyperf\Contract\PackerInterface;

/**
 * 自定义rpc序列化 基于高性能的msgpack
 * Class RpcPacker
 * @package Scjc\Interfaces
 */
class MsgPacker implements PackerInterface
{
    /**
     * @var string
     */
    protected $eof;

    public function __construct(array $options = [])
    {
        $this->eof = $options['settings']['package_eof'] ?? "\r\n";
    }

    public function pack($data): string
    {
        return msgpack_pack($data) . $this->eof;
    }

    public function unpack(string $data)
    {
        $data = rtrim($data, $this->eof);
        return msgpack_unpack($data);
    }
}