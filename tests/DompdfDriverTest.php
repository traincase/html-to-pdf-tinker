<?php

namespace Traincase\HtmlToPdfTinker\Tests;

use Dompdf\Dompdf;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use PHPUnit\Framework\TestCase;
use Traincase\HtmlToPdfTinker\Drivers\DompdfDriver;
use Traincase\HtmlToPdfTinker\DTO\PdfToGenerateDTO;
use Traincase\HtmlToPdfTinker\Exceptions\PdfCouldNotBeCreatedException;
use Traincase\HtmlToPdfTinker\Tests\Util\PDF2Text;

class DompdfDriverTest extends TestCase
{
    /** @test */
    public function it_creates_pdfs_on_the_filesystem()
    {
        $driver = new DompdfDriver(new Dompdf);
        $dto = new PdfToGenerateDTO([
            'filename' => 'test.pdf',
            'html' => '<html lang="en"><body><p>test text</p></body>',
            'options' => [],
            'path' => '/tmp',
        ]);
        $filesystem = new Filesystem(new MemoryAdapter);

        // Create PDF
        $path = $driver->storeOnFilesystem($filesystem, $dto);

        // Get generated text content in PDF
        $textContent = (new \Smalot\PdfParser\Parser)
            ->parseContent($filesystem->read($path))
            ->getText();

        $this->assertTrue($filesystem->has($path));
        $this->assertSame('application/pdf', $filesystem->getMimetype($path));
        $this->assertSame('test text', $textContent);
    }

    /** @test */
    public function it_throws_an_exception_when_path_is_invalid()
    {
        $this->expectException(PdfCouldNotBeCreatedException::class);
        $this->expectExceptionMessage('Dompdf could not create PDF');

        $driver = new DompdfDriver(new Dompdf);
        $dto = new PdfToGenerateDTO([
            'filename' => 'test.pdf',
            'html' => '<html lang="en"><body><p>test text</p></body>',
            'options' => [],
            'path' => '../../../../outside-of-filesystem',
        ]);
        $filesystem = new Filesystem(new MemoryAdapter);

        // Try to create PDF outside of valid filesystem
        $path = $driver->storeOnFilesystem($filesystem, $dto);
    }

    /** @test */
    public function it_throws_an_exception_when_dompdf_doesnt_create_a_pdf()
    {
        $this->expectException(PdfCouldNotBeCreatedException::class);
        $this->expectExceptionMessage('Dompdf could not create PDF');

        // Make sure Dompdf doesn't output a pdf
        $corruptedDompdf = new Class extends Dompdf {
            public function output($options = [])
            {
                return '';
            }
        };

        $driver = new DompdfDriver(new $corruptedDompdf);
        $dto = new PdfToGenerateDTO([
            'filename' => 'test.pdf',
            'html' => '<html lang="en"><body><p>test text</p></body>',
            'options' => [],
        ]);
        $filesystem = new Filesystem(new MemoryAdapter);

        // Try to create PDF outside of valid filesystem
        $path = $driver->storeOnFilesystem($filesystem, $dto);
    }
}
