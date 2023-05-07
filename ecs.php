<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $containerConfigurator): void {
    $containerConfigurator->import(SetList::PSR_12);
    $containerConfigurator->import(SetList::SYMPLIFY);
    $containerConfigurator->import(SetList::COMMON);
    $containerConfigurator->import(SetList::CLEAN_CODE);

    $containerConfigurator->ruleWithConfiguration(ClassAttributesSeparationFixer::class, [
        'elements' => [
            'const' => 'only_if_meta',
            'property' => 'one',
            'method' => 'one',
        ],
    ]);

    // disable features not available pn PHP 7.4
    $typeHintsConfig = [
        'enableMixedTypeHint' => false,
        'enableUnionTypeHint' => false,
        'enableIntersectionTypeHint' => false,
        'enableStandaloneNullTrueFalseTypeHints' => false,
    ];

    $containerConfigurator->ruleWithConfiguration(PropertyTypeHintSniff::class, $typeHintsConfig);
    $containerConfigurator->ruleWithConfiguration(ParameterTypeHintSniff::class, $typeHintsConfig);

    $containerConfigurator->ruleWithConfiguration(ReturnTypeHintSniff::class, $typeHintsConfig + [
        'enableNeverTypeHint' => false,
    ]);

    $containerConfigurator->skip([
        PropertyTypeHintSniff::class . '.MissingTraversableTypeHintSpecification' => null,
        ParameterTypeHintSniff::class . '.MissingTraversableTypeHintSpecification' => null,
        ReturnTypeHintSniff::class . '.MissingTraversableTypeHintSpecification' => null,
    ]);

    $containerConfigurator->parameters()
        ->set(Option::PARALLEL, false);
    $containerConfigurator->cacheDirectory('/tmp/ecs');
    $containerConfigurator->paths(
        [__DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/ecs.php', __DIR__ . '/rector.php']
    );
};
