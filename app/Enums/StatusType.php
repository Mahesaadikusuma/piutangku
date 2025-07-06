<?php

namespace App\Enums;

enum StatusType: string
{
    case PENDING = 'Pending';
    case SUCCESS = 'Success';
    case FAILED = 'Failed';

    public function labels(): string
    {
        return match ($this) {
            self::PENDING         => "<span class='px-3 py-1 text-sm font-medium text-white bg-yellow-500 rounded-md'>Pending</span>",
            self::SUCCESS         => "<span class='px-3 py-1 text-sm font-medium text-white bg-green-600 rounded-md'>Success</span>",
            self::FAILED          => "<span class='px-3 py-1 text-sm font-medium text-white bg-red-600 rounded-md'>Failed</span>",
        };
    }
}
