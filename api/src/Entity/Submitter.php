<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;

/**
 * An entity representing a submitter of a request
 *
 * This entity represents a request submitter with the goal of being able to find the assent and the brp references
 *
 * @author Robert Zondervan <robert@conduction.nl>
 *
 * @category Entity
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true},
 * )
 * @Gedmo\Loggable(logEntryClass="App\Entity\ChangeLog")
 * @ORM\Entity(repositoryClass="App\Repository\SubmitterRepository")
 * @ApiFilter(SearchFilter::class, properties={"brp": "exact","assent": "exact","person": "exact"})
 */
class Submitter
{
    /**
     * @var UuidInterface $id The UUID identifier of this resource
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
     * @var string A reference to a Assent service for the requesting assent of a request
     *
     * @Assert\Url
     * @Assert\Length(
     *     max=255
     * )
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $assent;

    /**
     * @var string A reference to the BRP for the requester of a request
     *
     * @Assert\Url
     * @Assert\Length(
     *     max=255
     * )
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brp;

    /**
     * @var string A reference to the contact component person
     *
     * @Assert\Url
     * @Assert\Length(
     *     max=255
     * )
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $person;

    /**
     * @var request The request this submitter belongs to
     *
     * @Groups({"read","write"})
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="App\Entity\Request", inversedBy="submitters")
     */
    private $request;

    /**
     * @var Datetime $dateSubmitted The moment this request was submitted by the submitter
     *
     * @Gedmo\Versioned
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
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getBrp(): ?string
    {
        return $this->brp;
    }

    public function setBrp(string $brp): self
    {
        $this->brp = $brp;

        return $this;
    }

    public function getPerson(): ?string
    {
    	return $this->person;
    }

    public function setPerson(string $person): self
    {
    	$this->person= $person;

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
