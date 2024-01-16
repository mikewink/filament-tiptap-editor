<form wire:submit.prevent="submit">
    <x-pounce::close-button />

    <x-pounce::header>
        {{ trans('filament-tiptap-editor::source-modal.heading') }}
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
            {{ trans('filament-tiptap-editor::source-modal.buttons.update') }}
        </x-filament::button>
        <x-filament::button
            type="button"
            color="gray"
            wire:click="close()"
        >
            {{ trans('filament-tiptap-editor::source-modal.buttons.cancel') }}
        </x-filament::button>
    </x-pounce::footer>
</form>