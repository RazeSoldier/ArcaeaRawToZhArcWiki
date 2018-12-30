<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

require_once __DIR__ . '/../src/Setup.php';

$phpUnitClass = 'PHPUnit\TextUI\Command';

if ( !class_exists( 'PHPUnit\\Framework\\TestCase' ) ) {
    echo "PHPUnit not found. Please install it and other dev dependencies by running `"
        . "composer install` in the root directory.\n";
    die ( 1 );
}
if ( !class_exists( $phpUnitClass ) ) {
    echo "PHPUnit entry point '" . $phpUnitClass . "' not found. Please make sure you installed "
        . "the containing component and check the spelling of the class name.\n";
    die ( 1 );
}

$_SERVER['argv'][] = '-c' . __DIR__ . '/phpunit.xml';
$phpUnitClass::main();