<?php

namespace FilamentTiptapEditor\Livewire;

use Awcodes\Pounce\Enums\MaxWidth;
use Awcodes\Pounce\PounceComponent;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Contracts\View\View;

class GridBuilderPounce extends PounceComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    public ?string $statePath;

    public function mount(): void
    {
        $this->form->fill();
    }

    public static function getMaxWidth(): MaxWidth
    {
        return MaxWidth::Medium;
    }

    public function form(Form $form): Form
    {
        return $form->statePath('data')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('columns')
                            ->label(trans('filament-tiptap-editor::grid-modal.labels.columns'))
                            ->required()
                            ->default(2)
                            ->live()
                            ->minValue(2)
                            ->maxValue(12)
                            ->numeric()
                            ->step(1),
                        Select::make('stack_at')
                            ->label(trans('filament-tiptap-editor::grid-modal.labels.stack_at'))
                            ->live()
                            ->selectablePlaceholder(false)
                            ->options([
                                'none' => trans('filament-tiptap-editor::grid-modal.labels.dont_stack'),
                                'sm' => 'sm',
                                'md' => 'md',
                                'lg' => 'lg',
                            ])
                            ->default('md'),
                        Toggle::make('asymmetric')
                            ->label(trans('filament-tiptap-editor::grid-modal.labels.asymmetric'))
                            ->default(false)
                            ->live()
                            ->columnSpanFull(),
                        TextInput::make('left_span')
                            ->label(trans('filament-tiptap-editor::grid-modal.labels.asymmetric_left'))
                            ->required()
                            ->live()
                            ->minValue(1)
                            ->maxValue(12)
                            ->numeric()
                            ->visible(fn (Get $get) => $get('asymmetric')),
                        TextInput::make('right_span')
                            ->label(trans('filament-tiptap-editor::grid-modal.labels.asymmetric_right'))
                            ->required()
                            ->live()
                            ->minValue(1)
                            ->maxValue(12)
                            ->numeric()
                            ->visible(fn (Get $get) => $get('asymmetric')),
                    ]),
            ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $this->dispatch(
            'insert-content',
            type: 'grid',
            statePath: $this->statePath,
            data: $data,
        );

        $this->close();
    }

    public function close(): void
    {
        $this->reset();
        $this->dispatch('unPounce');
    }

    public function render(): View
    {
        return view('filament-tiptap-editor::modals.grid-builder');
    }
}