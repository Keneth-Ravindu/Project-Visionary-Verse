<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    if (!function_exists('base_url')) {
        function base_url($path = '')
        {
            $base = defined('BASE_URL') ? BASE_URL : rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

            if ($base === '') {
                $base = '/';
            }

            if ($path === '') {
                return $base;
            }

            return rtrim($base, '/') . '/' . ltrim($path, '/');
        }
    }
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Visionary Verse'; ?></title>
    <?php
    $themePath = __DIR__ . '/../../../public/assets/css/theme.css';
    $themeVersion = file_exists($themePath) ? filemtime($themePath) : time();
    ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css?v=' . $themeVersion) ?>">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>" />
</head>
<body>