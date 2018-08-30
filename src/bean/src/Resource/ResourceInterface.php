<?php

namespace Swoft\Bean\Resource;

interface ResourceInterface
{
    /**
     * 获取已解析的配置beans
     */
    public function getDefinitions(): array;
}
