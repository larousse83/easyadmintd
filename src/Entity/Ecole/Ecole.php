<?php

namespace App\Entity\Ecole;

use App\Repository\Ecole\EcoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=EcoleRepository::class)
 * @Vich\Uploadable
 */
class Ecole
{
    const SERVER_PATH_TO_IMG_FOLDER = 'medias/images';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * image de l'ecole
     * @var UploadedFile
     * @Vich\UploadableField(mapping="imageFile", fileNameProperty="image")
     */
    private $imageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, unique=false, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Classe::class, mappedBy="ecole")
     */
    private $classes;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection|Classe[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->setEcole($this);
        }

        return $this;
    }

    public function removeClass(Classe $class): self
    {
        if ($this->classes->removeElement($class)) {
            // set the owning side to null (unless already changed)
            if ($class->getEcole() === $this) {
                $class->setEcole(null);
            }
        }

        return $this;
    }
    /**
     * @return UploadedFile
     */
    public function getimageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param UploadedFile $imageFile
     * @param bool $delete
     * @throws Exception
     */
    public function setImageFile($imageFile, $delete = true)
    {
        if ($delete) {
            //change le nom ici
            $uniqueName = $this->generateUniqueName( $imageFile );
            if ($uniqueName != null) {
                $imageFile->move(
                    self::SERVER_PATH_TO_IMG_FOLDER,
                    $uniqueName
                );
            }
            $this->image = $uniqueName;

        }else{
            $this->imageFile = $imageFile;
        }
    }
    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     *
     * @param bool $mustKeepOriginal
     * @throws Exception
     */
    public function uploadImg($mustKeepOriginal = false)
    {
        // the ImageFile property can be empty if the field is not required
        if (null === $this->getImageFile()) {
            return;
        }
        //verifie si il existe déjà un fichier si oui on le supprime
        if($this->getImage() !== null){
            array_map('unlink', glob(getcwd().'/'.self::SERVER_PATH_TO_IMG_FOLDER.'/'.$this->getId()."/*"));
        }

        // we use the original imagefile name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and target filename as params
        if($mustKeepOriginal){
            $dir = getcwd().'/public/'.self::SERVER_PATH_TO_IMG_FOLDER;
            if (!is_dir($dir)) {
                if (false === @mkdir($dir, 0777, true) && !is_dir($dir)) {
                    throw new FileException(sprintf('Unable to create the "%s" directory', $dir));
                }
            } elseif (!is_writable($dir)) {
                throw new FileException(sprintf('Unable to write in the "%s" directory', $dir));
            }
            copy($this->getImageFile()->getRealPath(), getcwd().'/public/'.self::SERVER_PATH_TO_IMG_FOLDER.'/'.$this->getImageFile()->getClientOriginalName());
        } else {
            $this->getImageFile()->move(
                getcwd() . '/' . self::SERVER_PATH_TO_IMG_FOLDER,
                $this->getImageFile()->getClientOriginalName()
            );
        }

        // set the path property to the filename where you've saved the imageFile
        $this->setImage($this->getImageFile()->getClientOriginalName());

        // clean up the file property as you won't need it anymore
        $this->setImageFile(null, false);
    }

    /**
     * Retourne le chemin de la video
     */
    public function getWebPathImg()
    {
        return '/'.self::SERVER_PATH_TO_IMG_FOLDER.'/'.$this->getImage();
    }

}
