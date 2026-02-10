<?php

require __DIR__.'/bootstrap/app.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    $request = Illuminate\Http\Request::capture()
);

$routes = collect(Illuminate\Support\Facades\Route::getRoutes())
    ->filter(fn ($r) => strpos($r->getName(), 'simpanan') !== false)
    ->map(fn ($r) => [
        'method' => implode('|', $r->methods),
        'uri' => $r->uri,
        'name' => $r->getName(),
    ]);

echo "Simpanan Routes:\n";
foreach ($routes as $route) {
    echo "{$route['method']}\t{$route['uri']}\t{$route['name']}\n";
}
