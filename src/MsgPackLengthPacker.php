<?php


namespace Hyperf\MsgPackRpc;


use Hyperf\Contract\PackerInterface;

class MsgPackLengthPacker implements PackerInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $length;

    protected $defaultOptions = [
        'package_length_type' => 'N',
        'package_body_offset' => 4,
    ];

    public function __construct(array $options = [])
    {
        $options = array_merge($this->defaultOptions, $options['settings'] ?? []);

        $this->type = $options['package_length_type'];
        $this->length = $options['package_body_offset'];
    }

    public function pack($data): string
    {
        $data = msgpack_pack($data);
        return pack($this->type, strlen($data)) . $data;
    }

    public function unpack(string $data)
    {
        $data = substr($data, $this->length);
        if (! $data) {
            return null;
        }
        return msgpack_unpack($data, true);
    }
}
