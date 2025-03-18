<?php

/*
 * @copyright   2016 Mautic, Inc. All rights reserved
 * @author      Mautic, Inc
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticMultiDomainBundle\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Mautic\CoreBundle\Event\TokenReplacementEvent;
use Mautic\CoreBundle\Helper\Chart\ChartQuery;
use Mautic\CoreBundle\Helper\Chart\LineChart;

use Mautic\CoreBundle\Helper\CoreParametersHelper;
use Mautic\CoreBundle\Helper\UserHelper;
use Mautic\CoreBundle\Model\FormModel;
use Mautic\CoreBundle\Security\Permissions\CorePermissions;
use Mautic\CoreBundle\Translation\Translator;
use Mautic\EmailBundle\Helper\MailHelper;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\LeadBundle\Model\FieldModel;
use Mautic\LeadBundle\Tracker\ContactTracker;
use Mautic\PageBundle\Model\TrackableModel;
use Mautic\UserBundle\Model\UserToken\UserTokenServiceInterface;
use MauticPlugin\MauticMultiDomainBundle\Entity\Multidomain;
use MauticPlugin\MauticMultiDomainBundle\Entity\MultidomainRepository;
use MauticPlugin\MauticMultiDomainBundle\Form\Type\MultidomainType;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Form\FormInterface;
use MauticPlugin\MauticMultiDomainBundle\Event\MultidomainEvent;

class MultidomainModel extends FormModel
{

    /**
     * @var \Mautic\FormBundle\Model\FormModel
     */
    protected $formModel;

    /**
     * @var TrackableModel
     */
    protected $trackableModel;

    /**
     * @var TemplatingHelper
     */
    protected $templating;

    /**
     * @var FieldModel
     */
    protected $leadFieldModel;

    /**
     * @var ContactTracker
     */
    protected $contactTracker;

    /**
     * 
     * @var EntityManager $entityManager
     */
    private static $entityManager;


    public function getModelName(): string
    {
        return 'multidomain';
    }

    /**
     * @return string
     */
    public function getPermissionBase()
    {
        return 'multidomain:items';
    }

    public function getRepository(): MultidomainRepository
    {
        return $this->em->getRepository(Multidomain::class);
    }

    /**
     * {@inheritdoc}
     *
     * @param object                              $entity
     * @param \Symfony\Component\Form\FormFactory $formFactory
     * @param null                                $action
     * @param array                               $options
     *
     * @throws NotFoundHttpException
     */
    public function createForm($entity, $formFactory, $action = null, $options = []): FormInterface
    {
        if (!$entity instanceof Multidomain) {
            throw new MethodNotAllowedHttpException(['Multidomain']);
        }

        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create(MultidomainType::class, $entity, $options);
    }

    /**
     * {@inheritdoc}
     *
     * @param null $id
     *
     * @return Multidomain
     */
    public function getEntity($id = null): ?object
    {
        if (null === $id) {
            return new Multidomain();
        }

        return parent::getEntity($id);
    }

    /**
     * {@inheritdoc}
     *
     * @param Multidomain      $entity
     * @param bool|false $unlock
     */
    public function saveEntity($entity, $unlock = true): void
    {
        parent::saveEntity($entity, $unlock);
        $this->getRepository()->saveEntity($entity);
    }

    public function generateMessageId(Multidomain $multidomain) {
        $url = $multidomain->getDomain();
        $parts = parse_url($url);
        if (!isset($parts['host'])) {
            throw new \Exception("InvalidDomainError");
        }

        $messageIdSuffix = '@' . $parts['host'];
        return bin2hex(random_bytes(16)).$messageIdSuffix;
    }

    
    /**
     * Get whether the color is light or dark.
     *
     * @param $hex
     * @param $level
     *
     * @return bool
     */
    public static function isLightColor($hex, $level = 200)
    {
        $hex = str_replace('#', '', $hex);
        $r   = hexdec(substr($hex, 0, 2));
        $g   = hexdec(substr($hex, 2, 2));
        $b   = hexdec(substr($hex, 4, 2));

        $compareWith = ((($r * 299) + ($g * 587) + ($b * 114)) / 1000);

        return $compareWith >= $level;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool|MultidomainEvent|void
     *
     * @throws MethodNotAllowedHttpException
     */
    protected function dispatchEvent($action, &$entity, $isNew = false, Event $event = null): ?MultidomainEvent
    {
        if (!$entity instanceof Multidomain) {
            throw new MethodNotAllowedHttpException(['Multidomain']);
        }

        switch ($action) {
            case 'pre_save':
                $name = 'mautic.multidomain_pre_save';
                break;
            case 'post_save':
                $name = 'mautic.multidomain_post_save';
                break;
            case 'pre_delete':
                $name = 'mautic.multidomain_pre_delete';
                break;
            case 'post_delete':
                $name = 'mautic.multidomain_post_delete';
                break;
            default:
                return null;
        }

        if ($this->dispatcher->hasListeners($name)) {
            if (empty($event)) {
                $event = new MultidomainEvent($entity, $isNew);
            }

            $this->dispatcher->dispatch($event, $name);

            return $event;
        } else {
            return null;
        }

    }

    // Get path of the config.php file.
    public function getConfiArray()
    {
        return include dirname(__DIR__).'/Config/config.php';
    }

}
