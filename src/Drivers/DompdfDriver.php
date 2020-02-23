<?php

namespace Traincase\HtmlToPdfTinker\Drivers;

use Dompdf\Dompdf;
use Dompdf\Options;
use League\Flysystem\FilesystemInterface;
use Traincase\HtmlToPdfTinker\DTO\PdfToGenerateDTO;
use Traincase\HtmlToPdfTinker\Exceptions\PdfCouldNotBeCreatedException;

class DompdfDriver extends Driver
{
    private Dompdf $domPdf;

    public function __construct(Dompdf $dompdf)
    {
        $this->domPdf = $dompdf;
    }

    /**
     * Create the PDF and return it in string format.
     *
     * @param FilesystemInterface $filesystem Filesystem used for storing the PDF
     * @param PdfToGenerateDTO $dto Data needed to generate the PDF file
     * @return string Filepath to the generated PDF file
     * @throws \Exception
     */
    public function storeOnFilesystem(FilesystemInterface $filesystem, PdfToGenerateDTO $dto): string
    {
        try {
            $this->domPdf->setPaper('a4', $dto->orientation);
            $this->domPdf->setOptions(new Options($dto->options));
            $this->domPdf->loadHTML($this->convertCurrencies($dto->html));
            $this->domPdf->render();

            $pdf = $this->domPdf->output();

            if (!$pdf) {
                throw new \Exception('Dompdf could not create PDF');
            }

            $fullPath = $this->getFullPath($dto->path, $dto->filename);

            $filesystem->put($this->getFullPath($dto->path, $dto->filename), $pdf);

            return $fullPath;
        } catch (\Exception $e) {
            throw new PdfCouldNotBeCreatedException('Dompdf could not create PDF', $e->getCode(), $e);
        }
    }

    /**
     * @param string $html
     * @return string|string[]
     */
    private function convertCurrencies(string $html): string
    {
        $replacers = array(
            '€' => '&#0128;',
            '£' => '&pound;',
        );

        foreach ($replacers as $search => $replace) {
            $html = str_replace($search, $replace, $html);
        }

        return $html;
    }
}
