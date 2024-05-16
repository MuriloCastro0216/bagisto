<?php

namespace Webkul\Core\Tests;

use Tests\TestCase;
use Webkul\Admin\Tests\Concerns\AdminTestBench;
use Webkul\Core\Tests\Concerns\CoreAssertions;

class CoreTestCase extends TestCase
{
    use AdminTestBench, CoreAssertions;
}
