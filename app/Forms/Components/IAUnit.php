<?php

namespace App\Forms\Components;
use Filament\Forms\Components\Field;
class IAUnit extends Field
{

    /**
     * @var view-string
     */
    protected string $view = 'forms.components.iaunit';

    protected mixed $content = null;

    public function content(mixed $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): mixed
    {
        return $this->evaluate($this->content);
    }

}

