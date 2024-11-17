<?php

namespace Err0r\Laratransaction\Enums;

enum TransactionType: string
{
    case PAYMENT = 'payment';
    case REFUND = 'refund';
}
