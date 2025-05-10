<?php

namespace Pscibisz\Inpost\Services\Enums;

/** I used the enum class to not pass these values from .env,
 * in symfony I would choose to configure the client via config->yaml files
 */
enum ApiEndpoint: string
{
    case API_URL = 'https://sandbox-api-shipx-pl.easypack24.net/';
    case ORDER_SHIPMENTS = 'v1/organizations/%d/shipments';
    case DISPATCH_ORDERS = 'v1/organizations/%d/dispatch_orders';
}
