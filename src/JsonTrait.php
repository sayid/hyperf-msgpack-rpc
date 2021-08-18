<?php


namespace Hyperf\RpcConvert;

use JsonSerializable;

trait JsonTrait
{
    /**
     * 从json字符串转化成对象
     * @param string|array $jsonString
     * @return mixed
     */
    public static function parseToObject($jsonString): self
    {
        $object = new self();
        $mapper = new \JsonMapper();
        if (is_string($jsonString)) {
            return $mapper->map(json_decode($jsonString), $object);
        } else {
            return $mapper->map($jsonString, $object);
        }
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toJson(int $options = 0)
    {
        $json = json_encode($this->toArray(), $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(json_last_error_msg());
        }
        return $json;
    }

    public function toArray()
    {
        $reflect = new \ReflectionObject($this);
        $props = $reflect->getProperties();
        $data = [];
        foreach ($props as $value_p) {
            try {
                $value_p->setAccessible(true);
                $value = $value_p->getValue($this);
                if (is_object($value) && is_subclass_of($value, JsonSerializable::class)) {
                    $value->toArray();
                } else {
                    $data[$value_p->getName()] = $value;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }
        return $data;
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}