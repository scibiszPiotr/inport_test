<?php

namespace Pscibisz\Inpost\Services\Enums;

enum Status: string
{
    case CONFIRMED = 'confirmed';
    case NEW = 'new';
    case CREATED = 'created';
    case OFFER_SELECTED = 'offer_selected';
    case OFFER_PREPARED = 'offers_prepared';
}