<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JournResource\Pages;
use App\Filament\Resources\JournResource\RelationManagers;
use App\Models\Journ;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
class JournResource extends Resource
{
    protected static ?string $model = Journ::class;
    protected static ?int $navigationSort = 100;
    protected static ?string $navigationGroup = 'Admin';
    protected static ?string $modelLabel = 'log';
    protected static ?string $slug = 'logs';
    protected static ?string $navigationLabel = 'Activity Logs';

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table->paginated([25,50,100,250])
        ->modifyQueryUsing(fn (Builder $query) => $query->with('userRel')->latest())
            ->columns([
                Tables\Columns\TextColumn::make('ac')->label('Action')->sortable()->badge()
                ->formatStateUsing(fn($state)=>match ($state) {
                    0 => 'S. Login',1 => 'Create',2 => 'Read',3 => 'Update',4 => 'Delete',5 => 'F. Login',
                    6 => 'Attach',7 => 'Detach',8 => 'Request',9 => 'Pass. Reset',10 => 'Logout',//11 => 'F. Login',
                    _=>'N/A'
                })
                ->color(fn($state)=>match ($state) {
                    0 => 'info',1 => 'primary',2 => 'gray',3 => 'warning',4 => 'danger',5 => 'danger',
                    6 => 'lime',7 => 'purple',8 => 'violet',9 => 'yellow',10 => 'stone',//11 => 'F. Login',
                    _=>'gray'
                }),
                Tables\Columns\TextColumn::make('text')->label('Details')->limit(15),
                Tables\Columns\TextColumn::make('userRel.name')->label('User')->sortable(),
                Tables\Columns\TextColumn::make('fen')->label('Page')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->dateTime()->sortable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')->label('Users')
                ->relationship(name: 'userRel', titleAttribute: 'name')->multiple()
                ->searchable()
                ->preload(),
                Tables\Filters\SelectFilter::make('ac')->label('Action')->multiple()
                ->options([
                    0 => 'S. Login',1 => 'Create',2 => 'Read',3 => 'Update',4 => 'Delete',5 => 'F. Login',
                    6 => 'Attach',7 => 'Detach',8 => 'Request',9 => 'Pass. Reset',10 => 'Logout',//11 => 'F. Login',
                ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Date start'),
                        Forms\Components\DatePicker::make('created_until')->label('Date end'),
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators[] = \Filament\Tables\Filters\Indicator::make('Date from ' . \Illuminate\Support\Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = \Filament\Tables\Filters\Indicator::make('Date until ' . \Illuminate\Support\Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
/*                 Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
 */            ])
            ->deferLoading()->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageJourns::route('/'),
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('userRel.name')->label('User'),
                Infolists\Components\TextEntry::make('ac')->label('Action')->formatStateUsing(fn($state)=>match ($state) {
                    0 => 'S. Login',1 => 'Create',2 => 'Read',3 => 'Update',4 => 'Delete',5 => 'F. Login',
                    6 => 'Attach',7 => 'Detach',8 => 'Request',9 => 'Pass. Reset',10 => 'Logout',//11 => 'F. Login',
                    _=>'N/A'
                })->badge()->color(fn($state)=>match ($state) {
                    0 => 'info',1 => 'primary',2 => 'gray',3 => 'warning',4 => 'danger',5 => 'danger',
                    6 => 'primary',7 => 'danger',8 => 'warning',9 => 'info',10 => 'danger',//11 => 'F. Login',
                    _=>'gray'
                }),
                Infolists\Components\TextEntry::make('fen')->label('Page'),
                Infolists\Components\TextEntry::make('ip')->label('IP Address'),
                Infolists\Components\TextEntry::make('loc')->label('Location'),
                Infolists\Components\TextEntry::make('created_at')->label('Date')->dateTime(),
                Infolists\Components\TextEntry::make('ua')->label('User-Agent')->columnSpanFull(),
                Infolists\Components\TextEntry::make('text')->label('Content')->html()->columnSpanFull(),
            ])->columns([
                'sm' => 2,
                'md' => 3,
                'lg' => 4,
            ]);
    }
}
