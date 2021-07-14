<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * An entity representing a submitter of a request.
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
 * @Gedmo\Loggable(logEntryClass="Conduction\CommonGroundBundle\Entity\ChangeLog")
 * @ORM\Entity(repositoryClass="App\Repository\SubmitterRepository")
 * @ApiFilter(SearchFilter::class, properties={"brp": "exact","assent": "exact","person": "exact"})
 */
class Submitter
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
     * @var string The BSN number of the submitter
     *
     * @Assert\Length(
     *     max=255
     * )
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bsn;

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
     * @var string A reference to a wrc organization
     *
     * @Assert\Url
     * @Assert\Length(
     *     max=255
     * )
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $organization;

    /**
     * @var request The request this submitter belongs to
     *
     * @Groups({"read","write"})
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="App\Entity\Request", inversedBy="submitters")
     */
    private $request;

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

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getAssent(): ?string
    {
        return $this->assent;
    }

    public function setAssent(?string $assent): self
    {
        $this->assent = $assent;

        return $this;
    }

    public function getBrp(): ?string
    {
        return $this->brp;
    }

    public function setBrp(?string $brp): self
    {
        $this->brp = $brp;

        return $this;
    }

    public function getBsn(): ?string
    {
        return $this->bsn;
    }

    public function setBsn(?string $bsn): self
    {
        $this->bsn = $bsn;

        return $this;
    }

    public function getPerson(): ?string
    {
        return $this->person;
    }

    public function setPerson(?string $person): self
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
}
