<?php

namespace Err0r\Laratransaction\Enums;

enum PaymentMethod: string
{
    case CREDIT_CARD = 'credit_card';
    case BANK_TRANSFER = 'bank_transfer';
    case CASH = 'cash';
    case PAYPAL = 'paypal';
    case OTHER = 'other';
}
