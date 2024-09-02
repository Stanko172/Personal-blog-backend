<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Models\Content;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\Enums\TiptapOutput;
use FilamentTiptapEditor\TiptapEditor;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                ->schema([
                    Grid::make(1)
                        ->schema([
                            Card::make([
                                TextInput::make('title')
                                    ->label('Title')
                                    ->required(),
                                Select::make('type')
                                    ->label('Type')
                                    ->options([
                                        'post' => 'Post',
                                        'essay' => 'Essay',
                                        'project' => 'Project',
                                    ])
                                    ->required(),
                                Textarea::make('description')
                                    ->label('Description')
                                    ->required(),
                            ]),
                            Card::make([
                                TiptapEditor::make('content')
                                    ->label('Content')
                                    ->disk('public')
                                    ->directory('uploads/images')
                                    ->output(TiptapOutput::Html)
                                    ->required(),
                            ]),
                        ])->columnSpan(2),

                    Grid::make(1)
                        ->schema([
                            Card::make([
                                FileUpload::make('cover_image')
                                    ->label('Cover Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('uploads/images')
                                    ->visibility('public')
                                    ->required(),
                            ]),
                            Card::make([
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                    ])
                                    ->required(),
                                DatePicker::make('published_at')
                                    ->label('Published At')
                                    ->required(),
                            ]),
                        ])->columnSpan(1),
                ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'edit' => Pages\EditContent::route('/{record}/edit'),
        ];
    }
}
