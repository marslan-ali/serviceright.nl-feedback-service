<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class TestCase
 * @package Tests
 */
abstract class TestCase extends BaseTestCase {

    use CreatesApplication,
        WithFaker;
}
