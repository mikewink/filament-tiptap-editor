@props([
    'statePath' => null,
    'tools' => [],
    'editor' => null,
])

@if (in_array('link', $tools))
<div x-ref="linkBubbleMenu" class="flex gap-1 items-center" x-cloak>
    <span x-text="editor().getAttributes('link').href" class="max-w-xs truncate overflow-hidden whitespace-nowrap"></span>
    <x-filament-tiptap-editor::tools.link :state-path="$statePath" icon="edit" :active="false" label="{{ trans('filament-tiptap-editor::editor.link.edit') }}" :editor="$editor"/>
    <x-filament-tiptap-editor::tools.remove-link :state-path="$statePath" :editor="$editor"/>
</div>
@endif


