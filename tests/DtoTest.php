<?php

declare(strict_types=1);

namespace Traincase\HtmlToPdfTinker\Tests;

use PHPUnit\Framework\TestCase;
use Traincase\HtmlToPdfTinker\DTO\PdfToGenerateDTO;

class DtoTest extends TestCase
{
    /** @test */
    public function it_defaults_the_orientation_to_portrait()
    {
        $dto = new PdfToGenerateDTO([
            'filename' => 'test.pdf',
            'html' => '<html lang="en"><body><p>test text</p></body>',
            'options' => [],
            'path' => '/tmp',
        ]);

        $this->assertInstanceOf(PdfToGenerateDTO::class, $dto);
        $this->assertSame($dto->orientation, 'portrait');
    }

    /** @test */
    public function it_defaults_the_path_to_root()
    {
        $dto = new PdfToGenerateDTO([
            'filename' => 'test.pdf',
            'html' => '<html lang="en"><body><p>test text</p></body>',
            'orientation' => 'landscape',
            'options' => [],
        ]);

        $this->assertInstanceOf(PdfToGenerateDTO::class, $dto);
        $this->assertSame($dto->path, '/');
        $this->assertSame($dto->orientation, 'landscape');
        $this->assertSame($dto->html, '<html lang="en"><body><p>test text</p></body>');
        $this->assertSame($dto->filename, 'test.pdf');
    }

    /** @test */
    public function the_orientation_should_be_portrait_or_landscape()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Mode should either be "portrait" or "landscape", got "invalid-mode"');

        new PdfToGenerateDTO([
            'filename' => 'test.pdf',
            'html' => '<html lang="en"><body><p>test text</p></body>',
            'orientation' => 'invalid-mode',
            'options' => [],
        ]);
    }
}
