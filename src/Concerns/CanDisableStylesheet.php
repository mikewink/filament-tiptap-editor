<?php

namespace FilamentTiptapEditor\Concerns;

trait CanDisableStylesheet
{
    protected ?bool $shouldDisableStylesheet = null;

    public function disableStylesheet(): static
    {
        $this->shouldDisableStylesheet = true;

        return $this;
    }

    public function shouldDisableStylesheet(): bool
    {
        return $this->shouldDisableStylesheet ?? config('filament-tiptap-editor.disable_stylesheet');
    }
}