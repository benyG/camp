<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class Orders extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $slug = 'orders';

    protected static string $view = 'filament.pages.orders';
    protected static ?string $navigationGroup = 'Administration';
    public static function getNavigationSort(): ?int
    {
        return auth()->user()->ex == 0 ? 150 : 30;
    }

    public static function canAccess(): bool
    {
        return auth()->user()->ex < 6;
    }

    public static function getModelLabel(): string
    {
        return __('main.m18');
    }

    public static function getNavigationLabel(): string
    {
        return __('main.m18').'s';
    }

    public function getTitle(): string|Htmlable
    {
        return __('main.w41');
    }

    public function getHeading(): string
    {
        return __('main.w41');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::latest())
            ->columns([
            ])
            ->filters([

            ])->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label(__('form.fil')),
            )
            ->actions([
            ])
            ->bulkActions([
            ])
            ->deferLoading()->striped()->persistFiltersInSession()
            ->persistSearchInSession()->persistColumnSearchesInSession();
    }
}
