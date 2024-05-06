<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManageQuestions extends ManageRecords
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->mutateFormDataUsing(function (array $data): array {
                $data['text'] = str_replace('src="../storage', 'src="'.env('APP_URL').'/storage', $data['text']);
                session(['cours' => $data['cours']]);
                session(['2providers' => $data['prov']]);

                //   session(['cours'=>$data['cours']]);
                return $data;
            })->after(function ($record) {
                $txt = "New question created ! <br>
                    Text: $record->text <br>
                    Module: ".$record->moduleRel->name.' <br>
                    Certification: '.$record->certif->name.' <br>
                    ';
                \App\Models\Journ::add(auth()->user(), 'Questions', 1, $txt);
            })
                ->successRedirectUrl(fn (Model $record): string => $this->getResource()::getUrl('view', [
                    'record' => $record->id,
                ]))->createAnother(false),
        ];
    }

    public function getTabs(): array
    {
        $b1 = \App\Models\Question::withCount('answers2')->having('answers2_count', '=', 0)->count();
        $b2 = \App\Models\Question::withCount('answers')->having('answers_count', '=', 0)->count();
        $b3 = \App\Models\Question::has('reviews')->count();
        //   $b4 = \App\Models\Question::selectRaw('count(text),id')->groupBy("text",'id')->having('count(text)','>',1)->count();
        $b4 = \App\Models\Question::whereIn('text', array_column(DB::select('select text,count(text) from questions group by text having count(text) > 1'), 'text'))->count();

        return [
            __('form.all') => Tab::make()->badge(\App\Models\Question::count())
                ->badgeColor('gray'),
            __('form.nta') => Tab::make()
                ->badgeColor(fn () => $b1 > 0 ? 'danger' : 'success')->badge($b1)
                ->modifyQueryUsing(fn (Builder $query) => $query->withCount('answers2')
                    ->having('answers2_count', '=', 0)
                ),
            __('form.noa') => Tab::make()->badge($b2)
                ->modifyQueryUsing(fn (Builder $query) => $query->having('answers_count', '=', 0))
                ->badgeColor(fn () => $b2 > 0 ? 'danger' : 'success'),
            __('form.trv') => Tab::make()->badge($b3)
                ->modifyQueryUsing(fn (Builder $query) => $query->has('reviews'))
                ->badgeColor(fn () => $b3 > 0 ? 'danger' : 'success'),
            __('form.du') => Tab::make()->badge($b4)
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('text', array_column(DB::select('select text,count(text) from questions group by text having count(text) > 1'), 'text'))->orderBy('text'))
                ->badgeColor(fn () => $b4 > 0 ? 'danger' : 'success'),
        ];
    }
}
