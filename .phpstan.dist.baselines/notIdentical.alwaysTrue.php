<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
    'rawMessage' => 'Strict comparison using !== between \'checked="checked"\' and \'0\' will always evaluate to true.',
    'count' => 1,
    'path' => __DIR__ . '/../app/code/core/Mage/Adminhtml/Block/System/Config/Form/Field.php',
];
$ignoreErrors[] = [
    'rawMessage' => 'Strict comparison using !== between \'+\' and \'0\' will always evaluate to true.',
    'count' => 1,
    'path' => __DIR__ . '/../app/code/core/Mage/Adminhtml/Block/Widget/Grid/Column/Renderer/Number.php',
];
$ignoreErrors[] = [
    'rawMessage' => 'Strict comparison using !== between \'[]\' and \'0\' will always evaluate to true.',
    'count' => 1,
    'path' => __DIR__ . '/../app/code/core/Mage/Catalog/Block/Product/View/Options/Type/Select.php',
];
$ignoreErrors[] = [
    'rawMessage' => 'Strict comparison using !== between mixed and 0 will always evaluate to true.',
    'count' => 1,
    'path' => __DIR__ . '/../app/code/core/Mage/Catalog/Model/Api2/Product/Validator/Product.php',
];
$ignoreErrors[] = [
    'rawMessage' => 'Strict comparison using !== between non-falsy-string and \'\' will always evaluate to true.',
    'count' => 1,
    'path' => __DIR__ . '/../app/code/core/Mage/Core/Block/Messages.php',
];
$ignoreErrors[] = [
    'rawMessage' => 'Strict comparison using !== between \'/\' and \'0\' will always evaluate to true.',
    'count' => 1,
    'path' => __DIR__ . '/../app/code/core/Mage/Core/Model/Url/Rewrite.php',
];
$ignoreErrors[] = [
    'rawMessage' => 'Strict comparison using !== between \'/\' and \'0\' will always evaluate to true.',
    'count' => 1,
    'path' => __DIR__ . '/../app/code/core/Mage/Core/Model/Url/Rewrite/Request.php',
];
$ignoreErrors[] = [
    'rawMessage' => 'Strict comparison using !== between array{\'link_id = ?\': mixed}|array{\'link_id in (?)\': array<mixed, mixed>}|array{\'sample_id = ?\': mixed} and array{} will always evaluate to true.',
    'count' => 1,
    'path' => __DIR__ . '/../app/code/core/Mage/Downloadable/Model/Resource/Link.php',
];
$ignoreErrors[] = [
    'rawMessage' => 'Strict comparison using !== between array{\'sample_id = ?\': mixed}|array{\'sample_id in (?)\': mixed} and array{} will always evaluate to true.',
    'count' => 1,
    'path' => __DIR__ . '/../app/code/core/Mage/Downloadable/Model/Resource/Sample.php',
];
$ignoreErrors[] = [
    'rawMessage' => 'Strict comparison using !== between \'<a href="%%s">%%s</a>\' and \'0\' will always evaluate to true.',
    'count' => 1,
    'path' => __DIR__ . '/../app/design/frontend/base/default/template/sales/recurring/grid.phtml',
];
$ignoreErrors[] = [
    'rawMessage' => 'Strict comparison using !== between \'<a href="%%s">%%s</a>\' and \'0\' will always evaluate to true.',
    'count' => 1,
    'path' => __DIR__ . '/../app/design/frontend/rwd/default/template/sales/recurring/grid.phtml',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
