@props([
    'statePath' => null,
    'icon' => 'link',
    'label' => trans('filament-tiptap-editor::editor.link.insert_edit'),
    'active' => true,
])

@php
    $useActive = $active ? 'link' : false;
@endphp

<x-filament-tiptap-editor::button
    action="openModal()"
    :active="$useActive"
    :label="$label"
    :icon="$icon"
    x-data="{
        openModal() {
            let link = this.editor().getAttributes('link');
            let arguments = {
                href: link.href || '',
                linkId: link.id || null,
                target: link.target || null,
                hreflang: link.hreflang || null,
                rel: link.rel || null,
                referrerpolicy: link.referrerpolicy || null,
                asButton: link.as_button || null,
                buttonTheme: link.button_theme || null,
                statePath: '{{ $statePath }}',
            };
            $wire.dispatch('pounce', { component: 'link-pounce', arguments: arguments });
        }
    }"
/>
