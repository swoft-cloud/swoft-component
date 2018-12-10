<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Devtool\Cases;

use PHPUnit\Framework\TestCase;
use Swoft\Devtool\FileGenerator;

/**
 * Class FileGeneratorTest
 */
class FileGeneratorTest extends TestCase
{
    /**
     * @throws \Leuffen\TextTemplate\TemplateParsingException
     */
    public function testGen()
    {
        $data = [
            'prefix' => '/path',
            'className' => 'DemoController',
            'namespace' => 'App\Controller',
        ];

        $gen = new FileGenerator([
            'tplDir' => __DIR__ . '/../res',
        ]);

        $code = $gen
            ->setTplFilename('some')
            ->render($data);

        $this->assertTrue(\strpos($code, $data['prefix']) > 0);
        $this->assertTrue(\strpos($code, $data['className']) > 0);
        $this->assertTrue(\strpos($code, $data['namespace']) > 0);
    }
}
