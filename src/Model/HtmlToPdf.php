<?php

namespace App\Model;

use App\Model\Type\Orientation;
use App\Model\Type\Paper;
use Dompdf\Dompdf;

class HtmlToPdf
{
    protected string $template;
    protected array $content;
    protected Dompdf $dompdf;

    public function __construct(string $template, array $content)
    {
        $this->template = $template;
        $this->content = $content;
        $this->dompdf = new Dompdf();
    }

    public function create(Paper $paper, Orientation $orientation): string
    {
        $this->dompdf->loadHtml($this->parseContent());
        $this->dompdf->setPaper($paper->value, $orientation->value);
        $this->dompdf->render();
        return $this->dompdf->output();
    }

    private function parseContent(): string
    {
        $html = $this->template;
        foreach ($this->content as $key => $value) {
            if (is_array($value)) {
                $value = implode('<br>', $value);
            }
            $html = str_replace("{{\$$key}}", $value, $html);
        }
        return $html;
    }
}
