<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $slug = 'orders';

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

    public static function form(Form $form): Form
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });

        return $form
            ->schema([
                Forms\Components\Select::make('type')->label('Type')->required()
                    ->options([
                        0 => 'Plan',
                        1 => __('form.iac'),
                        2 => 'ECA',
                    ]),
                Forms\Components\Select::make('pbi')->label(__('form.pid'))->required()
                    ->options([
                        $ix->bp_id => 'Basic', $ix->sp_id => 'Standard', $ix->pp_id => 'Premium',
                        $ix->iac1_id => __('form.iac').' 1', $ix->iac2_id => __('form.iac').' 2', $ix->iac3_id => __('form.iac').' 3',
                        $ix->eca_id => 'ECA',
                    ]),
                Forms\Components\TextInput::make('sid')->label('Stripe ID')->default('sid'.Str::random(9))
                    ->required()->maxLength(200),
                Forms\Components\DateTimePicker::make('exp')->label('Expiration')->default(now())
                    ->required(),
                Forms\Components\TextInput::make('amount')->label(__('form.amo'))
                    ->required()->numeric()->default(0),
                Forms\Components\TextInput::make('qte')->label(__('form.qty'))
                    ->required()->numeric()->default(1),
                Forms\Components\Select::make('user')->label(__('form.us'))->required()
                    ->relationship(name: 'userRel', titleAttribute: 'name'),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });

        return $table
            ->modifyQueryUsing(fn (Builder $query) => auth()->user()->ex == 0 ? $query->latest() : $query->where('user', auth()->id())->latest())
            ->columns([
                Tables\Columns\TextColumn::make('pbi')->label(__('form.pid'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        $ix->bp_id => 'Basic Plan',$ix->sp_id => 'Standard Plan',$ix->pp_id => 'Premium Plan',
                        $ix->iac1_id => __('form.iac').' 1000',$ix->iac2_id => __('form.iac').' 2000',$ix->iac3_id => __('form.iac').' 5000',
                        $ix->eca_id => 'ECA',default => 'N/A'
                    }),
                Tables\Columns\TextColumn::make('qte')->label(__('form.qty'))->numeric(),
                Tables\Columns\TextColumn::make('sid')->label('Stripe ID')
                    ->toggleable(isToggledHiddenByDefault: true)->hidden(auth()->user()->ex != 0),
                Tables\Columns\TextColumn::make('cus')->label(__('form.cid'))
                    ->toggleable(isToggledHiddenByDefault: true)->hidden(auth()->user()->ex != 0),
                Tables\Columns\TextColumn::make('exp')->label('Expiration')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('amount')->label(__('form.amo'))
                    ->numeric()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label(__('form.pat'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('userRel.name')->label(__('form.us'))
                    ->hidden(auth()->user()->ex != 0)->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pbi')->label(__('form.pid'))->multiple()
                    ->options([
                        $ix->bp_id => 'Basic Plan', $ix->sp_id => 'Standard Plan', $ix->pp_id => 'Premium Plan',
                        $ix->iac1_id => __('form.iac').' 1000', $ix->iac2_id => __('form.iac').' 2000', $ix->iac3_id => __('form.iac').' 5000',
                        $ix->eca_id => 'ECA',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('ffb')->label(__('form.bic'))->icon('heroicon-o-document-text')->iconButton()
                    ->url(fn ($record): string => $record->li.'')->color('gray'),
                Tables\Actions\EditAction::make()->iconButton()
                    ->using(function (\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model {
                        $reco = $record->replicate();
                        $record->update($data);
                        $txt = '';
                        if ($record->wasChanged()) {
                            if ($record->wasChanged('pbi')) {
                                $txt .= "Product ID was changed from '$reco->pbi' <br>to '$record->pbi' <br>";
                            }
                            if (strlen($txt) > 0) {
                                \App\Models\Journ::add(auth()->user(), 'Billing', 3, $txt);
                            }
                        }

                        return $record;
                    }),
                Tables\Actions\DeleteAction::make()->iconButton()->after(function ($record) {
                    $txt = "Removed order ID $record->id.
                    PID: $record->pbi <br>
                    Type: ".match ($record->type) {
                        0 => 'Plan',1 => 'IA Calls', 2 => 'ECA',default => 'N/A'
                    }.' <br>
                    For user: '.$record->userRel->name.' <br>
                    ';
                    \App\Models\Journ::add(auth()->user(), 'Billing', 4, $txt);
                }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOrders::route('/'),
        ];
    }
}
