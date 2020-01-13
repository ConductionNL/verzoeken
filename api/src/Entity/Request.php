<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * A request (or verzoek in dutch) to an organization (usually governmental) to do 'something' on behalf of a citizen or another organization
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RequestRepository")
 * @ORM\HasLifecycleCallbacks 
 * @ApiFilter(SearchFilter::class, properties={
 * 		"submitter":"exact",
 * 		"reference":"exact",
 * 		"status":"exact",
 * 		"requestType":"exact",
 * 		"processType":"exact",
 * 		"organizations.rsin": "exact",
 * 		"organizations.status": "exact",
 * 		"submitters.organization": "exact", 
 * 		"submitters.person": "exact", 
 * 		"submitters.contact": "exact", 
 * 		"requestCases.request_case": "exact",
 * })
 * @ApiFilter(DateFilter::class, properties={
 * 		"createdAt",
 * 		"submittedAt",
 * })
 * @ApiFilter(OrderFilter::class, properties={
 * 		"submitter",
 * 		"reference",
 * 		"status",
 * 		"requestType",
 * 		"processType",
 * 		"organizations.rsin",
 * 		"organizations.status",
 * 		"submitters.organization", 
 * 		"submitters.person", 
 * 		"submitters.contact", 
 * 		"requestCases.request_case",
 * 		"archive.nomination",
 * 		"archive.status",
 * 		"createdAt",
 * 		"submittedAt",
 *
 * })

 */
class Request
{
	/**
	 * @var \Ramsey\Uuid\UuidInterface $id The UUID identifier of this resource
	 * @example e2984465-190a-4562-829e-a8cca81aa35d
	 *
	 * @ApiProperty(
	 * 	   identifier=true,
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The UUID identifier of this resource",
	 *             "type"="string",
	 *             "format"="uuid",
	 *             "example"="e2984465-190a-4562-829e-a8cca81aa35d"
	 *         }
	 *     }
	 * )
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
	 * @var string $reference The human readable reference of this request, build as {gemeentecode}-{year}-{referenceId}. Where gemeentecode is a four digit number for gemeenten and a four letter abriviation for other organizations 
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The human readable reference of this request",
	 *             "type"="string",
	 *             "example"="6666-2019-0000000012",
	 *             "maxLength"="255"
	 *         }
	 *     }
	 * )	 
	 *
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * @Groups({"read"})
	 * @ORM\Column(type="string", length=255, nullable=true) //, unique=true
	 */
	private $reference;
	
	/**
	 * @param string $referenceId The autoincrementing id part of the reference, unique on an organization-year-id basis
	 *	 
	 * @Assert\Positive
	 * @Assert\Length(
	 *      max = 11
	 * )
	 * @ORM\Column(type="integer", length=11, nullable=true)
	 */
	private $referenceId;
	
	/**
	 * @var string $status The status of this request. e.g submitted
	 * @example incomplete
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The status of this request. Where *incomplete* is unfinished request, *complete* means that a request has been posted by the submitter, *submitted* means that an organization has started handling the request and *processed* means that any or all cases attached to a request have been handled ",
	 *             "type"="string",
	 *             "example"="incomplete",
	 *             "maxLength"="255",
	 *             "enum"={"incomplete", "complete", "submitted", "processed","cancelled"},
	 *             "default"="incomplete"
	 *         }
	 *     }
	 * )	 
	 *
     * @Assert\Choice({"incomplete", "complete", "submitted", "processed","cancelled"})
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * 
	 * @Groups({"read","write"})
	 * @ORM\Column(type="string", length=255)
	 */
	private $status = "incomplete";
	
	/**
	 * @var string $requestType The type of request against which this request should be validated
	 * @example http://vtc.zaakonline.nl/9bd169ef-bc8c-4422-86ce-a0e7679ab67a
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The type of request against which this request should be validated",
	 *             "type"="string",
	 *             "format"="uri",
	 *             "example"="http://vtc.zaakonline.nl/9bd169ef-bc8c-4422-86ce-a0e7679ab67a",
	 *             "maxLength"="255",
	 *             "required"=true
	 *         }
	 *     }
	 * )
	 *
	 * @Assert\NotNull
	 * @Assert\Url
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * @Groups({"read","write"})
	 * @ORM\Column(type="string", length=255)
	 */
	private $requestType;
	
	/**
	 * @var string $targetOrganization The RSIN of the organization that should handle this request
	 * @example 002851234
	 * @deprecated
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The RSIN of the organization that should handle this request",
	 *             "type"="string",
	 *             "example"="002851234",
	 *              "maxLength"="255",
	 *             "deprecated"=true
	 *         }
	 *     }
	 * )
	 *
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $targetOrganization;
	
	/**
	 * @var string $submitter The BSN (if its a person) or RSIN (if its an organization) that is the primary submiter of this request
	 * @example 002851234
	 * @deprecated
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The BSN (if its a person) or RSIN (if its an organization) that is the primary submiter of this request",
	 *             "type"="string",
	 *             "example"="002851234",
	 *             "maxLength"="255",
	 *             "deprecated"=true
	 *         }
	 *     }
	 * )
	 *
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $submitter;
	
	/**
	 * @var string $submitters The submitters of this request
	 * 
     * @MaxDepth(1)
	 * @Groups({"read", "write"})
	 * @ORM\OneToMany(targetEntity="App\Entity\Submitter", mappedBy="request", orphanRemoval=true, fetch="EAGER", cascade={"persist"})
	 */
	private $submitters;
	
	/**
	 * @var boolean $submitterPerson True if the submitter is a person
	 * @example true
	 * @deprecated
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "True if the submitter is a person",
	 *             "type"="boolean",
	 *             "example"=true,
	 *             "default"=true,
	 *             "deprecated"=true
	 *         }
	 *     }
	 * )
	 *
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $submitterPerson = true;
	
	/**
	 * @var array $properties The actual properties of the request, as described by the request type in the [vtc](http://vrc.zaakonline.nl/).
	 * @example {}
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The actual properties of the request, as described by the request type in the [vtc](http://vrc.zaakonline.nl/)",
	 *             "type"="array",
	 *             "format"="json",
	 *             "example"={}
	 *         }
	 *     }
	 * )
	 *
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="json_array")
	 */
	private $properties;
		
	/**
	 * @var string $processType The processType that made this request
	 * @example http://ptc.zaakonline.nl/9bd169ef-bc8c-4422-86ce-a0e7679ab67a
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The processType that made this request",
	 *             "type"="string",
	 *             "format"="url",
	 *             "example"="http://ptc.zaakonline.nl/9bd169ef-bc8c-4422-86ce-a0e7679ab67a",
	 *             "maxLength"="255"
	 *         }
	 *     }
	 * )
	 *
	 * @Assert\Url
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * @Groups({"read","write"})
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $processType;
	
	/**
	 * @var Datetime $createdAt The moment this request was created by the submitter 
	 * 
	 * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $createdAt;
	
	/**
	 * @var Datetime $submittedAt The moment this request was submitted by the submitter
	 * 
	 * @Groups({"read"})
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $submittedAt;

    /**
	 * @var ArrayCollection $organizations Organizations that are handling this request, the use of this under discussion since it would mean giving an organization all request info there where it might need less. Forcing AVG issues upon the parties. The sollotion for this might be found in goal binding.
	 * 
     * @MaxDepth(1)
	 * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity="App\Entity\Organization", mappedBy="request", orphanRemoval=true, fetch="EAGER", cascade={"persist"})
     */
    private $organizations;

    /**
	 * @var ArrayCollection $requestCases Any or all cases currently attached to this request
	 * 
     * @MaxDepth(1)
	 * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity="App\Entity\RequestCase", mappedBy="request", orphanRemoval=true, fetch="EAGER", cascade={"persist"})
     */
    private $requestCases;

    /**
	 * @var Request $parent The request that this request was based on
	 * 
     * @MaxDepth(1)
	 * @Groups({"read","write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Request", inversedBy="children")
     */
    private $parent;

    /**
	 * @var ArrayCollection $children The requests that are bassed on this request
	 * 
     * @MaxDepth(1)
	 * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity="App\Entity\Request", mappedBy="parent")
     */
    private $children;

    /**
	 * @var boolean $confidential Whether or not this request is considered confidential 
	 * @example false
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "Whether or not this request is considered confidential ",
	 *             "type"="boolean",
	 *             "example"=false,
	 *             "default"= false
	 *         }
	 *     }
	 * )
	 * 
	 * @Groups({"read","write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $confidential;

    /**
	 * @var Archive $archive Archivation rules of this resource
	 * 
     * @MaxDepth(1)
	 * @Groups({"read","write"})
     * @ORM\OneToOne(targetEntity="App\Entity\Archive", cascade={"persist", "remove"})
     */
    private $archive;

    /**
	 * @var string $currentStage The current stage of the client journey in this proces
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The current stage of the client journey in this proces",
	 *             "type"="string",
	 *             "example"="getuigen",
	 *             "maxLength"="255"
	 *         }
	 *     }
	 * )	 
	 *
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currentStage;

    public function __construct()
    {
        $this->submitters = new ArrayCollection();
        $this->organizations = new ArrayCollection();
        $this->requestCases = new ArrayCollection();
        $this->children = new ArrayCollection();
    }
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id): self
	{
		$this->id = $id;
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
	
	public function getTargetOrganization(): ?string
	{
		return $this->targetOrganization;
	}
	
	public function setTargetOrganization(string $targetOrganization): self
	{
		$this->targetOrganization= $targetOrganization;
		return $this;
	}
	
	public function getSubmitter(): ?string
	{
		return $this->submitter;
	}
	
	public function setSubmitter(string $submitter): self
	{
		$this->submitter = $submitter;
                                                                                                                                 		
		return $this;
	}
	
	public function getSubmitterPerson(): ?bool
	{
		return $this->submitterPerson;
	}
	
	public function setSubmitterPerson(bool $submitterPerson): self
	{
		$this->submitterPerson = $submitterPerson;
		
		return $this;
	}
	
	public function getProperties()
	{
		return $this->properties;
	}
	
	public function setProperties($properties): self
	{
		$this->properties = $properties;
		return $this;
	}
	
	public function getProcess(): ?string
	{
		return $this->process;
	}
	
	public function setProcess(?string $process): self
	{
		$this->process = $process;
		
		return $this;
	}
	
	public function getCreatedAt(): ?\DateTimeInterface
	{
		return $this->createdAt;
	}
	
	public function setCreatedAt(\DateTimeInterface $createdAt): self
	{
		$this->createdAt = $createdAt;
		
		return $this;
	}
	
	public function getSubmittedAt(): ?\DateTimeInterface
	{
		return $this->submittedAt;
	}
	
	public function setSubmittedAt(\DateTimeInterface $submittedAt): self
	{
		$this->submittedAt = $submittedAt;
		
		return $this;
	}

    /**
     * @return Collection|Submitter[]
     */
    public function getSubmitters(): Collection
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

    /**
     * @return Collection|Organization[]
     */
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(Organization $organization): self
    {
        if (!$this->organizations->contains($organization)) {
            $this->organizations[] = $organization;
            $organization->setRequest($this);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): self
    {
    	if ($this->organizations->contains($organization)) {
    		$this->organizations->removeElement($organization);
            // set the owning side to null (unless already changed)
    		if ($organization->getRequest() === $this) {
    			$organization->setRequest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RequestCase[]
     */
    public function getRequestCases(): Collection
    {
    	return $this->requestCases;
    }

    public function addRequestCase(RequestCase $requestCase): self
    {
    	if (!$this->requestCases->contains($requestCase)) {
    		$this->requestCases[] = $requestCase;
    		$requestCase->setRequest($this);
        }

        return $this;
    }

    public function removeOpenCase(RequestCase $requestCase): self
    {
    	if ($this->requestCases->contains($requestCase)) {
    		$this->requestCases->removeElement($requestCase);
            // set the owning side to null (unless already changed)
    		if ($requestCase->getRequest() === $this) {
    			$requestCase->setRequest(null);
            }
        }

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

    public function getArchive(): ?Archive
    {
        return $this->archive;
    }

    public function setArchive(?Archive $archive): self
    {
        $this->archive = $archive;

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
}
