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
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;


use App\Repository\OrganizationRepository;
use App\Repository\RequestRepository;

/**
 * A request (or verzoek in dutch) to an organization (usually governmental) to do 'something' on behalf of a citizen or another organization
 *
 * @ApiResource(
 *     attributes={"order"={"dateCreated": "ASC"}},
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
 * 		"requestCases.request_case": "exact",
 * })
 * @ApiFilter(DateFilter::class, properties={
 * 		"dateCreated",
 * 		"dateModified",
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
 * 		"dateCreated",
 * 		"dateModified",
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
	 * @var string $resource A specific commonground organisation that is being reviewd, e.g a single product
	 * @example https://wrc.zaakonline.nl/organisations/16353702-4614-42ff-92af-7dd11c8eef9f
	 *
	 * @Assert\NotNull
	 * @Assert\Url
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="string", length=255)
	 */
	private $organization;
	
	/**
	 * @var string $reference The human readable reference of this request, build as {gemeentecode}-{year}-{referenceId}. Where gemeentecode is a four digit number for gemeenten and a four letter abriviation for other organizations 
	 * @example 6666-2019-0000000012
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
	 * @var string $status The curent status of this request. Where *incomplete* is unfinished request, *complete* means that a request has been posted by the submitter, *submitted* means that an organization has started handling the request and *processed* means that any or all cases attached to a request have been handled
	 * @example incomplete
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
	 * @var string $processType The processType that made this request
	 * @example http://ptc.zaakonline.nl/9bd169ef-bc8c-4422-86ce-a0e7679ab67a
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
	 * @var array $submitters An array instemmingen of the people or organizations that submitted this request 
	 * @example 
	 * 
	 * 
	 * @Assert\NotNull
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="array")
	 */
	private $submitters = [];
	
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
	 * @var array $cases An array of cases tied to this request
	 * @example 
	 * 
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="array", nullable=true)
	 */
	private $cases = [];

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
	 * @Groups({"read","write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $confidential;

    /**
	 * @var string $currentStage The current stage of the client journey in this proces
	 * @example getuigen
	 *
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currentStage;
    
    /**
     * @var Datetime $dateSubmitted The moment this request was submitted by the submitter
     *
     * @Groups({"read"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateSubmitted;    
    
    /**
     * @var Datetime $dateCreated The moment this request was created
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;
    
    /**
     * @var Datetime $dateModified  The moment this request last Modified
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;


    public function __construct()
    {
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
	
	public function getOrganization(): ?string
                  	{
                  		return $this->organization;
                  	}
	
	public function setOrganization(string $organization): self
                  	{
                  		$this->organization = $organization;
                  		
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
    	
	public function getProcessType(): ?string
                  	{
                  		return $this->processType;
                  	}
	
	public function setProcessType(string $processType): self
	{
		$this->processType = $processType;
		return $this;
	}
	
	public function getSubmitters(): ?array
	{
		return $this->submitters;
	}
	
	public function setSubmitters(array $submitters): self
	{
		$this->submitters = $submitters;
		
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
                  		$this->dateSubmitted= $dateSubmitted;
                  		return $this;
                  	}

    public function getDateCreated(): ?\DateTimeInterface
    {
    	return $this->dateCreated;
    }
    
    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
    	$this->dateCreated= $dateCreated;
    	
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
}
