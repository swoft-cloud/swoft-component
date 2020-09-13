<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Aop\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Aop\Ast\Visitor\ProxyVisitor;
use Swoft\Aop\Proxy;
use Swoft\Proxy\Ast\Parser;
use Swoft\Proxy\Exception\ProxyException;
use Swoft\Proxy\Proxy as BaseProxy;
use SwoftTest\Aop\Testing\AopClass;
use function class_exists;
use function sprintf;
use const PHP_EOL;

/**
 * Class AopTest
 *
 * @since 2.0
 */
class AopTest extends TestCase
{
    /**
     * @throws ProxyException
     */
    public function testProxyClass(): void
    {
        $visitor   = new ProxyVisitor();
        $className = BaseProxy::newClassName(AopClass::class, $visitor);

        $this->assertTrue(class_exists($className));
    }

    /**
     * @throws ProxyException
     */
    public function testProxyCode(): void
    {
        $parser    = new Parser();
        $visitor   = new ProxyVisitor('proxy_id');
        $className = AopClass::class;

        $visitorClassName = get_class($visitor);
        $parser->addNodeVisitor($visitorClassName, $visitor);

        $proxyCode = $parser->parse($className);

        // Proxy file and proxy code
        $proxyCode = sprintf("<?php%s %s\n", PHP_EOL, $proxyCode);
// \vdump($proxyCode);
        $tpFile = __DIR__ . '/template/aop.tp';

        $codeMd5 = md5($proxyCode);
        $tpMd5   = md5(file_get_contents($tpFile));

        $this->assertEquals($codeMd5, $tpMd5);

        require $tpFile;

        // Proxy class
        $proxyClassName = $visitor->getProxyClassName();
        $this->assertTrue(class_exists($proxyClassName));

        $originalClass = Proxy::getOriginalClassName($proxyClassName);
        $this->assertEquals($originalClass, $className);
    }
}
