<?php

namespace Traincase\HtmlToPdfTinker\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class PdfToGenerateDTO extends DataTransferObject
{
    /**
     * The filename if you plan to store this PDF on the filesystem.
     */
    public string $filename;

    /**
     * The HTML string to convert to PDF
     */
    public string $html;

    /**
     * Display orientation of the PDF: 'portrait' or 'landscape'
     */
    public string $orientation;

    /**
     * Any driver specific options you wish to pass.
     */
    public array $options;

    /**
     * The path to where you plan to store this PDF on the filesystem.
     */
    public string $path;

    public function __construct(array $attributes)
    {
        if (($attributes['orientation'] ?? null)
            && !in_array($attributes['orientation'], ['portrait', 'landscape'])
        ) {
            throw new \InvalidArgumentException(sprintf(
                'Mode should either be "portrait" or "landscape", got "%s"',
                $attributes['orientation']
            ));
        }

        parent::__construct(array_merge([
            'orientation' => 'portrait',
            'path' => '/',
        ], $attributes));
    }
}
