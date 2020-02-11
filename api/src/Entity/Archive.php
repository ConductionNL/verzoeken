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
 * @ApiResource(
 *     attributes={"order"={"dateCreated": "ASC"}},
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ArchiveRepository")
 */
class Archive
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
	 * @var string $nomination The archive nomination of the resource
	 * @example destroy
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The archive nomination of the resource",
	 *             "type"="string",
	 *             "example"="destroy",
	 *             "maxLength"="255",
	 *             "enum"={"keep", "destroy"},
	 *             "default"="destroy"
	 *         }
	 *     }
	 * )	 
	 *
     * @Assert\Choice({"keep", "destroy"})
	 * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255)
     */
    private $nomination =  "destroy";

    /**
	 * @var string $actionDate A "Y-m-d H:i:s" formatted value stating when an archive action should be made on the resource
	 * 
     * @Assert\DateTime
	 * @Groups({"read","write"})
     * @ORM\Column(type="datetime")
     */
    private $actionDate;

    /**
	 * @var string $status Indication whether the resource must be permanently stored or destroyed after a certain period.
	 * @example to_be_archived
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "Indication whether the resource must be permanently stored or destroyed after a certain period",
	 *             "type"="string",
	 *             "example"="to_be_archived",
	 *             "maxLength"="255",
	 *             "enum"={"to_be_archived", "archived", "term_unknown", "transferred"},
	 *             "default"="to_be_archived"
	 *         }
	 *     }
	 * )	 
	 *
     * @Assert\Choice({"to_be_archived", "archived", "term_unknown", "transferred"})
	 * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255)
     */
    private $status = "to_be_archived";
    
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomination(): ?string
    {
        return $this->nomination;
    }

    public function setNomination(string $nomination): self
    {
        $this->nomination = $nomination;

        return $this;
    }

    public function getActionDate(): ?\DateTimeInterface
    {
        return $this->actionDate;
    }

    public function setActionDate(\DateTimeInterface $actionDate): self
    {
        $this->actionDate = $actionDate;

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
