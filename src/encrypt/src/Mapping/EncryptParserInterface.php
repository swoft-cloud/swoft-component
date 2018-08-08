<?php
/**
 * Created by PhpStorm.
 * User: zcm
 * Date: 2018/7/26
 * Time: 15:46
 */

namespace Swoft\Encrypt\Mapping;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface EncryptParserInterface
 * @package Swoft\Encrypt\Mapping
 */
interface EncryptParserInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    public function parser(ServerRequestInterface $request): ServerRequestInterface;
}