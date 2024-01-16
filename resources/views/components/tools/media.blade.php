@props([
    'statePath' => null,
    'editor' => null,
])

@php
    if (str(config('filament-tiptap-editor.media_action'))->contains('\\Curator')) {
        $action = "\$wire.dispatchFormEvent('tiptap::setMediaContent', '" . $statePath . "', arguments);";
    } else {
        $action = "\$wire.\$dispatch('pounce', { component: 'media-pounce', arguments })";
    }
@endphp

<x-filament-tiptap-editor::button
    action="openModal()"
    label="{{ trans('filament-tiptap-editor::editor.media') }}"
    active="image"
    icon="media"
    x-data="{
        openModal() {
            let media = this.editor().getAttributes('image');
            let arguments = {
                type: 'media',
                src: media.src || null,
                alt: media.alt || null,
                title: media.title || null,
                width: media.width || null,
                height: media.height || null,
                lazyLoad: media.lazy || false,
                statePath: '{{ $statePath }}',
                disk: '{{ $editor->getDisk() }}',
                directory: '{{ $editor->getDirectory() }}',
                acceptedFileTypes: {{ Js::from($editor->getAcceptedFileTypes()) }},
                maxFileSize: {{ $editor->getMaxFileSize() }},
            };

            {{ $action }}
        }
    }"
/>