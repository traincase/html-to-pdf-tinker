<?php

namespace Traincase\HtmlToPdfTinker\Tests\Mock;

use League\Flysystem\FilesystemInterface;
use Traincase\HtmlToPdfTinker\Drivers\Driver;
use Traincase\HtmlToPdfTinker\DTO\PdfToGenerateDTO;
use Traincase\HtmlToPdfTinker\Exceptions\UnsupportedOutputTypeException;

class TestDriver extends Driver
{
    /**
     * @inheritDoc
     */
    public function storeOnFilesystem(FilesystemInterface $filesystem, PdfToGenerateDTO $dto): string
    {
        throw new UnsupportedOutputTypeException(
            sprintf('Driver "%s" does not support outputting PDFs to the file system', get_class($this))
        );
    }
}
