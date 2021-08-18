<?php
namespace Scjc\Lib;


use Hyperf\Contract\NormalizerInterface;
use Hyperf\Database\Query\Builder;
use Hyperf\Utils\Arr;
use Scjc\Lib\Common\CodeInterface;
use Scjc\Lib\Common\HttpResponse;
use Scjc\Lib\Constant\BaseCode;
use Scjc\Lib\Interfaces\ResponseInterface;
use Scjc\Lib\Listener\RegisterProtocolListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            // 合并到  config/autoload/dependencies.php 文件
            'dependencies' => [
                NormalizerInterface::class => SimpleNormalizer::class
            ],
            // 合并到  config/autoload/annotations.php 文件
            'annotations' => [

            ],
            // 默认 Command 的定义，合并到 Hyperf\Contract\ConfigInterface 内，换个方式理解也就是与 config/autoload/commands.php 对应
            'commands' => [

            ],
            // 与 commands 类似
            'listeners' => [

            ],
            // 组件默认配置文件，即执行命令后会把 source 的对应的文件复制为 destination 对应的的文件
            'publish' => [
            ],
            // 亦可继续定义其它配置，最终都会合并到与 ConfigInterface 对应的配置储存器中
        ];
    }
}