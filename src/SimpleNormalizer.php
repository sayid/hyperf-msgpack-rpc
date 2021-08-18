<?php


namespace Hyperf\RpcConvert;

/**
 * Class SimpleNormalizer
 * @package Hyperf\RpcConvert
 */
class SimpleNormalizer extends \Hyperf\Utils\Serializer\SimpleNormalizer
{
    public function denormalize($data, string $class)
    {
        $data = parent::denormalize($data, $class);
        if ($class && strpos($class, "\\")) {
            //是对象
            try {
                $mapper = new \JsonMapper();
                $data = $mapper->map($data, new $class);
            } catch (\Throwable $exception) {
                throw $exception;
            }
        }
    }
}