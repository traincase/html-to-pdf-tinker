<?php

namespace Traincase\HtmlToPdfTinker\Tests;

use PHPUnit\Framework\TestCase;
use Traincase\HtmlToPdfTinker\PdfTinkerManager;
use Traincase\HtmlToPdfTinker\Tests\Mock\TestDriver;

class ManagerTest extends TestCase
{
    /** @test */
    public function it_can_register_and_resolve_drivers()
    {
        $manager = new PdfTinkerManager;

        $manager->extend('test-driver', function() {
            return new TestDriver;
        });

        $this->assertContains('test-driver', $manager->getRegisteredDrivers());
        $this->assertInstanceOf(TestDriver::class, $manager->resolve('test-driver'));
    }

    /** @test */
    public function it_throws_exceptions_when_drivers_cant_be_resolved()
    {
        $manager = new PdfTinkerManager;

        $this->expectException(\InvalidArgumentException::class);

        $manager->resolve('non-existing-driver');
    }
}
