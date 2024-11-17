<?php

namespace Err0r\Laratransaction\Enums;

enum PaymentMethod: string
{
    case CREDIT_CARD = 'credit_card';
    case DEBIT_CARD = 'debit_card';
    case BANK_TRANSFER = 'bank_transfer';
    case CASH = 'cash';
    case OTHER = 'other';
}
