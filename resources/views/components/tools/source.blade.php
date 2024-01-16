@props([
    'statePath' => null,
])

<x-filament-tiptap-editor::button
    action="openModal()"
    label="{{ trans('filament-tiptap-editor::editor.source') }}"
    icon="source"
    x-data="{
        openModal() {
            $wire.$dispatch('pounce', { component: 'source-pounce', arguments: { 'statePath': '{{ $statePath }}', html: this.editor().getHTML() } })
        }
    }"
/>
