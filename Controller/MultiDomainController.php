<?php

namespace MauticPlugin\MauticMultiDomainBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractStandardFormController;
use MauticPlugin\MauticMultiDomainBundle\Model\MultidomainModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class MultiDomainController extends AbstractStandardFormController
{
    protected function getModelName(): string
    {
        return 'multidomain';
    }

    protected function getDefaultOrderColumn(): string
    {
        return 'domain';
    }

    protected function getTemplateBase(): string
    {
        return '@MauticCore/FormTheme';
    }

    public function indexAction(Request $request, $page = 1): Response | JsonResponse
    {
        $model = $this->getModel('multidomain.multidomain');
        \assert($model instanceof MultidomainModel);


        return parent::indexStandard($request, $page);
    }

    /**
     * Generates new form and processes post data.
     *
     * @return JsonResponse|Response
     */
    public function newAction(Request $request): Response
    {
        return parent::newStandard($request);
    }

    /**
     * Generates edit form and processes post data.
     *
     * @param int  $objectId
     * @param bool $ignorePost
     *
     * @return JsonResponse|Response
     */
    public function editAction(Request $request, $objectId, $ignorePost = false): JsonResponse|Response
    {
        return parent::editStandard($request, $objectId, $ignorePost);
    }

    /**
     * Displays details on a spintax.
     *
     * @param $objectId
     *
     * @return array|JsonResponse|RedirectResponse|Response
     */
    public function viewAction(Request $request, $objectId): JsonResponse|array|RedirectResponse|Response
    {
        return parent::viewStandard($request, $objectId, 'maultidomain', 'plugin.maultidomain');
    }

    /**
     * Clone an entity.
     *
     * @param int $objectId
     *
     * @return JsonResponse|RedirectResponse|Response
     */
    public function cloneAction(Request $request, $objectId): RedirectResponse|JsonResponse|Response
    {
        return parent::cloneStandard($request, $objectId);
    }

    /**
     * Deletes the entity.
     *
     * @param int $objectId
     *
     * @return JsonResponse|RedirectResponse
     */
    public function deleteAction(Request $request, $objectId): RedirectResponse|JsonResponse
    {
        return parent::deleteStandard($request, $objectId);
    }

    /**
     * Deletes a group of entities.
     *
     * @return JsonResponse|RedirectResponse
     */
    public function batchDeleteAction(Request $request): RedirectResponse|JsonResponse
    {
        return parent::batchDeleteStandard($request);
    }
}