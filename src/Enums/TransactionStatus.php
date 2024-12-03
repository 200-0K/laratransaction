<?php

namespace Err0r\Laratransaction\Enums;

enum TransactionStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case EXPIRED = 'expired';
    case CANCELLED = 'cancelled';
}
