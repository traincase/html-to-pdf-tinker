<?php

namespace Traincase\HtmlToPdfTinker\Drivers;

use Exception;
use League\Flysystem\Filesystem;
use mikehaertl\wkhtmlto\Pdf;
use Traincase\HtmlToPdfTinker\DTO\PdfToGenerateDTO;
use Traincase\HtmlToPdfTinker\Exceptions\PdfCouldNotBeCreatedException;

class WkhtmltopdfDriver extends Driver
{
    private Pdf $wkhtmltopdf;

    public function __construct(Pdf $wkhtmltopdf)
    {
        $this->wkhtmltopdf = $wkhtmltopdf;
    }

    /**
     * Create the PDF and return it in string format.
     *
     * @param Filesystem $filesystem Filesystem used for storing the PDF
     * @param PdfToGenerateDTO $dto Data needed to generate the PDF file
     * @return string Filepath to the generated PDF file
     * @throws PdfCouldNotBeCreatedException
     */
    public function storeOnFilesystem(Filesystem $filesystem, PdfToGenerateDTO $dto): string
    {
        try {
            $options = array_merge([
                'orientation' => $dto->orientation
            ], $dto->options);

            $pdf = $this->generatePdf($dto->html, $options);

            if (!$pdf) {
                throw new \Exception('Wkhtmltopdf could not create PDF');
            }

            $fullPath = $this->getFullPath($dto->path, $dto->filename);

            $filesystem->write($fullPath, $pdf);

            return $fullPath;
        } catch (\Exception $e) {
            throw new PdfCouldNotBeCreatedException('Wkhtmltopdf could not create PDF', $e->getCode(), $e);
        }
    }

    /**
     * @param string $html
     * @param array $options
     * @return string
     * @throws Exception
     */
    protected function generatePdf(string $html, array $options): string
    {
        // Cloning the wkhtmltopdf class, since we're not able to reset/flush it.
        // If we generate multiple pdfs with the same driver we don't want
        // pages of the first PDF showing up in the second and nth...
        $wk = clone $this->wkhtmltopdf;

        $wk->setOptions($options);

        $wk->addPage($html);

        $pdf = $wk->toString();

        return (string) $pdf;
    }
}
