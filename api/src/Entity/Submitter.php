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
 * The submitters of a request
 * 
 * @ApiResource(
 *     attributes={"order"={"dateCreated": "ASC"}},
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\SubmitterRepository")
 */
class Submitter
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
     * @var string $assent The Assent that is used to check if this submitter given assent to this request
	 * @example irc.zaakonline.nl/assent/e2984465-190a-4562-829e-a8cca81aa35d
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The Assent that is used to check if this submitter given assent to this request",
	 *             "type"="string",
	 *             "format"="url",
	 *             "example"="irc.zaakonline.nl/assent/e2984465-190a-4562-829e-a8cca81aa35d",
	 *             "maxLength"="255"
	 *         }
	 *     }
	 * )
	 * 
	 * @Assert\Url
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
	private $assent;
	
	/**
     * @var string $contact The person that is used for contact information if no BSN or identification of the subitter is required
	 * @example crc.zaakonline.nl/person/e2984465-190a-4562-829e-a8cca81aa35d
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The person that is used for contact information if no BSN or identification of the subitter is required",
	 *             "type"="string",
	 *             "format"="url",
	 *             "example"="crc.zaakonline.nl/person/e2984465-190a-4562-829e-a8cca81aa35d",
	 *             "maxLength"="255"
	 *         }
	 *     }
	 * )
	 * 
	 * @Assert\Url
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $contact;

    /**
	 * @var string $targetOrganization The BSN of the person that submitted this request
	 * @example 999993653
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The BSN of the person that submitted this request",
	 *             "type"="string",
	 *             "format"="bsn",
	 *             "example"="999993653",
	 *             "maxLength"="10"
	 *         }
	 *     }
	 * )
	 * 
	 * @Assert\Length(
	 *      max = 10
	 * )
	 * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $person;

    /**
	 * @var string $targetOrganization The RSIN of the organization that submitted this request
	 * @example 002851234
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The RSIN of the organization that submitted this request",
	 *             "type"="string",
	 *             "format"="rsin",
	 *             "example"="002851234",
	 *             "maxLength"="10"
	 *         }
	 *     }
	 * )
	 * 
	 * @Assert\Length(
	 *      max = 10
	 * )
	 * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $organization;

    /**
     * @var Object $request The request that this submitter submitted
     * 
     * @MaxDepth(1)
	 * @Groups({"read", "write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Request", inversedBy="submitters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $request;

    /**
	 * @param string $role The role that a party has on this request
	 * @example initiator
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The role that a party has on this request",
	 *             "type"="string",
	 *             "example"="initiator",
	 *             "maxLength"="255",
	 *             "enum"={"advisor","practitioner","interested_party","interested_party","initiator","customer_care","coordinator","co_initiator"},
	 *             "default"="initiator"
	 *         }
	 *     }
	 * )	 
	 *
     * @Assert\Choice({"advisor","practitioner","interested_party","interested_party","initiator","customer_care","coordinator","co_initiator"})
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * 
	 * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255)
     */
    private $role = "initiator";
    
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

    public function getSubmitter(): ?string
    {
        return $this->submitter;
    }

    public function setSubmitter(string $submitter): self
    {
        $this->submitter = $submitter;

        return $this;
    }

    public function getAssent(): ?string
    {
        return $this->assent;
    }

    public function setAssent(string $assent): self
    {
        $this->assent = $assent;

        return $this;
    }

    public function getPerson(): ?string
    {
        return $this->person;
    }

    public function setPerson(string $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    public function setOrganization(?string $organization): self
    {
        $this->organization = $organization;

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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

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
