<?php

use Swoft\Bean\Annotation\Mapping\Bean;
use SwoftTest\Bean\Testing\Definition\SingletonClass;
use SwoftTest\Bean\Testing\InjectBean;
use SwoftTest\Bean\Testing\Definition\PrototypeClass;

return [
    'singleton' => [
        'class'               => SingletonClass::class,
        'privateProp'         => 'privateProp',
        'publicProp'          => 12,
        'classPrivate'        => 'classPrivate',
        'classPublic'         => 12,
        'setProp'             => 'setProp',
        'definitionBean'      => \bean('injectBean'),
        'definitionBeanAlias' => \bean('injectBeanAlias'),
        'definitionBeanClass' => \bean(InjectBean::class),
        '__option'            => [
            'scope' => Bean::SINGLETON,
            'alias' => 'singleton-alias'
        ]
    ],
    'prototype' => [
        'class'               => PrototypeClass::class,
        'privateProp'         => 'privateProp',
        'publicProp'          => 12,
        'classPrivate'        => 'classPrivate',
        'classPublic'         => 12,
        'setProp'             => 'setProp',
        'definitionBean'      => \bean('injectBean'),
        'definitionBeanAlias' => \bean('injectBeanAlias'),
        'definitionBeanClass' => \bean(InjectBean::class),
        '__option'            => [
            'scope' => Bean::SINGLETON,
            'alias' => 'prototype-alias'
        ]
    ]
];