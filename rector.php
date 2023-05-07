<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DeadCode\Rector\ClassLike\RemoveAnnotationRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Strict\Rector\AbstractFalsyScalarRuleFixerRector;
use Rector\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector;

return static function (RectorConfig $config): void {
    $config->paths([__DIR__ . '/src', __DIR__ . '/tests']);
    $config->phpVersion(PhpVersion::PHP_74);

    // Define what rule sets will be applied
    $config->import(LevelSetList::UP_TO_PHP_74);
    $config->import(SetList::CODE_QUALITY);
    $config->import(SetList::CODING_STYLE);
    $config->import(SetList::TYPE_DECLARATION);
    $config->importNames();
    $config->importShortClasses(false);

    $config->ruleWithConfiguration(
        BooleanInBooleanNotRuleFixerRector::class,
        [
            AbstractFalsyScalarRuleFixerRector::TREAT_AS_NON_EMPTY => false,
        ]
    );

    $config->ruleWithConfiguration(RemoveAnnotationRector::class, ['method', 'inheritDoc', 'author', 'module']);

    $config->rule(RemoveUselessParamTagRector::class);
    $config->rule(RemoveUselessReturnTagRector::class);
    $config->rule(RemoveUselessVarTagRector::class);

    $config->skip([VarConstantCommentRector::class, NewlineAfterStatementRector::class, ReturnNeverTypeRector::class]);
};
