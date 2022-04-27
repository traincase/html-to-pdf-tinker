<?php

namespace Traincase\HtmlToPdfTinker\DTO;

class PdfToGenerateDTO
{
    public function __construct(
        // The filename if you plan to store this PDF on the filesystem.
        public string $filename,

        // The HTML string to convert to PDF
        public string $html,

        // Display orientation of the PDF: 'portrait' or 'landscape'
        public string $orientation,

        // Any driver specific options you wish to pass.
        public array $options,

        // The path to where you plan to store this PDF on the filesystem.
        public string $path,
    ) {
    }

    public static function fromArray(array $attributes): self
    {
        if (array_key_exists('orientation', $attributes)
            && !in_array($attributes['orientation'], ['portrait', 'landscape'])
        ) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Mode should either be "portrait" or "landscape", got "%s"',
                    $attributes['orientation']
                )
            );
        }

        return new self(
            filename: $attributes['filename'],
            html: $attributes['html'],
            orientation: $attributes['orientation'] ?? 'portrait',
            options: $attributes['options'] ?? [],
            path: $attributes['path'] ?? '/',
        );
    }
}
