<?php

namespace App\Filament\Imports;

use App\Models\Book;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class BookImporter extends Importer
{
    protected static ?string $model = Book::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('user')
                ->relationship(resolveUsing: 'email')
                ->requiredMapping(),
            ImportColumn::make('title')
                ->rules(['required', 'max:255'])
                ->requiredMapping(),
            ImportColumn::make('author')
                ->rules(['required', 'max:255'])
                ->requiredMapping(),
        ];
    }

    public function resolveRecord(): ?Book
    {
        // return Book::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Book();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your book import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
