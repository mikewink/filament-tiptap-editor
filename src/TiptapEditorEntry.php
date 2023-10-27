<?php

namespace FilamentTiptapEditor;

use Filament\Infolists\Components\Entry;
use FilamentTiptapEditor\Concerns\CanDisableStylesheet;

class TiptapEditorEntry extends Entry
{
    use CanDisableStylesheet;

    protected string $view = 'filament-tiptap-editor::tiptap-editor-entry';
}