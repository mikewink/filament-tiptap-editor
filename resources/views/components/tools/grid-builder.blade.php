@props([
    'statePath' => null,
])

<x-filament-tiptap-editor::button
    action="$wire.$dispatch('pounce', { component: 'grid-builder-pounce', arguments: { 'statePath': '{{ $statePath }}' } })"
    active="grid-builder"
    label="{{ trans('filament-tiptap-editor::editor.grid-builder.label') }}"
    icon="grid-builder"
/>
