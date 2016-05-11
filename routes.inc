<?php
/**
 * @copyright 2015-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
$rf = new \Aura\Router\RouterFactory(BASE_URI);
$ROUTES = $rf->newInstance();

$ROUTES->add('home', '/')->setValues(['controller'=>'index', 'action'=>'index']);

$ROUTES->add('m', '/m/{year}/{month}/{day}/{derivative}/{media_id}')
       ->addTokens([
            'year'       => '\d{4}',
            'month'      => '\d{1,2}',
            'day'        => '\d{1,2}',
            'derivative' => '\d+',
            'media_id'   => '[0-9a-f]{13}'
        ])
       ->addValues(['controller'=>'media', 'action'=>'derivative']);


$ROUTES->attach('login', '/login', function ($r) {
    $r->setValues(['controller'=>'login']);

    $r->add('index',  '')       ->addValues(['action'=>'index' ]);
    $r->add('login',  '/login') ->addValues(['action'=>'login' ]);
    $r->add('logout', '/logout')->addValues(['action'=>'logout']);
});

$ROUTES->attach('people', '/people', function ($r) {
    $r->setTokens(['id'=>'[0-9]+']);
    $r->setValues(['controller'=>'people']);

    $r->add('index',  '')            ->addValues(['action'=>'index' ]);
    $r->add('view',   '/{id}')       ->addValues(['action'=>'view'  ]);
    $r->add('update', '/update{/id}')->addValues(['action'=>'update']);
});

$ROUTES->attach('users', '/users', function ($r) {
    $r->setTokens(['id'=>'[a-z0-9]+']);
    $r->setValues(['controller'=>'users']);

    $r->add('index',  '')             ->addValues(['action'=>'index' ]);
    $r->add('update', '/update{/id}') ->addValues(['action'=>'update']);
    $r->add('delete', '/delete/{id}') ->addValues(['action'=>'delete']);
});

$ROUTES->attach('departments', '/departments', function ($r) {
    $r->setTokens(['id'=>'[0-9]+']);
    $r->setValues(['controller'=>'departments']);

    $r->add('index',  '')            ->addValues(['action'=>'index' ]);
    $r->add('view',   '/{id}')       ->addValues(['action'=>'view'  ]);
    $r->add('update', '/update{/id}')->addValues(['action'=>'update']);
});

$ROUTES->attach('derivatives', '/derivatives', function ($r) {
    $r->setTokens(['id'=>'[0-9]+']);
    $r->setValues(['controller'=>'derivatives']);

    $r->add('index',  '')            ->addValues(['action'=>'index' ]);
    $r->add('update', '/update{/id}')->addValues(['action'=>'update']);
});

$ROUTES->attach('media', '/media', function ($r) {
    $r->setTokens(['id'=>'[0-9]+']);
    $r->setValues(['controller'=>'media']);

    $r->add('index',  '')            ->addValues(['action'=>'index' ]);
    $r->add('view',   '/{id}')       ->addValues(['action'=>'view'  ]);
    $r->add('update', '/update{/id}')->addValues(['action'=>'update']);
    $r->add('delete', '/delete/{id}')->addValues(['action'=>'delete']);
    $r->add('saveDerivative', '/saveDerivative')->addValues(['action'=>'saveDerivative']);
});

$NAV1 = [
    ['msgid'=>'home',                  'uri'=>$ROUTES->generate('home')],
    ['msgid'=>['person', 'people', 2], 'uri'=>$ROUTES->generate('people.index')]
];