<?php

namespace Traincase\HtmlToPdfTinker\Drivers;

use League\Flysystem\Filesystem;
use Traincase\HtmlToPdfTinker\DTO\PdfToGenerateDTO;
use Traincase\HtmlToPdfTinker\Exceptions\UnsupportedOutputTypeException;

abstract class Driver
{
    /**
     * Create the PDF and return it in string format.
     *
     * @param Filesystem $filesystem Filesystem used for storing the PDF
     * @param PdfToGenerateDTO $dto Data needed to generate the PDF file
     * @return string Filepath to the generated PDF file
     * @throws UnsupportedOutputTypeException
     */
    abstract public function storeOnFilesystem(Filesystem $filesystem, PdfToGenerateDTO $dto): string;

    /**
     * Get the full path to a file based on a path and a filename;
     *
     * @param string $path
     * @param string $filename
     * @return string
     */
    public function getFullPath(string $path, string $filename): string
    {
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        return "{$path}/{$filename}";
    }
}
