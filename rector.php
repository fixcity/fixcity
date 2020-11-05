<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ConsecutiveNullCompareReturnsToNullCoalesceQueueRector;
use Rector\Set\ValueObject\SetList;
use Rector\SOLID\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set('php_version_features', '7.4');

    $parameters->set('paths', [__DIR__ . '/src', __DIR__ . '/tests']);

    $parameters->set('exclude_paths', [__DIR__ . '/vendor', '*Adapter.php']);

    $parameters->set('auto_import_names', true);

    $parameters->set('import_short_classes', false);

    $parameters->set('sets', ['code-quality', SetList::PHP_71, SetList::PHP_72, SetList::PHP_73, SetList::PHP_74]);

    $parameters->set('services', [
        ConsecutiveNullCompareReturnsToNullCoalesceQueueRector::class => null,
        ChangeIfElseValueAssignToEarlyReturnRector::class => null
    ]);
};
