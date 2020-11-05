<?php

$fileHeaderComment = <<<COMMENT
This file is part of the fixcity package.

(c) FixCity <fixcity.org@gmail.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
COMMENT;

$finder = PhpCsFixer\Finder::create()
    ->in([
            __DIR__ . '/src',
            __DIR__ . '/tests',
    ]);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__.'/var/cache/.php_cs.cache')
    ->setRules([
                   '@PSR2' => true,
                   'psr4' => true,
                   'strict_param' => true,
                   'array_syntax' => ['syntax' => 'short'],
                   'header_comment' => ['header' => $fileHeaderComment, 'comment_type' => 'PHPDoc', 'location' => 'after_open', 'separate' => 'bottom'],
                   'mb_str_functions' => true,
                   'ordered_imports' => true,
                   'blank_line_before_statement' => true,
                   'trailing_comma_in_multiline_array' => true,
                   'strict_comparison' => true,
                   'php_unit_method_casing' => ['case' => 'snake_case'],
                   'php_unit_test_case_static_method_calls' => ['call_type' => 'this'],
                   'class_attributes_separation' => ['elements' => ['const', 'property', 'method']],
                   'declare_strict_types' => true,
                   'no_unused_imports' => true,
                   'no_unset_on_property' => true,
                   'no_null_property_initialization' => true,
                   'single_blank_line_before_namespace' => true,
                   'no_blank_lines_after_class_opening' => true
               ])
    ->setFinder($finder);