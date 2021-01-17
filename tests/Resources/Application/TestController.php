<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Resources\Application;

use Novuso\Common\Application\Attribute\Validation;

/**
 * Class TestController
 */
class TestController
{
    #[Validation(
        rules: [
            [
                'field' => 'page',
                'label' => 'page',
                'rules' => 'natural_number'
            ],
            [
                'field' => 'per_page',
                'label' => 'per_page',
                'rules' => 'natural_number'
            ],
            [
                'field' => 'sort',
                'label' => 'sort',
                'rules' => 'in_list[first_name,last_name]'
            ],
            [
                'field' => 'order',
                'label' => 'order',
                'rules' => 'in_list[asc,desc]'
            ]
        ]
    )]
    public function getUsers()
    {
    }

    #[Validation(
        formName: 'create-user',
        rules: [
            [
                'field' => 'first_name',
                'label' => 'First name',
                'rules' => 'required|not_empty|max_length[100]'
            ],
            [
                'field' => 'last_name',
                'label' => 'Last name',
                'rules' => 'required|not_empty|max_length[100]'
            ],
            [
                'field' => 'email_address',
                'label' => 'Email address',
                'rules' => 'required|email|max_length[100]'
            ]
        ]
    )]
    public function createUser()
    {
    }
}
