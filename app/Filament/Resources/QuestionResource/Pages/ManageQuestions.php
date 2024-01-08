<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class ManageQuestions extends ManageRecords
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
                Actions\CreateAction::make()->mutateFormDataUsing(function (array $data): array {
                    $data['text'] = str_replace('src="../storage','src="'.env('APP_URL').'/storage',$data['text']);
                    session(['cours'=>$data['cours']]);
                    return $data;
                })
                ->successRedirectUrl(fn (Model $record): string => $this->getResource()::getUrl('view', [
                    'record' => $record->id,
                ]))
            ];
    }
}
