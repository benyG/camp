<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Components\Tab;

class ManageOrders extends ManageRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('ddr')->label('Charges')->tooltip(__('form.orph2'))
                ->url(fn (): string => $this->getResource()::getUrl('charges'))
                ->visible(auth()->user()->can('viewAny',\App\Models\Chx::class))
                ->disabled(fn():bool=>\App\Models\Chx::all()->count()<=0)
                ->badge(fn():int=>\App\Models\Chx::all()->count())
                ->badgeColor('success')
                ->color('info'),
            Actions\CreateAction::make()
            ->mutateFormDataUsing(function (array $data): array {
                $ix = cache()->rememberForever('settings', function () {
                    return \App\Models\Info::findOrFail(1);
                });

                switch ($data['pbi']) {
                    case $ix->bp_id:
                        case $ix->sp_id:
                            case $ix->pp_id:
                                $data['type']=0;
                        break;
                        case $ix->iac2_id:
                            case $ix->iac3_id:
                                case $ix->iac1_id:
                                    $data['type']=1;
                        break;
                    case $ix->eca_id:
                        $data['type']=2;
                        break;
                    default:
                        break;
                }

                return $data;
            })
            ->after(function ($record) {
                $txt = 'New billing entry created ! <br>
                Type: '.match ($record->type) {
                    0 => 'Plan',1 => 'IA Calls', 2 => 'ECA',default => 'N/A'
                }." <br>
                PID: $record->pbi <br>
                Amount: $record->amount <br>
                Qty: $record->qte <br>
                For user : ".$record->userRel->name.' <br>';
                if ($record->type==1) {
                        \App\Models\User::where('id',$record->userRel->id)->increment('ix2', $record->qte);
                }
                \App\Models\Journ::add(auth()->user(), 'Billing', 1, $txt);
            }),
        ];
    }
    public function getTabs(): array
    {
        $b1 = \App\Models\Order::whereNull('user')->count();
        return [
            __('form.all') => Tab::make()->badge(\App\Models\Order::count())
                ->badgeColor('gray'),
            __('form.orph') => Tab::make()
                ->badgeColor(fn () => $b1 > 0 ? 'danger' : 'success')->badge($b1)
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('user')
                ),
        ];
    }
}
