<?php

$path = realpath(__DIR__.'/../');
if (false === $path) {
	throw new RuntimeException('Cannot find the project root');
}

$finder = PhpCsFixer\Finder::create()->in($path)->name('*.php');

return (new PhpCsFixer\Config())
	->setRules([
		'@PSR1' => true,
		'@PSR2' => true,
		'@PhpCsFixer' => true,
		'@Symfony' => true,
		'lowercase_cast' => true,
		'combine_consecutive_unsets' => true,
		'no_empty_comment' => true,
		'no_empty_statement' => true,
		'no_whitespace_in_blank_line' => true,
		'standardize_not_equals' => true,
		'no_spaces_around_offset' => true,
		'array_syntax' => ['syntax' => 'short'],
		'global_namespace_import' => [
			'import_classes' => false,
			'import_constants' => false,
			'import_functions' => false,
		],
		'multiline_whitespace_before_semicolons' => [
			'strategy' => 'no_multi_line',
		],
		'phpdoc_separation' => false,
		'no_superfluous_phpdoc_tags' => false,
		'concat_space' => [
			'spacing' => 'none',
		],
		'string_implicit_backslashes' => false,
		'trailing_comma_in_multiline' => true,
		'php_unit_test_class_requires_covers' => false,
	])
	->setIndent("\t")
	->setLineEnding("\n")
	->setRiskyAllowed(true)
	->setUsingCache(false)
	->setFinder($finder);
