<?php

namespace App\Filament\Resources;

use Exception;
use App\Filament\Resources\SmailResource\Pages;
use App\Filament\Resources\SmailResource\RelationManagers;
use App\Models\SMail;
use App\Models\Vague;
use App\Models\User;
use App\Notifications\NewMail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
//use Illuminate\Support\Facades\Mail;
//use App\Mail\Imail;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Notification as Notif;

class SmailResource extends Resource
{
    protected static ?string $model = SMail::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $modelLabel = 'message';
    protected static ?string $slug = 'messages';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user')->label('To')
                ->multiple()
                ->required(fn():bool=>auth()->user()->ex<2)
                ->relationship(name: 'users', titleAttribute: 'name')
                ->options(function(){
                    $vagues= Vague::with('users')->get();
                    $users= User::where('vague',null)->where('id','<>',auth()->user()->id)->get();
                    $bg=array();
                    $er=array();
                    foreach($users as $uy){
                        $er[$uy->id]=$uy->name;
                    }
                    $bg['No class']=$er;
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
            ->preload()->hidden(fn():bool=>auth()->user()->ex>=2),
                Forms\Components\TextInput::make('sub')->label('Subject')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('from')
                    ->hidden(),
                TinyEditor::make('content')
                    ->required()
                    ->fileAttachmentsDisk('public')->fileAttachmentsVisibility('public')->fileAttachmentsDirectory('uploads')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->striped()
        ->query(Smail::selectRaw('distinct (mail),hid, smails.id,sub,content,smails.from,smails.created_at,smails.updated_at,users_mail.sent,users_mail.read,read_date,last_sent')->join('users_mail', 'smails.id', '=', 'users_mail.mail')
       // ->join('users', 'users.id', '=', 'users_mail.user')
       ->where(function (Builder $query) {
        $query->where('from',auth()->user()->id)
              ->where('hid',false);})->orwhere('user',auth()->user()->id)->latest())
            ->columns([
                Tables\Columns\TextColumn::make('sub')->label('Subject')
                ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('from')->label('Correspondants')
                ->formatStateUsing(fn (Model $record): string => $record->from== auth()->user()->id? $record->users2->pluck('name') : $record->user1->name)
                ->sortable()->hidden(fn():bool=>auth()->user()->ex>=2),
             Tables\Columns\IconColumn::make('sent')->label('Sent via SMTP')->hidden(fn():bool=>auth()->user()->ex>=2)
             ->icon(fn($state):string=>$state==1?'heroicon-o-envelope':'')
                 ->tooltip(fn (Smail $record): string =>$record->sent==1? "Last sent on {$record->last_sent}":"")
                 ->sortable(),
                 Tables\Columns\IconColumn::make('read')->label('Read ?')
                 ->color(fn($state):string=>$state==1?'success':'danger')
                 ->icon(fn($state, Smail $record):string=>$record->from== auth()->user()->id? "":($state==1?'heroicon-o-envelope-open':'heroicon-o-envelope'))
                     ->tooltip(fn (Smail $record): string =>$record->from== auth()->user()->id? "":($record->read==1? "Read on {$record->read_date}":""))
                     ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Date')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Read')->beforeFormFilled(function (Model $record)
                {
                  if(!$record->read)  $record->users2()->updateExistingPivot(auth()->user()->id, ['read' => true,]);
                })->modalHeading(fn (Model $record): string=>$record->sub),
                Tables\Actions\Action::make('transfer')->modalHeading('Transfer')->label('Transfer')->form([
                    Forms\Components\Select::make('user4')->label('To')
                    ->multiple()->hidden(fn():bool=>auth()->user()->ex>=2)
                    ->required(fn():bool=>auth()->user()->ex<2)
                    ->relationship(name: 'users', titleAttribute: 'name')
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
                    Forms\Components\TextInput::make('sub')->label('Subject')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('from')
                        ->hidden(),
                    TinyEditor::make('content')
                        ->required()
                        ->fileAttachmentsDisk('public')->fileAttachmentsVisibility('public')->fileAttachmentsDirectory('uploads')
                        ->columnSpanFull(),
                ])->fillForm(fn (Smail $record): array => [
                    'content' => $record->content,
                    'sub' => $record->sub,
                ])->action(function (array $data, Smail $record, string $model): void {
                    $data['from']=auth()->user()->id;
                    $rec=$model::create($data);
                    $rec->users()->attach($data['user4']);
                })->hidden(fn():bool=>auth()->user()->ex>=2),
                Tables\Actions\Action::make('resend')->color('warning')->label('Resend')
                ->action(function (Smail $record) {
                    $para=array(); $opt='1';
                    if(auth()->user()->ex>=2){
                        $para=[auth()->user()->name,auth()->user()->email];
                        $opt='4';
                    }
                    try {
                        Notif::send($record->users, new NewMail($record,$para,$opt));
                        Notification::make()->success()->title('Sent via SMTP')->send();
                    } catch (Exception $exception) {
                        Notification::make()
                            ->title('We were not able to reach some recipients via SMTP')
                            ->danger()
                            ->send();
                    }
                })
                ->visible(fn(Smail $record)=>$record->from== auth()->user()->id),
                Tables\Actions\Action::make('Delete')->modalHeading('Delete message')->label('Delete')
                ->requiresConfirmation()->color('danger')->modalIcon('heroicon-o-trash')->modalIconColor('warning')
                ->action(function (Smail $record) {
                    if($record->from==auth()->user()->id){
                        $record->hid=true;
                        $record->save();
                    }else {
                        $record->users()->detach(auth()->user()->id);
                    }
                    Notification::make('e')->title('Deleted successfully')->icon('heroicon-o-trash')
                    ->iconColor('success')->send();
                })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('Delete')->modalHeading('Delete messages')->label('Delete')
                    ->requiresConfirmation()->color('danger')->modalIcon('heroicon-o-trash')->modalIconColor('warning')
                    ->action(function (Collection $record) {
                        $record->each(function (Smail $rec, int $key) {
                        if($rec->from==auth()->user()->id){
                            $rec->hid=true;
                            $rec->save();
                        }else {
                            $rec->users()->detach(auth()->user()->id);
                        }
                        });
                        Notification::make('e')->title('Deleted successfully')->icon('heroicon-o-trash')
                        ->iconColor('success')->send();
                    })->deselectRecordsAfterCompletion()
                    ,
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSmails::route('/'),
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('user.name')->label('From')
                ->hidden(fn (Model $record): bool => $record->from== auth()->user()->id),
                Infolists\Components\TextEntry::make('users.name')->label('Sent to')
                ->hidden(fn (Model $record): bool => $record->users->contains(auth()->user())),
                Infolists\Components\TextEntry::make('content')->html()->columnSpanFull(),
            ]);
    }
    public static function getNavigationBadge(): ?string
    {
        return Smail::selectRaw('user')->join('users_mail', 'smails.id', '=', 'users_mail.mail')
        ->where('read',false)->where('user',auth()->user()->id)->get()->count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return Smail::selectRaw('user')->join('users_mail', 'smails.id', '=', 'users_mail.mail')
        ->where('read',false)->where('user',auth()->user()->id)->get()->count() > 0 ? 'danger' : 'primary';
    }
}
