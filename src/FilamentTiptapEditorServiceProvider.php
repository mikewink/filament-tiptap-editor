<?php

namespace FilamentTiptapEditor;

use Filament\Facades\Filament;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use FilamentTiptapEditor\Commands\MakeBlockCommand;
use FilamentTiptapEditor\Livewire\GridBuilderPounce;
use FilamentTiptapEditor\Livewire\LinkPounce;
use FilamentTiptapEditor\Livewire\MediaPounce;
use FilamentTiptapEditor\Livewire\OembedPounce;
use FilamentTiptapEditor\Livewire\SourcePounce;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Vite;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentTiptapEditorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-tiptap-editor')
            ->hasConfigFile()
            ->hasAssets()
            ->hasTranslations()
            ->hasCommands([
                MakeBlockCommand::class
            ])
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('tiptap-converter', function () {
            return new TiptapConverter();
        });

        $assets = [
            AlpineComponent::make('tiptap', __DIR__ . '/../resources/dist/filament-tiptap-editor.js'),
            Css::make('tiptap', __DIR__ . '/../resources/dist/filament-tiptap-editor.css')->loadedOnRequest(),
        ];

        if (config('filament-tiptap-editor.extensions_script')) {
            $assets[] = Js::make('tiptap-custom-extension-scripts', Vite::asset(config('filament-tiptap-editor.extensions_script')));
        }

        if (config('filament-tiptap-editor.extensions_styles')) {
            $assets[] = Css::make('tiptap-custom-extension-styles', Vite::asset(config('filament-tiptap-editor.extensions_styles')));
        }

        FilamentAsset::register($assets, 'awcodes/tiptap-editor');
    }

    public function packageBooted(): void
    {
        $panel = Filament::getCurrentPanel();

        if ($panel && ! $panel->hasPlugin('pouncePlugin')) {
            $panel->renderHook(
                name: 'panels::body.end',
                hook: fn (): string => Blade::render("@livewire('pounce')"),
            );
        }

        Livewire::component('link-pounce', LinkPounce::class);
        Livewire::component('media-pounce', MediaPounce::class);
        Livewire::component('grid-builder-pounce', GridBuilderPounce::class);
        Livewire::component('oembed-pounce', OembedPounce::class);
        Livewire::component('source-pounce', SourcePounce::class);
    }
}
