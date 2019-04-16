<?php declare(strict_types=1);


namespace SwoftTest\Annotation;


use PHPUnit\Framework\TestCase;
use Swoft\Annotation\AnnotationRegister;
use SwoftTest\Annotation\Annotation\Mapping\Demo;
use SwoftTest\Annotation\Annotation\Mapping\DemoMethod;
use SwoftTest\Annotation\Annotation\Mapping\DemoProperty;
use SwoftTest\Annotation\Annotation\Parser\DemoParser;

/**
 * Class AnnotationTest
 *
 * @since 2.0
 */
class AnnotationTest extends TestCase
{
    public function testAnnotationClass()
    {
        $annotations = AnnotationRegister::getAnnotations();

        $demoAnnotation = $annotations['SwoftTest\Annotation']['SwoftTest\Annotation\DemoAnnotation'] ?? [];
        $this->assertTrue(!empty($demoAnnotation));

        $this->assertTrue(isset($demoAnnotation['reflection']));

        $annoClassName = [
            Demo::class
        ];

        foreach ($demoAnnotation['annotation'] as $anno) {
            $this->assertTrue(in_array(get_class($anno), $annoClassName));
            if ($anno instanceof Demo) {
                $this->assertEquals($anno->getName(), 'demoAnnotation');
            }
        }
    }

    public function testAnnotationProperty()
    {
        $annotations     = AnnotationRegister::getAnnotations();
        $propAnnotations = $annotations['SwoftTest\Annotation']['SwoftTest\Annotation\DemoAnnotation']['properties'] ?? [];

        $this->assertTrue(!empty($propAnnotations));

        foreach ($propAnnotations as $proName => $proAry) {
            $proAnnotation = $proAry['annotation'][0];
            if (!$proAnnotation instanceof DemoProperty) {
                $this->assertTrue(false);
                continue;
            }

            $this->assertEquals($proName, $proAnnotation->getName());
        }
    }

    public function testAnnotationMethod()
    {
        $annotations       = AnnotationRegister::getAnnotations();
        $methodAnnotations = $annotations['SwoftTest\Annotation']['SwoftTest\Annotation\DemoAnnotation']['methods'] ?? [];

        $this->assertTrue(!empty($methodAnnotations));

        foreach ($methodAnnotations as $methodName => $methodAry) {
            $methodAnnotation = $methodAry['annotation'][0];
            if (!$methodAnnotation instanceof DemoMethod) {
                $this->assertTrue(false);
                continue;
            }

            $this->assertEquals($methodName, $methodAnnotation->getName());
        }
    }

    public function testParser()
    {
        $parsers = AnnotationRegister::getParsers();


        $parserClassName = [
            DemoParser::class
        ];

        foreach ($parsers as $parser) {
            $this->assertTrue(in_array($parser, $parserClassName));
        }
    }

    public function testAutoLoader()
    {
        $autoLoader = AnnotationRegister::getAutoLoader('SwoftTest\\Annotation\\');
        $this->assertTrue($autoLoader instanceof AutoLoader);
    }
}