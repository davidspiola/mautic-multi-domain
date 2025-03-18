<?php

return [
    'name'        => 'Multidomain',
    'description' => 'User can add multiple tracking domains for emails.',
    'author'      => 'Abdullah Kiser / Friendly Automate',
    'version'     => '1.0.1',
    'routes' => [
        'main' => [
            'mautic_multidomain_index' => [
                'path'       => '/multidomain/{page}',
                'controller' => 'MauticPlugin\MauticMultiDomainBundle\Controller\MultiDomainController::indexAction',
                'method'     => 'GET|POST',
                'defaults'   => ['page' => 1],
            ],
            'mautic_multidomain_new' => [
                'path'       => '/multidomain/new',
                'controller' => 'MauticPlugin\MauticMultiDomainBundle\Controller\MultiDomainController::newAction',
            ],
            'mautic_multidomain_edit' => [
                'path'       => '/multidomain/edit/{objectId}',
                'controller' => 'MauticPlugin\MauticMultiDomainBundle\Controller\MultiDomainController::editAction',
            ],
            'mautic_multidomain_action' => [
                'path'       => '/multidomain/{objectAction}/{objectId}',
                'controller' => 'MauticMultiDomainBundle:MultiDomain:executeAction',
            ],
        ],
        'api' => [
            'mautic_api_multidomainstandard' => [
                'standard_entity' => true,
                'name' => 'multidomain',
                'path' => '/multidomain',
                'controller' => 'MauticMultiDomainBundle:Api\MultiDomainApi',
            ],
        ],
    ],
    'menu' => [
        'main' => [
            'mautic.multidomain.menu' => [
                'route'    => 'mautic_multidomain_index',
                'priority' => 10,
                'iconClass' => 'fa-globe',
            ],
        ],
    ],
    'services' => [
        'permissions' => [
            'multiDomain.permissions' => [
                'class' => \MauticPlugin\MauticMultiDomainBundle\Permissions\Security\MultiDomainPermissions::class,
            ],
        ],
        'models' => [
            'mautic.multidomain.model.multidomain' => [
                'class'     => \MauticPlugin\MauticMultiDomainBundle\Model\MultidomainModel::class,
                'arguments' => [
                    'mautic.form.model.form',
                    'mautic.page.model.trackable',
                    'mautic.helper.templating',
                    'event_dispatcher',
                    'mautic.lead.model.field',
                    'mautic.tracker.contact',
                    'doctrine.orm.entity_manager',
                ],
                //'public' => true,
                'alias' => 'model.multidomain.multidomain'
            ],
        ],
        'events' => [
            'mautic.multidomain.subscriber.multidomain' => [
                'class'     => \MauticPlugin\MauticMultiDomainBundle\EventListener\MultidomianSubscriber::class,
                'arguments' => [
                    'router',
                    'mautic.helper.ip_lookup',
                    'mautic.core.model.auditlog',
                    'mautic.page.model.trackable',
                    'mautic.page.helper.token',
                    'mautic.asset.helper.token',
                    'mautic.multidomain.model.multidomain',
                    'request_stack',
                ],
            ],
            'mautic.multidomain.subscriber.emailbuilder' => [
                'class'     => \MauticPlugin\MauticMultiDomainBundle\EventListener\BuilderSubscriber::class,
                'arguments' => [
                    'mautic.helper.core_parameters',
                    'mautic.email.model.email',
                    'mautic.page.model.trackable',
                    'mautic.page.model.redirect',
                    'translator',
                    'doctrine.orm.entity_manager',
                    'mautic.multidomain.model.multidomain',
                    'router',
                ],
            ],
            'mautic.multidomain.subscriber.buildjssubscriber' => [
                'class'     => \MauticPlugin\MauticMultiDomainBundle\EventListener\BuildJsSubscriber::class,
                'arguments' => [
                    'templating.helper.assets',
                    'request_stack',
                    'router',
                ],
            ],
        ],
    ],
//    'services' => [
//        'forms' => [
//            'mautic.form.type.multidomain' => [
//                'class' => \MauticPlugin\MauticMultiDomainBundle\Form\Type\MultidomainType::class,
//            ],
//        ],
//        'models' => [
//            'mautic.multidomain.model.multidomain' => [
//                'class'     => \MauticPlugin\MauticMultiDomainBundle\Model\MultidomainModel::class,
//                'arguments' => [
//                    'mautic.form.model.form',
//                    'mautic.page.model.trackable',
//                    'event_dispatcher',
//                    'mautic.lead.model.field',
//                    'mautic.tracker.contact',
//                    'doctrine.orm.entity_manager',
//                ],
//                //'public' => true,
//                'alias' => 'model.multidomain.multidomain'
//            ],
//        ],
//        'events' => [
//            'mautic.multidomain.subscriber.multidomain' => [
//                'class'     => \MauticPlugin\MauticMultiDomainBundle\EventListener\MultidomianSubscriber::class,
//                'arguments' => [
//                    'router',
//                    'mautic.helper.ip_lookup',
//                    'mautic.core.model.auditlog',
//                    'mautic.page.model.trackable',
//                    'mautic.page.helper.token',
//                    'mautic.asset.helper.token',
//                    'mautic.multidomain.model.multidomain',
//                    'request_stack',
//                ],
//            ],
//            'mautic.multidomain.subscriber.emailbuilder' => [
//                'class'     => \MauticPlugin\MauticMultiDomainBundle\EventListener\BuilderSubscriber::class,
//                'arguments' => [
//                    'mautic.helper.core_parameters',
//                    'mautic.email.model.email',
//                    'mautic.page.model.trackable',
//                    'mautic.page.model.redirect',
//                    'translator',
//                    'doctrine.orm.entity_manager',
//                    'mautic.multidomain.model.multidomain',
//                    'router',
//                ],
//            ],
//            'mautic.multidomain.subscriber.buildjssubscriber' => [
//                'class'     => \MauticPlugin\MauticMultiDomainBundle\EventListener\BuildJsSubscriber::class,
//                'arguments' => [
//                    'assets.package',
//                    'request_stack',
//                    'router',
//                ],
//            ],
//        ],
//    ],
];
