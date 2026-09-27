<?php

namespace PHPMaker2026\Project1\Db\Entity;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateInterval;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use PHPMaker2026\Project1\AdvancedUserInterface;
use PHPMaker2026\Project1\AdvancedSecurity;
use PHPMaker2026\Project1\UserProfile;
use PHPMaker2026\Project1\UserRepository;
use PHPMaker2026\Project1\CustomEntityRepository;
use PHPMaker2026\Project1\DefaultSequenceGenerator;
use PHPMaker2026\Project1\UuidGenerator;
use PHPMaker2026\Project1\Entity as BaseEntity;
use function PHPMaker2026\Project1\Config;
use function PHPMaker2026\Project1\EntityManager;
use function PHPMaker2026\Project1\ConvertToBool;
use function PHPMaker2026\Project1\ConvertToString;
use function PHPMaker2026\Project1\SameDateTime;
use function PHPMaker2026\Project1\RemoveXss;
use function PHPMaker2026\Project1\HtmlDecode;
use function PHPMaker2026\Project1\HashPassword;
use function PHPMaker2026\Project1\PhpEncrypt;
use function PHPMaker2026\Project1\PhpDecrypt;
use function PHPMaker2026\Project1\Security;
use function PHPMaker2026\Project1\IsEmpty;
use InvalidArgumentException;

/**
 * Entity class for 'notes' table
 */
#[Entity]
#[Table('notes')]
class Note extends BaseEntity
{
    #[Id]
    #[Column(name: 'note_id', type: 'integer', unique: true, insertable: false)]
    #[GeneratedValue]
    private int $noteId;

    #[Column(name: 'course_id', type: 'integer', nullable: true)]
    private ?int $courseId;

    #[Column(name: 'uploaded_by', type: 'integer', nullable: true)]
    private ?int $uploadedBy;

    #[Column(name: 'title', type: 'string')]
    private string $title;

    #[Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description;

    #[Column(name: 'file_path', type: 'string', nullable: true)]
    private ?string $filePath;

    #[Column(name: 'note_type', type: 'string', nullable: true)]
    private ?string $noteType;

    #[Column(name: 'visibility_status', type: 'string', nullable: true)]
    private ?string $visibilityStatus;

    #[Column(name: 'created_at', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->visibilityStatus = 'visible';
    }

    public function getNoteId(): int
    {
        return $this->noteId;
    }

    public function setNoteId(int $value): static
    {
        $this->noteId = $value;
        return $this;
    }

    public function getCourseId(): ?int
    {
        return $this->courseId;
    }

    public function setCourseId(?int $value): static
    {
        $this->courseId = $value;
        return $this;
    }

    public function getUploadedBy(): ?int
    {
        return $this->uploadedBy;
    }

    public function setUploadedBy(?int $value): static
    {
        $this->uploadedBy = $value;
        return $this;
    }

    public function getTitle(): string
    {
        return HtmlDecode($this->title);
    }

    public function setTitle(string $value): static
    {
        $this->title = RemoveXss($value);
        return $this;
    }

    public function getDescription(): ?string
    {
        return HtmlDecode($this->description);
    }

    public function setDescription(?string $value): static
    {
        $this->description = RemoveXss($value);
        return $this;
    }

    public function getFilePath(): ?string
    {
        return HtmlDecode($this->filePath);
    }

    public function setFilePath(?string $value): static
    {
        $this->filePath = RemoveXss($value);
        return $this;
    }

    public function getNoteType(): ?string
    {
        return $this->noteType;
    }

    public function setNoteType(?string $value): static
    {
        if (!in_array($value, ["pdf", "ppt", "doc", "link", "syllabus", "lesson_plan", "content"])) {
            throw new InvalidArgumentException("Invalid 'note_type' value");
        }
        $this->noteType = $value;
        return $this;
    }

    public function getVisibilityStatus(): ?string
    {
        return $this->visibilityStatus;
    }

    public function setVisibilityStatus(?string $value): static
    {
        if (!in_array($value, ["visible", "hidden"])) {
            throw new InvalidArgumentException("Invalid 'visibility_status' value");
        }
        $this->visibilityStatus = $value;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $value): static
    {
        if (!$this->isInitialized('createdAt') || !SameDateTime($this->createdAt, $value)) {
            $this->createdAt = $value;
        }
        return $this;
    }
}
