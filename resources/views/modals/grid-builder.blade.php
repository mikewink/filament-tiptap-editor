<form wire:submit.prevent="submit">
    <x-pounce::close-button />

    <x-pounce::header>
        {{ trans('filament-tiptap-editor::grid-modal.heading') }}
    </x-pounce::header>

    <x-pounce::content>
        @if(isset($data['columns']))
        <div class="rounded-lg p-4 bg-gray-100 dark:bg-gray-950 mb-4">
            <div class="grid gap-4" style="grid-template-columns: repeat({{ $data['columns'] }}, minmax(0, 1fr))">
                @if ($data['asymmetric'])
                    <div
                        class="bg-gray-300 dark:bg-gray-800 rounded-lg border border-dashed border-white dark:border-gray-600 p-0.5 text-center"
                        style="grid-column: span {{ $data['left_span'] }};"
                    >
                        <p>{{ $data['left_span'] ?? '1' }}</p>
                    </div>
                    <div
                        class="bg-gray-300 dark:bg-gray-800 rounded-lg border border-dashed border-white dark:border-gray-600 p-0.5 text-center"
                        style="grid-column: span {{ $data['right_span'] }};"
                    >
                        <p>{{ $data['right_span'] ?? '1' }}</p>
                    </div>
                @else
                    @foreach(range(1, $data['columns']) as $column)
                        <div class="bg-gray-300 dark:bg-gray-800 rounded-lg border border-dashed border-white dark:border-gray-600 p-0.5 text-center">
                            <p>{{ $column }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        @endif

        {{ $this->form }}
    </x-pounce::content>

    <x-pounce::footer>
        <x-filament::button
            type="submit"
            color="primary"
            wire:click="submit()"
        >
            {{ trans('filament-tiptap-editor::grid-modal.labels.submit') }}
        </x-filament::button>
        <x-filament::button
            type="button"
            color="gray"
            wire:click="close()"
        >
            {{ trans('filament-tiptap-editor::grid-modal.labels.cancel') }}
        </x-filament::button>
    </x-pounce::footer>
</form>