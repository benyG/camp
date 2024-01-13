<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use App\Models\SMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\Imail;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class ManageExams extends ManageRecords
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('e')->label('Certifications')->color('info')
            ->url(fn (): string => $this->getResource()::getUrl('certif')),
            Actions\CreateAction::make(),
        ];
    }
}
