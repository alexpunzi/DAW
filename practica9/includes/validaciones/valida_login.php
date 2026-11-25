<?php

function validarLogin($email, $password): ?string
{
    if (trim((string)$email) === '' || trim((string)$password) === '') {
        return 'vacio';
    }

    return null;
}