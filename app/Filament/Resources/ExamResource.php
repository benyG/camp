<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamResource\Pages;
use App\Filament\Resources\ExamResource\RelationManagers;
use App\Models\Exam;
use App\Models\Info;
use App\Models\Module;
use App\Models\Course;
use App\Models\User;
use App\Models\Vague;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Closure;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $modelLabel = 'assessment';
    protected static ?string $navigationLabel = 'Bootcamp';
    protected static ?string $slug = 'bootcamp';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Title')
                    ->required()->maxLength(255)->unique()->columnSpanFull(),
                Forms\Components\TextInput::make('timer')->maxLength(Info::findOrFail(1)->maxt)
                ->required()->rules(['numeric'])->inputMode('numeric')->inlineLabel(),
                Forms\Components\Toggle::make('type')->label('Is an Exam ?')->hidden(auth()->user()->ex!=0)
                ->declined(auth()->user()->ex!=0)->live(),
                Forms\Components\DateTimePicker::make('due')->seconds(false)->label('Due Date')
                ->required(fn (Get $get) => $get('type') == true)
                ->hidden(auth()->user()->ex!=0),
                Forms\Components\TextInput::make('from')
                ->hidden(),
                Forms\Components\Select::make('modules')->label('Modules')
                ->multiple()
                ->minItems(1)
                ->relationship(name: 'modules', titleAttribute: 'name')
                ->options(function(){
                    $vagues= Course::with('modules')->get();
                    $bg=array();
                    foreach ($vagues as $vague) {
                        $ez=$vague->modules;
                        $ee=array();
                        foreach($ez as $us){
                            $ee[$us->id]=$us->name;
                        }
                        $bg[$vague->name]=$ee;
                    }
                    return $bg;
                })
                ->preload(),
                Forms\Components\Select::make('users')->label('Users')
                ->multiple()
                ->required(auth()->user()->ex==0)
                ->relationship(name: 'users', titleAttribute: 'name')
                ->hidden(auth()->user()->ex!=0)
                ->options(function(){
                    $vagues= Vague::with('users')->get();
                    $users= User::where('vague',null)->where('id','<>',auth()->user()->id)->get();
                    $bg=array();
                    $er=array();
                    foreach($users as $uy){
                        $er[$uy->id]=$uy->name;
                    }
                    $bg['No session']=$er;
                    foreach ($vagues as $vague) {
                        $ez=$vague->users;
                        $ee=array();
                        foreach($ez as $us){
                            $ee[$us->id]=$us->name;
                        }
                        $bg[$vague->name]=$ee;
                    }
                    return $bg;
                })
                ->preload(),
                Forms\Components\Textarea::make('descr')->label('Description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(Exam::selectRaw('distinct(exam),exams.id,name,descr,due,type,timer,added_at,added,start_at,comp_at,exams.from')->join('exam_users', 'exams.id', '=', 'exam_users.exam')
        ->where('from',auth()->user()->id)->orwhere('exam_users.user',auth()->user()->id)->latest('added_at'))
        ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label('Title')
                ->description(fn (Exam $record): ?string => $record->descr),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (Exam $record): string => $record->type== 0? 'Test' : 'Exam')
                    ->color(fn (string $state): string => match ($state) {'0' => 'warning','1' => 'success',})
                    ->sortable(),
                    Tables\Columns\TextColumn::make('users.name')->label('Users')
                    ->hidden(auth()->user()->ex!=0),
                Tables\Columns\TextColumn::make('added_at')->label('Created on')
                    ->dateTime()->hidden(auth()->user()->ex!=0)
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('added')->label('Affected on')
                    ->dateTime()->hidden(auth()->user()->ex==0)->since()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('due')->label('Due Date')
                    ->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('Start on')->label('Started on')
                    ->dateTime()->hidden(auth()->user()->ex==0)
                    ->sortable(),
                Tables\Columns\TextColumn::make('comp_at')->label('Completed on')
                    ->dateTime()->hidden(auth()->user()->ex==0)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('resend')->label('Results')
                ->action(function (Smail $record) {
                    foreach ($record->users as $rrr) {
                        Mail::to($rrr->email)->send(new Imail($record,$rrr->name,$rrr->email));
                        Notification::make()->success()->title('Successfully sent via SMTP to : '.$rrr->email)->send();
                    }
                })->button()->color('success')->visible(fn (): bool =>auth()->user()->ex==0),
                Tables\Actions\EditAction::make()->mutateFormDataUsing(function (array $data): array {
                    $data['from'] = auth()->id();
                    if(auth()->user()->ex!=0) {
                        $data['type']=false;
                        $data['due']=null;
                        $data['users']=auth()->id();
                    }
                    return $data;
                }),
           //     Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
             /*    Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */
            ])
            ->deferLoading()->striped()->persistFiltersInSession()
            ->persistSearchInSession()->persistColumnSearchesInSession();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageExams::route('/'),
            'certif' => Pages\ListCertif::route('/certifications'),
        ];
    }
    protected function shouldPersistTableSortInSession(): bool
    {
        return true;
    }
}
