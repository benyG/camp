<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOrders extends ManageRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->after(function ($record) {
                $txt = 'New billing entry created ! <br>
                Type: '.match ($record->type) {
                    0 => 'Plan',1 => 'IA Calls', 2 => 'ECA',default => 'N/A'
                }." <br>
                PID: $record->pbi <br>
                Amount: $record->amount <br>
                Qty: $record->qte <br>
                For user : ".$record->userRel->name.' <br>
                ';
                \App\Models\Journ::add(auth()->user(), 'Billing', 1, $txt);
            }),
        ];
    }
}
