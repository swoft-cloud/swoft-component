<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Testing;

class SwooleRequest extends \Swoole\Http\Request
{
    public $get;

    public $post;

    public $header;

    public $server;

    public $cookie;

    public $files;

    public $fd;

    private $testContent;

    /**
     * 获取非urlencode-form表单的POST原始数据
     * @return string
     */
    public function rawContent()
    {
        return $this->testContent;
    }

    public function setRawContent($content)
    {
        $this->testContent = $content;
    }
}
