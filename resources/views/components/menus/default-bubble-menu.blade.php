@props([
    'statePath' => null,
    'tools' => [],
    'editor' => null,
])

<div x-ref="defaultBubbleMenu" class="flex gap-1 items-center" x-cloak>
    @if (in_array('bold', $tools)) <x-filament-tiptap-editor::tools.bold :state-path="$statePath" :editor="$editor" /> @endif
    @if (in_array('italic', $tools)) <x-filament-tiptap-editor::tools.italic :state-path="$statePath" :editor="$editor" /> @endif
    @if (in_array('strike', $tools)) <x-filament-tiptap-editor::tools.strike :state-path="$statePath" :editor="$editor" /> @endif
    @if (in_array('underline', $tools)) <x-filament-tiptap-editor::tools.underline :state-path="$statePath" :editor="$editor" /> @endif
    @if (in_array('superscript', $tools)) <x-filament-tiptap-editor::tools.superscript :state-path="$statePath" :editor="$editor" /> @endif
    @if (in_array('subscript', $tools)) <x-filament-tiptap-editor::tools.subscript :state-path="$statePath" :editor="$editor" /> @endif
    @if (in_array('lead', $tools)) <x-filament-tiptap-editor::tools.lead :state-path="$statePath" :editor="$editor" /> @endif
    @if (in_array('small', $tools)) <x-filament-tiptap-editor::tools.small :state-path="$statePath" :editor="$editor" /> @endif
    @if (in_array('link', $tools)) <x-filament-tiptap-editor::tools.link :state-path="$statePath" :editor="$editor" /> @endif
</div>


