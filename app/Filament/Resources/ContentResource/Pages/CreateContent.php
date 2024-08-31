<?php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateContent extends CreateRecord
{
    protected static string $resource = ContentResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $data['slug'] = Str::slug($data['title']);
        $data['user_id'] = auth()->id();

        return static::getModel()::create($data);
    }
}
