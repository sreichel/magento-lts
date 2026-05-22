<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
    'rawMessage' => 'Strict comparison using !== between \'\' and \'\' will always evaluate to false.',
    'count' => 3,
    'path' => __DIR__ . '/../app/code/core/Mage/Tax/Helper/Data.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
