<?php

$caPath = getenv('MYSQL_ATTR_SSL_CA') ?: '/run/app-certificates/mysql-ca.pem';

if (!file_exists($caPath)) {
    fwrite(STDERR, "Cannot find MySQL CA certificate file at: {$caPath}\n");
    exit(1);
}

if (!is_readable($caPath)) {
    fwrite(STDERR, "MySQL CA certificate file exists but is not readable by current process at: {$caPath}\n");
    exit(1);
}

$contents = file_get_contents($caPath);
if ($contents === false || strlen(trim($contents)) === 0) {
    fwrite(STDERR, "MySQL CA certificate file is empty.\n");
    exit(1);
}

echo "MySQL CA certificate successfully verified and readable by PHP.\n";
exit(0);
