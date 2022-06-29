<?php

namespace UpsFreeVendor\WPDesk\Composer\Codeception;

use UpsFreeVendor\WPDesk\Composer\Codeception\Commands\CreateCodeceptionTests;
use UpsFreeVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests;
use UpsFreeVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTests;
/**
 * Links plugin commands handlers to composer.
 */
class CommandProvider implements \UpsFreeVendor\Composer\Plugin\Capability\CommandProvider
{
    public function getCommands()
    {
        return [new \UpsFreeVendor\WPDesk\Composer\Codeception\Commands\CreateCodeceptionTests(), new \UpsFreeVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests(), new \UpsFreeVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTests()];
    }
}
