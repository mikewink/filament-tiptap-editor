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
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Contracts\View\View;

class LinkPounce extends PounceComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    public ?string $statePath;
    public ?string $href;
    public ?string $linkId;
    public ?string $hreflang;
    public ?string $target;
    public ?string $rel;
    public ?string $referrerpolicy;
    public ?string $asButton;
    public ?string $buttonTheme;

    public function mount(): void
    {
        $this->form->fill([
            'href' => $this->href,
            'id' => $this->linkId,
            'hreflang' => $this->hreflang,
            'target' => $this->target,
            'rel' => $this->rel,
            'referrerpolicy' => $this->referrerpolicy,
            'as_button' => $this->asButton,
            'button_theme' => $this->buttonTheme,
        ]);
    }

    public static function getMaxWidth(): MaxWidth
    {
        return MaxWidth::Large;
    }

    public function form(Form $form): Form
    {
        return $form->statePath('data')
            ->schema([
                Grid::make(['md' => 3])
                    ->schema([
                        TextInput::make('href')
                            ->label(trans('filament-tiptap-editor::link-modal.labels.url'))
                            ->columnSpan('full')
                            ->requiredWithout('id')
                            ->validationAttribute('URL'),
                        TextInput::make('id'),
                        Select::make('target')
                            ->selectablePlaceholder(false)
                            ->options([
                                '' => trans('filament-tiptap-editor::link-modal.labels.target.default'),
                                '_blank' => trans('filament-tiptap-editor::link-modal.labels.target.new_window'),
                                '_parent' => trans('filament-tiptap-editor::link-modal.labels.target.parent'),
                                '_top' => trans('filament-tiptap-editor::link-modal.labels.target.top'),
                            ]),
                        TextInput::make('hreflang')
                            ->label(trans('filament-tiptap-editor::link-modal.labels.language')),
                        TextInput::make('rel')
                            ->columnSpan('full'),
                        TextInput::make('referrerpolicy')
                            ->label(trans('filament-tiptap-editor::link-modal.labels.referrer_policy'))
                            ->columnSpan('full'),
                        Toggle::make('as_button')
                            ->label(trans('filament-tiptap-editor::link-modal.labels.as_button'))
                            ->reactive(),
                        Radio::make('button_theme')
                            ->columnSpan('full')
                            ->columns(2)
                            ->visible(fn (Get $get) => $get('as_button'))
                            ->options([
                                'primary' => trans('filament-tiptap-editor::link-modal.labels.button_theme.primary'),
                                'secondary' => trans('filament-tiptap-editor::link-modal.labels.button_theme.secondary'),
                                'tertiary' => trans('filament-tiptap-editor::link-modal.labels.button_theme.tertiary'),
                                'accent' => trans('filament-tiptap-editor::link-modal.labels.button_theme.accent'),
                            ]),
                    ])
            ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $this->dispatch(
            'insert-content',
            type: 'link',
            statePath: $this->statePath,
            href: $data['href'],
            id: $data['id'],
            hreflang: $data['hreflang'],
            target: $data['target'],
            rel: $data['rel'],
            referrerpolicy: $data['referrerpolicy'],
            as_button: $data['as_button'],
            button_theme: $data['as_button'] ? $data['button_theme'] : '',
        );

        $this->close();
    }

    public function unsetLink(): void
    {
        $this->dispatch('unset-link', statePath: $this->statePath);

        $this->close();
    }

    public function close(): void
    {
        $this->reset();
        $this->dispatch('unPounce');
    }

    public function render(): View
    {
        return view('filament-tiptap-editor::modals.link');
    }
}