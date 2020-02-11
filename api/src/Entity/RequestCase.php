<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * An case atached to a request
 * 
 * @ApiResource(
 *     attributes={"order"={"dateCreated": "ASC"}},
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RequestCaseRepository")
 */
class RequestCase
{
	/**
	 * @var \Ramsey\Uuid\UuidInterface $id The UUID identifier of this object
	 * @example e2984465-190a-4562-829e-a8cca81aa35d
	 *
	 * @ApiProperty(
	 * 	   identifier=true,
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The UUID identifier of this object",
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
	 * @var string $requestCase The OpenCase that is handling this request or supposed to handle this request
	 * @example zrc.gemeente.nl/case/e2984465-190a-4562-829e-a8cca81aa35d
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The OpenCase that is handling this request or supposed to handle this request",
	 *             "type"="string",
	 *             "format"="url",
	 *             "example"="zrc.gemeente.nl/case/e2984465-190a-4562-829e-a8cca81aa35d",
	 *             "maxLength"="255",
	 *             "required"=true
	 *         }
	 *     }
	 * )
	 * 
	 * @Assert\Url
	 * @Assert\Length(
	 *      max = 255
	 * )
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255)
     */
    private $requestCase;

    /**
     * @var Object $request The request that this case is handling
     * 
     * @MaxDepth(1)
     * @Groups({"read","write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Request", inversedBy="requestCases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $request;

    /**
	 * @var string $caseNumber The number of an case
	 * @example 12345
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The number of an case",
	 *             "type"="string",
	 *             "example"="12345",
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
    private $caseNumber;
    
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

    public function getId()
    {
        return $this->id;
    }

    public function getRequestCase(): ?string
    {
    	return $this->requestCase;
    }

    public function setRequestCase(string $requestCase): self
    {
    	$this->requestCase = $requestCase;

        return $this;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getCaseNumber(): ?string
    {
        return $this->caseNumber;
    }

    public function setCaseNumber(?string $caseNumber): self
    {
        $this->caseNumber = $caseNumber;

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
