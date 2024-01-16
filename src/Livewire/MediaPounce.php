<?php

namespace FilamentTiptapEditor\Livewire;

use Awcodes\Pounce\Enums\MaxWidth;
use Awcodes\Pounce\PounceComponent;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaPounce extends PounceComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    public ?string $statePath = null;
    public ?string $disk = null;
    public ?string $directory = null;
    public ?array $acceptedFileTypes = null;
    public ?int $maxFileSize = null;
    public ?string $src = null;
    public ?string $alt = null;
    public ?string $title = null;
    public ?int $width = null;
    public ?int $height = null;
    public ?bool $lazyLoad = false;
    public ?string $linkText = null;
    public ?string $fileType = null;

    public function mount(): void
    {
        $source = $this->src
            ? $this->directory . Str::of($this->src)->after($this->directory)
            : null;

        $this->form->fill([
            'src' => $source,
            'alt' => $this->alt,
            'title' => $this->title,
            'width' => $this->width,
            'height' => $this->height,
            'lazy' => $this->lazyLoad ?? false,
            'link_text' => $this->linkText,
            'type' => $this->fileType,
        ]);
    }

    public static function getMaxWidth(): MaxWidth
    {
        return MaxWidth::TwoExtraLarge;
    }

    public function form(Form $form): Form
    {
        return $form->statePath('data')
            ->schema([
                Grid::make()
                    ->schema([
                        Group::make([
                            FileUpload::make('src')
                                ->label(trans('filament-tiptap-editor::media-modal.labels.file'))
                                ->disk($this->disk)
                                ->directory($this->directory)
                                ->visibility(config('filament-tiptap-editor.visibility'))
                                ->preserveFilenames(config('filament-tiptap-editor.preserve_file_names'))
                                ->acceptedFileTypes($this->acceptedFileTypes)
                                ->maxFiles(1)
                                ->maxSize($this->maxFileSize)
                                ->imageResizeMode(config('filament-tiptap-editor.image_resize_mode'))
                                ->imageCropAspectRatio(config('filament-tiptap-editor.image_crop_aspect_ratio'))
                                ->imageResizeTargetWidth(config('filament-tiptap-editor.image_resize_target_width'))
                                ->imageResizeTargetHeight(config('filament-tiptap-editor.image_resize_target_height'))
                                ->required()
                                ->live()
                                ->afterStateUpdated(function (TemporaryUploadedFile $state, Set $set) {
                                    if (Str::contains($state->getMimeType(), 'image')) {
                                        $set('type', 'image');
                                    } else {
                                        $set('type', 'document');
                                    }

                                    if ($dimensions = $state->dimensions()) {
                                        $set('width', $dimensions[0]);
                                        $set('height', $dimensions[1]);
                                    }
                                })
                                ->saveUploadedFileUsing(function (BaseFileUpload $component, TemporaryUploadedFile $file, Set $set) {
                                    $filename = $component->shouldPreserveFilenames()
                                        ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                                        : Str::uuid();
                                    $storeMethod = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';
                                    $extension = $file->getClientOriginalExtension();

                                    if (Storage::disk($component->getDiskName())->exists(ltrim($component->getDirectory() . '/' . $filename . '.' . $extension, '/'))) {
                                        $filename = $filename . '-' . time();
                                    }

                                    $upload = $file->{$storeMethod}($component->getDirectory(), $filename . '.' . $extension, $component->getDiskName());

                                    return Storage::disk($component->getDiskName())->url($upload);
                                }),
                        ])->columnSpan(1),
                        Group::make([
                            TextInput::make('link_text')
                                ->label(trans('filament-tiptap-editor::media-modal.labels.link_text'))
                                ->required()
                                ->visible(fn (Get $get) => $get('type') == 'document'),
                            TextInput::make('alt')
                                ->label(trans('filament-tiptap-editor::media-modal.labels.alt'))
                                ->hidden(fn (Get $get) => $get('type') == 'document')
                                ->hintAction(
                                    Action::make('alt_hint_action')
                                        ->label('?')
                                        ->color('primary')
                                        ->url('https://www.w3.org/WAI/tutorials/images/decision-tree', true)
                                ),
                            TextInput::make('title')
                                ->label(trans('filament-tiptap-editor::media-modal.labels.title')),
                            Group::make([
                                TextInput::make('width'),
                                TextInput::make('height'),
                            ])->columns()->hidden(fn (Get $get) => $get('type') == 'document'),
                            Checkbox::make('lazy')
                                ->label(trans('filament-tiptap-editor::media-modal.labels.lazy'))
                                ->hidden(fn (Get $get) => $get('type') == 'document'),
                        ])->columnSpan(1),
                    ]),
                Hidden::make('type')
                    ->default('document'),
            ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        if (config('filament-tiptap-editor.use_relative_paths')) {
            $source = Str::of($data['src'])
                ->replace(config('app.url'), '')
                ->ltrim('/')
                ->prepend('/');
        } else {
            $source = str_starts_with($data['src'], 'http')
                ? $data['src']
                : Storage::disk(config('filament-tiptap-editor.disk'))->url($data['src']);
        }

        $this->dispatch(
            'insert-content',
            type: 'media',
            statePath: $this->statePath,
            media: [
                'src' => $source,
                'alt' => $data['alt'] ?? null,
                'title' => $data['title'] ?? null,
                'width' => $data['width'] ?? null,
                'height' => $data['height'] ?? null,
                'lazy' => $data['lazy'] ?? false,
                'link_text' => $data['link_text'] ?? null,
            ],
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
        return view('filament-tiptap-editor::modals.media');
    }
}