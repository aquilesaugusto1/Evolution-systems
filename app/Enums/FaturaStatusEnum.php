<?php

namespace App\Enums;

enum FaturaStatusEnum: string
{
    case EM_ABERTO = 'Em Aberto';
    case PAGA = 'Paga';
    case ATRASADA = 'Atrasada';
    case CANCELADA = 'Cancelada';
}