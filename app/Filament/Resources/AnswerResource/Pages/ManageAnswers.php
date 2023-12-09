<?php

namespace App\Filament\Resources\AnswerResource\Pages;

use App\Filament\Resources\AnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAnswers extends ManageRecords
{
    protected static string $resource = AnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
