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
            Actions\CreateAction::make()->after(function ($data) {
                $txt = 'New answer created ! <br>
                Text: '.$data['text'].' <br>
                ';
                \App\Models\Journ::add(auth()->user(), 'Answers', 1, $txt);
            }),
        ];
    }
}
