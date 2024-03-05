<?php

namespace App\Filament\Resources\AnnResource\Pages;

use App\Filament\Resources\AnnResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAnns extends ManageRecords
{
    protected static string $resource = AnnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->after(function ($data) {
                    $txt="New announcement
                    Description: ".$data['descr']." <br>
                    Url: ".$data['url']." <br>
                    Users: ".str_replace(array('1','2','3','4','5'),array('Admin','Starter','User','Pro','VIP'),implode(',',$data['type']))." <br>
                    Display: ".(intval($data['hid'])==0?'No':'Yes')." <br>
                    Due date: ".$data['due']." <br>
                    ";
                    \App\Models\Journ::add(auth()->user(),'Announcements',1,$txt);
            }),
        ];
    }
}
