<?php

namespace App\Enums;

enum MessageType: string
{
    case CREATED = 'Berhasil Menambahkan Data';
    case UPDATED = 'Berhasil Memperbarui Data';
    case DELETED = 'Berhasil Menghapus Data';
    case INFO = 'info';
    case WARNING = 'warning';
    case ERROR = 'Terjadi kesalahan, Silahkan coba lagi';

    public function message(string $entity = '', ?string $error = null): string
    {
        if ($this === MessageType::ERROR && $error) {
            return "{$this->value} {$error}";
        }

        return "{$this->value} {$entity}";
    }
}
