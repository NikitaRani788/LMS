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
 * Entity class for 'assignments' table
 */
#[Entity]
#[Table('assignments')]
class Assignment extends BaseEntity
{
    #[Id]
    #[Column(name: 'assignment_id', type: 'integer', unique: true, insertable: false)]
    #[GeneratedValue]
    private int $assignmentId;

    #[Column(name: 'course_id', type: 'integer', nullable: true)]
    private ?int $courseId;

    #[Column(name: 'assigned_by', type: 'integer', nullable: true)]
    private ?int $assignedBy;

    #[Column(name: 'title', type: 'string')]
    private string $title;

    #[Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description;

    #[Column(name: 'assignment_type', type: 'string', nullable: true)]
    private ?string $assignmentType;

    #[Column(name: 'due_date', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $dueDate;

    #[Column(name: 'max_marks', type: 'integer', nullable: true)]
    private ?int $maxMarks;

    #[Column(name: 'attachment_path', type: 'string', nullable: true)]
    private ?string $attachmentPath;

    #[Column(name: 'status', type: 'string', nullable: true)]
    private ?string $status;

    #[Column(name: 'created_at', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->assignmentType = 'practical';
        $this->status = 'active';
    }

    public function getAssignmentId(): int
    {
        return $this->assignmentId;
    }

    public function setAssignmentId(int $value): static
    {
        $this->assignmentId = $value;
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

    public function getAssignedBy(): ?int
    {
        return $this->assignedBy;
    }

    public function setAssignedBy(?int $value): static
    {
        $this->assignedBy = $value;
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

    public function getAssignmentType(): ?string
    {
        return $this->assignmentType;
    }

    public function setAssignmentType(?string $value): static
    {
        if (!in_array($value, ["practical", "mcq"])) {
            throw new InvalidArgumentException("Invalid 'assignment_type' value");
        }
        $this->assignmentType = $value;
        return $this;
    }

    public function getDueDate(): ?DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?DateTimeInterface $value): static
    {
        if (!$this->isInitialized('dueDate') || !SameDateTime($this->dueDate, $value)) {
            $this->dueDate = $value;
        }
        return $this;
    }

    public function getMaxMarks(): ?int
    {
        return $this->maxMarks;
    }

    public function setMaxMarks(?int $value): static
    {
        $this->maxMarks = $value;
        return $this;
    }

    public function getAttachmentPath(): ?string
    {
        return HtmlDecode($this->attachmentPath);
    }

    public function setAttachmentPath(?string $value): static
    {
        $this->attachmentPath = RemoveXss($value);
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $value): static
    {
        if (!in_array($value, ["active", "closed", "archived"])) {
            throw new InvalidArgumentException("Invalid 'status' value");
        }
        $this->status = $value;
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
