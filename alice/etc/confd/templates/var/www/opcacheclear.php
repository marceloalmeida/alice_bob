<?php

$hostname = (function_exists('gethostname')
    ? gethostname()
    : (php_uname('n')
        ?: (empty($_SERVER['SERVER_NAME'])
            ? $_SERVER['HOST_NAME']
            : $_SERVER['SERVER_NAME']
        )
    )
);

if (empty($_REQUEST['CODE_PATH'])) {
    echo $hostname . ": RESET_ALL";
    opcache_reset();
} else {
    echo $hostname . ": RESET " . $_REQUEST['CODE_PATH'];
    $cache = opcache_get_status(true);
    if (!empty($cache['scripts'])) {
        foreach ($cache['scripts'] as $path => $values) {
            if (strstr($path, $_REQUEST['CODE_PATH']) !== false) {
                opcache_invalidate($path, true);
            }
        }
    }
}

echo "\n";
