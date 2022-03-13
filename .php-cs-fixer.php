<?php

$rules = [
    '@PHP71Migration'        => true,
    '@PHP71Migration:risky'  => true,
    '@PSR2'                  => true,
    '@Symfony'               => true,
    'ordered_class_elements' => [
        'order' => [
            'use_trait',
            'constant_public',
            'constant_protected',
            'constant_private',
            'property_public',
            'property_protected',
            'property_private',
            'construct',
            'destruct',
            'magic',
            'phpunit',
            'method_public',
            'method_protected',
            'method_private',
        ]
    ],
    'visibility_required'     => ['elements' => ['property', 'method', 'const']],
    'no_useless_else'         => true,
    'no_useless_return'       => true,
    'align_multiline_comment' => true,
    'no_unused_imports'       => true,
    'binary_operator_spaces'  => [
        'operators' => [
            '=' => 'align',
            '=>' => 'align',
        ]
    ],
    'declare_strict_types'       => false,
    'no_superfluous_phpdoc_tags' => false,
    'phpdoc_no_package'          => false,
    'phpdoc_summary'             => false,
];

$config = (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->exclude('vendor')
            ->exclude('var')
            ->exclude('public')
            ->exclude('bin/console')
    );

return $config;
