<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A request (or verzoek in dutch) to an organization (usually governmental) to do 'something' on behalf of a citizen or another organization.
 *
 * @ApiResource(
 *     attributes={"order"={"dateCreated": "ASC"}},
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true},
 *     itemOperations={
 *          "get",
 *          "put",
 *          "delete",
 *          "get_change_logs"={
 *              "path"="/requests/{id}/change_log",
 *              "method"="get",
 *              "swagger_context" = {
 *                  "summary"="Changelogs",
 *                  "description"="Gets al the change logs for this resource"
 *              }
 *          },
 *          "get_audit_trail"={
 *              "path"="/requests/{id}/audit_trail",
 *              "method"="get",
 *              "swagger_context" = {
 *                  "summary"="Audittrail",
 *                  "description"="Gets the audit trail for this resource"
 *              }
 *          }
 *     },
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RequestRepository")
 * @Gedmo\Loggable(logEntryClass="Conduction\CommonGroundBundle\Entity\ChangeLog")
 * @ORM\HasLifecycleCallbacks
 *
 * @ApiFilter(BooleanFilter::class)
 * @ApiFilter(OrderFilter::class)
 * @ApiFilter(DateFilter::class, strategy=DateFilter::EXCLUDE_NULL)
 * @ApiFilter(SearchFilter::class, properties={
 *     "submitters.brp": "exact",
 *     "submitters.assent": "exact",
 *     "submitters.person": "exact",
 *     "submitters.organization": "partial",
 *     "organization": "exact",
 *     "order": "exact",
 *     "reources": "exact",
 *     "reference": "exact",
 *     "status": "exact",
 *     "requestType": "partial",
 *     "processType": "partial",
 *     "currentStage": "exact",
 *     "roles.rolType": "exact",
 *     "roles.request": "exact",
 *     "roles.participant": "exact",
 *     "roles.participantType": "exact"})
 */
class Request
{
    /**
     * @var UuidInterface The UUID identifier of this resource
     *
     * @example e2984465-190a-4562-829e-a8cca81aa35d
     *
     * @Assert\Uuid
     * @Groups({"read"})
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string A specific commonground organisation that is being reviewed, e.g a single product
     *
     * @example https://wrc.zaakonline.nl/organisations/16353702-4614-42ff-92af-7dd11c8eef9f
     *
     * @Gedmo\Versioned
     * @Assert\NotNull
     * @Assert\Url
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $organization;

    /**
     * @var string The organization on wich this request was created
     *
     * @example https://wrc.zaakonline.nl/organisations/16353702-4614-42ff-92af-7dd11c8eef9f
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255)
     */
    private $initialOrganization;

    /**
     * @var string A specific commonground organisation that is being reviewed, e.g a single product
     *
     * @example https://wrc.zaakonline.nl/organisations/16353702-4614-42ff-92af-7dd11c8eef9f
     *
     * @Gedmo\Versioned
     * @Assert\Url
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true, name="orderUri")
     */
    private $order;

    /**
     * @var string A specific commonground organisation that is being reviewed, e.g a single product
     *
     * @example https://wrc.zaakonline.nl/organisations/16353702-4614-42ff-92af-7dd11c8eef9f
     *
     * @Gedmo\Versioned
     * @Assert\Url
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $event;

    /**
     * @var array The resources that are the subject of this request
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="array", nullable=true)
     */
    private $resources = [];

    /**
     * @var string The human readable reference of this request, build as {gemeentecode}-{year}-{referenceId}. Where gemeentecode is a four digit number for gemeenten and a four letter abriviation for other organizations
     *
     * @example 6666-2019-0000000012
     *
     * @Gedmo\Versioned
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @param string $referenceId The autoincrementing id part of the reference, unique on an organization-year-id basis
     *
     * @Gedmo\Versioned
     * @Assert\Positive
     * @Assert\Length(
     *      max = 11
     * )
     * @ORM\Column(type="integer", length=11, nullable=true) //
     */
    private $referenceId;

    /**
     * @var string The external human readable reference of this request, as set (by example) for an external application that has already profidet the consumer with an reference
     *
     * @example 6666-2019-0000000012
     *
     * @Gedmo\Versioned
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $externalReference;

    /**
     * @var string The current status of this request. Where *incomplete* is unfinished request, *complete* means that a request has been posted by the submitter, *submitted* means the request is submitted to the organizaztion, *inProgress* means the request is in progress by the organization, *processed* means that any or all cases attached to a request have been handled *cancelled* means the request has been cancelled, *retracted* means the person who submitted the request retracted the request and *rejected* means that the request is rejected by the organization.
     *
     * @example incomplete
     *
     * @Gedmo\Versioned
     * @Assert\Choice({"incomplete", "complete", "submitted", "inProgress", "processed", "cancelled", "retracted", "rejected"})
     * @Assert\Length(
     *      max = 255
     * )
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255)
     */
    private $status = 'incomplete';

    /**
     * @var string The type of request against which this request should be validated
     *
     * @example http://vtc.zaakonline.nl/9bd169ef-bc8c-4422-86ce-a0e7679ab67a
     *
     * @Gedmo\Versioned
     * @Assert\Url
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $requestType;

    /**
     * @var string The processType that made this request
     *
     * @example http://ptc.zaakonline.nl/9bd169ef-bc8c-4422-86ce-a0e7679ab67a
     *
     * @Gedmo\Versioned
     * @Assert\Url
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $processType;

    /**
     * @var array The actual properties of the request, as described by the request type in the [vtc](http://vrc.zaakonline.nl/).
     *
     * @example {}
     *
     * @Gedmo\Versioned
     * @Groups({"read", "write"})
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $properties;

    /**
     * @var array An array of cases tied to this request
     *
     * @example
     *
     * @Gedmo\Versioned
     * @Groups({"read", "write"})
     * @ORM\Column(type="array", nullable=true)
     */
    private $cases = [];

    /**
     * @var array An array of processes tied to this request
     *
     * @example
     *
     * @Gedmo\Versioned
     * @Groups({"read", "write"})
     * @ORM\Column(type="array", nullable=true)
     */
    private $processes = [];

    /**
     * @var Request The request that this request was based on
     *
     * @Gedmo\Versioned
     * @MaxDepth(1)
     * @Groups({"read","write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Request", inversedBy="children")
     */
    private $parent;

    /**
     * @var ArrayCollection The requests that are bassed on this request
     *
     * @MaxDepth(1)
     * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity="App\Entity\Request", mappedBy="parent")
     */
    private $children;

    /**
     * @var bool Whether or not this request is considered confidential
     *
     * @example false
     *
     * @Gedmo\Versioned
     * @Groups({"read","write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $confidential;

    /**
     * @var string The current stage of the client journey in this proces
     *
     * @example getuigen
     *
     * @Gedmo\Versioned
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currentStage;

    /**
     * @var ArrayCollection An array of Submitters of the that submitted this request
     *
     * @Assert\NotNull
     * @Assert\Valid
     * @MaxDepth(1)
     * @Groups({"read", "write"})
     * @ORM\OneToMany(targetEntity="App\Entity\Submitter", mappedBy="request", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $submitters;

    /**
     * @var Datetime The moment this request was submitted by the submitter
     *
     * @Gedmo\Versioned
     * @Groups({"read"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateSubmitted;

    /**
     * @var Datetime The moment this request was created
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var Datetime The moment this request last Modified
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->submitters = new ArrayCollection();
    }

    /**
     *  @ORM\PrePersist
     *  @ORM\PreUpdate
     */
    public function prePersist()
    {
        if (!$this->getInitialOrganization()) {
            $this->setInitialOrganization($this->getOrganization());
        }
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    public function setOrganization(string $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getInitialOrganization(): ?string
    {
        return $this->initialOrganization;
    }

    public function setInitialOrganization(string $initialOrganization): self
    {
        $this->initialOrganization = $initialOrganization;

        return $this;
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function setOrder(?string $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function setEvent(?string $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getResources(): ?array
    {
        return $this->resources;
    }

    public function setResources(array $resources): self
    {
        $this->resources = $resources;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getReferenceId(): ?int
    {
        return $this->reference;
    }

    public function setReferenceId(int $referenceId): self
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    public function getExternalReference(): ?string
    {
        return $this->externalReference;
    }

    public function setEternalReference(string $externalReference): self
    {
        $this->externalReference = $externalReference;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRequestType(): ?string
    {
        return $this->requestType;
    }

    public function setRequestType(string $requestType): self
    {
        $this->requestType = $requestType;

        return $this;
    }

    public function getProcessType(): ?string
    {
        return $this->processType;
    }

    public function setProcessType(string $processType): self
    {
        $this->processType = $processType;

        return $this;
    }

    // tot hier nagelopen

    public function getProperties()
    {
        return $this->properties;
    }

    public function setProperties($properties): self
    {
        $this->properties = $properties;

        return $this;
    }

    public function getCases(): ?array
    {
        return $this->cases;
    }

    public function setCases(?array $cases): self
    {
        $this->cases = $cases;

        return $this;
    }

    public function getProcesses(): ?array
    {
        return $this->processes;
    }

    public function setProcesses(?array $processes): self
    {
        $this->processes = $processes;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getConfidential(): ?bool
    {
        return $this->confidential;
    }

    public function setConfidential(?bool $confidential): self
    {
        $this->confidential = $confidential;

        return $this;
    }

    public function getCurrentStage(): ?string
    {
        return $this->currentStage;
    }

    public function setCurrentStage(?string $currentStage): self
    {
        $this->currentStage = $currentStage;

        return $this;
    }

    public function getDateSubmitted(): ?\DateTimeInterface
    {
        return $this->dateSubmitted;
    }

    public function setDateSubmitted(\DateTimeInterface $dateSubmitted): self
    {
        $this->dateSubmitted = $dateSubmitted;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(\DateTimeInterface $dateModified): self
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    public function getSubmitters(): ?Collection
    {
        return $this->submitters;
    }

    public function addSubmitter(Submitter $submitter): self
    {
        if (!$this->submitters->contains($submitter)) {
            $this->submitters[] = $submitter;
            $submitter->setRequest($this);
        }

        return $this;
    }

    public function removeSubmitter(Submitter $submitter): self
    {
        if ($this->submitters->contains($submitter)) {
            $this->submitters->removeElement($submitter);
            // set the owning side to null (unless already changed)
            if ($submitter->getRequest() === $this) {
                $submitter->setRequest(null);
            }
        }

        return $this;
    }
}
