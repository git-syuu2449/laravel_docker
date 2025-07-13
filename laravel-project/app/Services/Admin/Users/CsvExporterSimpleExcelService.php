<?php

namespace App\Services\Admin\Users;

use Spatie\SimpleExcel\SimpleExcelWriter;

use App\Models\User;
use App\Enums\Role;


class CsvExporterSimpleExcelService
{
    public function writeTo(SimpleExcelWriter $writer): void
    {

        $query =  User::query();
        $users = $query->withTrashed()->orderByDesc('name')->get();

        // 対象がいなくともヘッダーのみで出力するため制御はしない
        // 1行ずつ書き込み
        // $writer->addHeader(header: ['姓名', 'メールアドレス', '権限', '登録日時', '削除日時']);
        // $users->each(function(User $user)use($writer) {
        //     $writer->addRow([$user->name, $user->email, Role::from($user->role->value)->label(), $user->created_at, $user->deleted_at]);
        // });
        // addRowsでまとめて書き込み（内部的には同じ）
        $writer->addHeader(['姓名', 'メールアドレス', '権限', '登録日時', '削除日時'])
               ->addRows(
                   $users->map(fn($u) => [
                       $u->name,
                       $u->email,
                       Role::from($u->role->value)->label(),
                       $u->created_at,
                       $u->deleted_at,
                   ])
               );
    }
}