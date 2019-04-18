<?php

use Swoft\Bean\Annotation\Mapping\Bean;
use SwoftTest\Bean\Testing\Definition\SingletonClass;
use SwoftTest\Bean\Testing\InjectBean;
use SwoftTest\Bean\Testing\Definition\PrototypeClass;
use SwoftTest\Bean\Testing\Definition\RequestClass;
use SwoftTest\Bean\Testing\Definition\SessionClass;

return [
    'singleton'    => [
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
    'prototype'    => [
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
            'alias' => 'prototype-alias'
        ]
    ],
    'requestClass' => [
        'class'               => RequestClass::class,
        'privateProp'         => 'privateProp',
        'publicProp'          => 12,
        'classPrivate'        => 'classPrivate',
        'classPublic'         => 12,
        'setProp'             => 'setProp',
        'definitionBean'      => \bean('injectBean'),
        'definitionBeanAlias' => \bean('injectBeanAlias'),
        'definitionBeanClass' => \bean(InjectBean::class),
        '__option'            => [
            'alias' => 'request-alias'
        ]
    ],
    'sessionClass' => [
        'class'               => SessionClass::class,
        'privateProp'         => 'privateProp',
        'publicProp'          => 12,
        'classPrivate'        => 'classPrivate',
        'classPublic'         => 12,
        'setProp'             => 'setProp',
        'definitionBean'      => \bean('injectBean'),
        'definitionBeanAlias' => \bean('injectBeanAlias'),
        'definitionBeanClass' => \bean(InjectBean::class),
        '__option'            => [
            'alias' => 'session-alias'
        ]
    ],
];