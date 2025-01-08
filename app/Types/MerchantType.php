<?php

namespace App\Types;

enum MerchantType: string
{
    case TENANT = 'tenant';
    case CLIENT = 'client';

}
