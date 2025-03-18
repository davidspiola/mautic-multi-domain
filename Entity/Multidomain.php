<?php

namespace MauticPlugin\MauticMultiDomainBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\FormEntity;

/**
 * Class Multidomain
 * @package MauticPlugin\MauticMultiDomainBundle\Entity
 */
class Multidomain extends FormEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * 
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $name;

//    public static function loadValidatorMetadata(ClassMetadata $metadata)
//    {
//
//        $metadata->addConstraint(new UniqueEntity([
//            'fields' => 'email',
//        ]));
//        $metadata->addPropertyConstraint(
//            'email',
//            new Assert\NotBlank(
//                [
//                    'message' => 'mautic.multidomain.email.required',
//                ]
//            )
//        );
//
//        $metadata->addPropertyConstraint(
//            'email',
//            new Assert\Email(
//                [
//                    'message' => 'mautic.multidomain.email.invalid',
//                ]
//            )
//        );
//
//        $metadata->addPropertyConstraint(
//            'domain',
//            new Assert\NotBlank(
//                ['message' => 'mautic.multidomain.domain.required']
//            )
//        );
//
//        $metadata->addPropertyConstraint(
//            'domain',
//            new Assert\Url(
//                ['message' => 'mautic.multidomain.domain.invalid']
//            )
//        );
//    }

    public static function loadMetadata(\Doctrine\ORM\Mapping\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('multi_domain')
            ->setEmbeddable()
            ->setCustomRepositoryClass(MultidomainRepository::class);
        $builder->addId();
        //$builder->addField('name', Types::STRING);
        $builder->addField('email', Types::STRING);
        $builder->addField('domain', Types::STRING);


    }

    /**
     * Prepares the metadata for API usage.
     *
     * @param $metadata
     */
    public static function loadApiMetadata(ApiMetadataDriver $metadata): void
    {
        $metadata->setGroupPrefix('multidomain')
            ->addListProperties(
                [
                    'id',
                    'email',
                    'domain',
                ]
            )
            ->build();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    public function getName(): ?string
    {
        return $this->domain;
    }

    public function setName(string $name): void
    {
        $this->domain = $name;
    }

    public function getDescription(): String
    {
        return "";
    }

    public function getCategory(): String
    {
        return "";
    }

}