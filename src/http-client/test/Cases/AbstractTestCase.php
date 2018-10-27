<?php

namespace SwoftTest\HttpClient;

use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase
{
    protected $options = ['timeout' => 20];
}