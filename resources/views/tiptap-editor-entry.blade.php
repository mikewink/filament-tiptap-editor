@if (config('filament-tiptap-editor.extensions_script') || config('filament-tiptap-editor.extensions_styles'))
    @vite([
        config('filament-tiptap-editor.extensions_script', null),
        config('filament-tiptap-editor.extensions_styles', null)
    ])
@endif

<div
    @if (! $shouldDisableStylesheet())
        x-data="{}"
        x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('tiptap', 'awcodes/tiptap-editor'))]"
    @endif
    class="tiptap-editor"
>
    <div class="ProseMirror">
        {!! tiptap_converter()->asHTML($getState()) !!}
    </div>
</div>