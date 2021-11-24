<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Article
{
    use Timestampable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5,max=255,minMessage="Le titre doit contenir au moins 5 caractères !")
     * @Assert\NotBlank(message="Veuillez indiquer un titre")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=10,minMessage="Le contenu doit contenir au moins 10 caractères !")
     * @Assert\NotBlank(message="Veuillez indiquer un contenu")
     */
    private $content;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @Assert\Image(maxSize="8M",mimeTypes = {"image/jpg","image/jpeg","image/png"},mimeTypesMessage = "Only JPEG, PNG and JPG extensions accepted")
     * @Vich\UploadableField(mapping="article_image", fileNameProperty="image")
     * 
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            //Il est nécessaire qu'au moins un champ change si vous utilisez la doctrine
            //sinon les écouteurs d'événements ne seront pas appelés et le fichier sera perdu.

            $this->setUpdatedAt(new \DateTimeImmutable);
            //$this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
