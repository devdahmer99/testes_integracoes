<?php

namespace Integration\Web;

use PHPUnit\Framework\TestCase;

class RestTest extends TestCase
{
    public function testApiRestQueDeveRetornarArrayDeLeiloes()
    {
        $responsta = file_get_contents('http://localhost:8080/rest.php');

        self::assertStringContainsString('200 OK', $http_response_header[0]);
        self::assertIsArray(json_decode($responsta));
    }
}