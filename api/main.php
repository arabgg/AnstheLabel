<?php

return function () {
    ob_start();
    require __DIR__ . '/../public/index.php';
    return ob_get_clean();
};
