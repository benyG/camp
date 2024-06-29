<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use App\Models\Chx;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

use Illuminate\Contracts\Support\Htmlable;

class Charges extends Page implements HasForms,HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament.resources.order-resource.pages.charges';

    public function getTitle(): string|Htmlable
    {
        return 'Charges';
    }

    public function getHeading(): string
    {
        return 'Charges';
    }

    public function getSubheading(): ?string
    {
        return __('form.orph2');
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(Chx::query())
            ->emptyStateHeading(__('main.bi1'))->emptyStateIcon('heroicon-o-bookmark')
            ->columns([
                Tables\Columns\TextColumn::make('sid')->label('Stripe ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pli')->label('Payment Intent')
                    ->searchable(),
                Tables\Columns\TextColumn::make('i1')->label('Email'),
                Tables\Columns\TextColumn::make('i2')->label(__('form.amo')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('ffb')->label(__('form.bic'))->icon('heroicon-o-document-text')->iconButton()
                ->url(fn ($record): string => $record->rli.'')->color('gray')->openUrlInNewTab()
                ->visible(fn($record):bool=>!empty($record->rli)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
}
