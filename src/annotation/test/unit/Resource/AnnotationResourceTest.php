<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Annotation\Unit\Resource;

use PHPUnit\Framework\TestCase;
use Swoft\Annotation\Resource\AnnotationResource;
use SwoftTest\Testing\Concern\CommonTestAssertTrait;

/**
 * Class AnnotationResourceTest
 */
class AnnotationResourceTest extends TestCase
{
    use CommonTestAssertTrait;

    public function testBasic(): void
    {
        $ar = new AnnotationResource();

        $this->assertFalse($ar->isInPhar());
        $this->assertEmpty($ar->getBasePath());
        $this->assertEmpty($ar->getOnlyNamespaces());
        $this->assertEmpty($ar->getDisabledAutoLoaders());

        $this->assertSame('AutoLoader', $ar->getLoaderClassName());

        $this->assertNotEmpty($names = $ar->getExcludedFilenames());
        $this->assertArrayHasKey('Swoft.php', $names);

        $this->assertNotEmpty($prefixes = $ar->getExcludedPsr4Prefixes());
        $this->assertArrayContainValue($prefixes, 'PHPUnit\\');
        $this->assertArrayContainValue($prefixes, 'Monolog\\');
        $this->assertArrayNotContainValue($prefixes, 'TestNamespace\\');
        $this->assertTrue($ar->isExcludedPsr4Prefix('Monolog\\SomeClass'));

        $ar = new AnnotationResource([
            'inPhar'               => true,
            'basePath'             => '/bash/path',
            'notifyHandler'        => function () {
            },
            // TODO force load framework components: bean, error, event, aop
            'disabledAutoLoaders'  => ['Some\\TestLoader'],
            'excludedPsr4Prefixes' => ['TestNamespace\\'],
        ]);

        $this->assertTrue($ar->isInPhar());

        $this->assertNotEmpty($loaders = $ar->getDisabledAutoLoaders());
        $this->assertArrayContainValue($loaders, 'Some\\TestLoader');

        $this->assertNotEmpty($prefixes = $ar->getExcludedPsr4Prefixes());
        $this->assertArrayContainValue($prefixes, 'PHPUnit\\');
        $this->assertArrayContainValue($prefixes, 'TestNamespace\\');
    }
}
