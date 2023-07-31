<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Models\VerificationLog;

class EloquentVerificationLogRepository
{

    public function save(VerificationLog $verificationLog) : ?VerificationLog
    {
        $verificationLog->save();
        return $verificationLog;
    }

}
