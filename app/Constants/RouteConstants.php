<?php

namespace App\Constants;

abstract class RouteConstants
{
    //Gets
    const CONTATO_ID = '/contato/id/{id}';
    const CONTATO_NOME = '/contato/nome/{nome}';
    const CONTATO_EMAIL = '/contato/email/{email}';
    const CONTATOS = '/contatos';

    //Insert
    const CONTATO_INSERT = '/contato';

    //Update
    const CONTATO_UPDATE = '/contato';

    //Remove
    const CONTATO_DELETE = '/contato';
}
