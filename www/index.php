<?php

function myAutoLoader(string $className)
{
    require_once __DIR__ . '/../src/' . str_replace('\\', '/', $className) . '.php';
}
try {
spl_autoload_register('myAutoLoader');
$route = $_GET['route'] ?? '';
$routes = require __DIR__ . '/../src/routes.php';

$isRouteFound = false;
foreach ($routes as $pattern => $controllerAndAction) {
    preg_match($pattern, $route, $matches);
    if (!empty($matches)) {
        $isRouteFound = true;
        break;
    }
}

if (!$isRouteFound) {
    throw new \TechSupport\Models\Exceptions\NotFoundException();
}
unset($matches[0]);

$controllerName = $controllerAndAction[0];
$actionName = $controllerAndAction[1];

$controller = new $controllerName();
$controller->$actionName(...$matches);

} catch (\TechSupport\Models\Exceptions\DbException $e) {
    $view = new \TechSupport\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
}

catch (\TechSupport\Models\Exceptions\NotFoundException $e) {
    $view = new \TechSupport\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
}