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
use App\Models\Course;

class ManageExams extends ManageRecords
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        $oo=Course::has('users1')->where('pub',true)->count();
        return [
            Actions\CreateAction::make()
            ->disabled(fn():bool=>auth()->user()->ex==0 ?false :$oo<=0)
            ->color(fn():string=>$oo<=0?'gray':'primary'),
        ];
    }
}
