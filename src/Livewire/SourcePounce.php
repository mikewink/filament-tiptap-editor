<?php

namespace FilamentTiptapEditor\Livewire;

use Awcodes\Pounce\Enums\MaxWidth;
use Awcodes\Pounce\PounceComponent;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;

class SourcePounce extends PounceComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    public ?string $statePath;

    public ?string $html;

    public function mount(): void
    {
        $this->form->fill([
            'source' => $this->html
        ]);
    }

    public static function getMaxWidth(): MaxWidth
    {
        return MaxWidth::Screen;
    }

    public function form(Form $form): Form
    {
        return $form->statePath('data')
            ->schema([
                TextArea::make('source')
                    ->label(trans('filament-tiptap-editor::source-modal.labels.source'))
                    ->extraAttributes(['class' => 'source_code_editor'])
                    ->autosize(),
            ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $content = $data['source'] ?? '<p></p>';

        $this->dispatch(
            'insert-content',
            type: 'source',
            statePath: $this->statePath,
            source: $content,
        );

        $this->close();
    }

    public static function destroyOnClose(): bool
    {
        return true;
    }

    public function close(): void
    {
        $this->dispatch('unPounce');
    }

    public function render(): View
    {
        return view('filament-tiptap-editor::modals.source');
    }
}