<form wire:submit.prevent="submit">
    <x-pounce::close-button />

    <x-pounce::header>
        {{ trans('filament-tiptap-editor::link-modal.heading.' . (blank($href) ? 'insert' : 'update')) }}
    </x-pounce::header>

    <x-pounce::content>
        {{ $this->form }}
    </x-pounce::content>

    <x-pounce::footer>
        <x-filament::button
            type="submit"
            color="primary"
            wire:click="submit()"
        >
            {{ trans('filament-tiptap-editor::link-modal.buttons.' . (blank($href) ? 'insert' : 'update')) }}
        </x-filament::button>

        <x-filament::button
            type="button"
            color="gray"
            wire:click="close()"
        >
            {{ trans('filament-tiptap-editor::link-modal.buttons.cancel') }}
        </x-filament::button>

        @if ($href)
        <x-filament::button
            type="button"
            color="danger"
            wire:click="unsetLink()"
            class="ms-auto"
        >
            {{ trans('filament-tiptap-editor::link-modal.buttons.remove') }}
        </x-filament::button>
        @endif
    </x-pounce::footer>
</form>