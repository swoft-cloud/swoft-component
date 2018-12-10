<?php

$header = <<<'EOF'
This file is part of Swoft.

@link     https://swoft.org
@document https://doc.swoft.org
@contact  group@swoft.org
@license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@DoctrineAnnotation' => true,
        'header_comment' => [
            'commentType' => 'PHPDoc',
            'header' => $header,
            'separate' => 'none',
            'location' => 'after_declare_strict',
        ],
        'array_syntax' => [
            'syntax' => 'short'
        ],
        'class_attributes_separation' => true,
        'combine_consecutive_unsets' => true,
        'declare_strict_types' => false,
        'linebreak_after_opening_tag' => true,
        'no_useless_else' => true,
        'no_unused_imports' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'php_unit_strict' => false,
        'single_quote' => true,
        'standardize_not_equals' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('public')
            ->exclude('resources')
            ->exclude('config')
            ->exclude('runtime')
            ->exclude('vendor')
            ->in(__DIR__)
    )
    ->setUsingCache(false);
