<?php

namespace App\Services\Admin\Users;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\User;
use App\Enums\Role;


/**
 * ExporterLaravel版のエクスポーター
 * implementsでFromCollection、WithHeadingsを強制
 */
class CsvExporterLaravelExcelService implements FromCollection, WithHeadings
{
    public function collection()
    {
        $query =  User::query();
        $users = $query->withTrashed()->orderByDesc('name')->get();

        return $users->map(fn($u) => [
            $u->name,
            $u->email,
            Role::from($u->role->value)->label(),
            $u->created_at,
            $u->deleted_at,
        ]);
    }

    public function headings(): array
    {
        return ['姓名', 'メールアドレス', '権限', '登録日時', '削除日時'];
    }
}