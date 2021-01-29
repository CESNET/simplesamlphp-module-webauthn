<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ArraySyntaxFixer::class)
        ->call('configure', [[
            'syntax' => 'short',
        ]])
    ;

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__.'/ecs.php',
        __DIR__.'/lib',
        __DIR__.'/www',
    ]);

    $parameters->set(
        Option::SETS,
        [
            SetList::SPACES,
            SetList::ARRAY,
            SetList::DOCBLOCK,
            SetList::NAMESPACES,
            SetList::CONTROL_STRUCTURES,
            SetList::CLEAN_CODE,
            SetList::PSR_1,
            SetList::PSR_12,
            SetList::PHP_70,
            SetList::PHP_71,
            SetList::COMMON,
            SetList::PHP_CS_FIXER,
        ]
    );
};
