<?php
/**
 * Auto-update README.md with a generated summary of recent code changes.
 * Scans: migrations, portal routes, portal controllers, portal views.
 * Inserts content between markers <!-- AUTO_SUMMARY_START --> and <!-- AUTO_SUMMARY_END -->
 */

$root = realpath(__DIR__ . '/..');
$readmePath = $root . DIRECTORY_SEPARATOR . 'README.md';

if (!file_exists($readmePath)) {
    echo "README.md not found at $readmePath\n";
    exit(1);
}

function listFilesMarkdown($files, $prefix = '') {
    $out = "";
    foreach ($files as $f) {
        $out .= "- " . $prefix . basename($f) . "\n";
    }
    return $out;
}

// Migrations
$migrationsPath = $root . DIRECTORY_SEPARATOR . 'bloodmap_api' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations';
$migrations = is_dir($migrationsPath) ? glob($migrationsPath . DIRECTORY_SEPARATOR . '*.php') : [];
sort($migrations);

// Routes (extract named routes)
$routesFile = $root . DIRECTORY_SEPARATOR . 'bloodmap_api' . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'web.php';
$routes = [];
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    if (preg_match_all("/->name\(['\"]([^'\"]+)['\"]\)/", $content, $m)) {
        $routes = array_unique($m[1]);
        sort($routes);
    }
}

// Portal controllers
$controllersPath = $root . DIRECTORY_SEPARATOR . 'bloodmap_api' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Portal';
$controllers = is_dir($controllersPath) ? glob($controllersPath . DIRECTORY_SEPARATOR . '*.php') : [];
sort($controllers);

// Portal views
$viewsPath = $root . DIRECTORY_SEPARATOR . 'bloodmap_api' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'portal';
$views = is_dir($viewsPath) ? glob($viewsPath . DIRECTORY_SEPARATOR . '*.blade.php') : [];
sort($views);

$date = date('Y-m-d H:i:s');

$md = "<!-- AUTO_SUMMARY_START -->\n";
$md .= "**Auto-generated summary (generated on $date)**\n\n";

$md .= "**Migrations:**\n";
if (count($migrations)) {
    $md .= listFilesMarkdown($migrations, 'migrations/');
} else {
    $md .= "- (none)\n";
}
$md .= "\n";

$md .= "**Portal Routes (named):**\n";
if (count($routes)) {
    foreach ($routes as $r) { $md .= "- $r\n"; }
} else {
    $md .= "- (none)\n";
}
$md .= "\n";

$md .= "**Portal Controllers:**\n";
if (count($controllers)) {
    $md .= listFilesMarkdown($controllers, 'Controllers/Portal/');
} else {
    $md .= "- (none)\n";
}
$md .= "\n";

$md .= "**Portal Views:**\n";
if (count($views)) {
    $md .= listFilesMarkdown($views, 'views/portal/');
} else {
    $md .= "- (none)\n";
}
$md .= "\n";

$md .= "**Notes:**\n- Preferences JSON column added to `users` via migration.\n- Settings view updated with preference toggles and dark-mode preview.\n\n";
$md .= "<!-- AUTO_SUMMARY_END -->\n";

$readme = file_get_contents($readmePath);

if (strpos($readme, '<!-- AUTO_SUMMARY_START -->') !== false) {
    // Replace existing block
    $readme = preg_replace('/<!-- AUTO_SUMMARY_START -->(.*?)<!-- AUTO_SUMMARY_END -->/s', $md, $readme);
} else {
    // Append at end
    $readme = rtrim($readme) . "\n\n" . $md;
}

file_put_contents($readmePath, $readme);

echo "README.md updated with auto-generated summary.\n";

exit(0);
