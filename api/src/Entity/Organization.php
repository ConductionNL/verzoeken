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
 * An organization handling a request
 * 
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\OrganizationRepository")
 */
class Organization
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
	 * @var string $rsin The RSIN of a organization that is handling or supposed to handle this request
	 * @example 002851234
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The RSIN of a organization that is handling or supposed to handle this request",
	 *             "type"="string",
	 *             "example"="002851234",
	 *              "maxLength"="255",
	 *             "required"=true
	 *         }
	 *     }
	 * )
	 * 
	 * @Assert\NotNull
	 * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $rsin;
    
    /**
     * @param string $status The status of this request in the organization
	 * @example none
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *         	   "description" = "The status of this request in the organization",
     *             "type"="string",
     *             "example"="none",
     *             "maxLength"="255",
     *             "enum"={"none","accepted","processing","complete","rejected"},
     *             "default"="none",
     *         }
     *     }
     * )	 
     *
     * @Assert\Choice({"none","accepted","processing","complete","rejected"})
     * @Assert\NotNull
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     */
    private $status = "none";
    
    /**
     * @var Object $request The request that this organsisation is handling
     * 
     * @MaxDepth(1)
     * @Groups({"read","write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Request", inversedBy="organizations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $request;
    
    /**
     * @var Datetime $createdAt The moment this submitter was added to the request
     * 
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function getId()
    {
        return $this->id;
    }

    public function getRsin(): ?string
    {
        return $this->rsin;
    }

    public function setRsin(string $rsin): self
    {
        $this->rsin = $rsin;

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

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): self
    {
        $this->request = $request;

        return $this;
    }
}