<?php
$appJsPath = __DIR__ . '/../../../public/assets/js/app.js';
$appJsVersion = file_exists($appJsPath) ? filemtime($appJsPath) : time();
?>
<script defer src="<?= base_url('assets/js/app.js?v=' . $appJsVersion) ?>"></script>
<?php if (!empty($pageScript)): ?>
    <?php
    $pageJsPath = __DIR__ . '/../../../public/assets/js/' . $pageScript;
    $pageJsVersion = file_exists($pageJsPath) ? filemtime($pageJsPath) : time();
    ?>
    <script defer src="<?= base_url('assets/js/' . $pageScript . '?v=' . $pageJsVersion) ?>"></script>
<?php endif; ?>
</body>
</html>