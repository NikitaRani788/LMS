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
 * Entity class for 'announcements' table
 */
#[Entity]
#[Table('announcements')]
class Announcement extends BaseEntity
{
    #[Id]
    #[Column(name: 'announcement_id', type: 'integer', unique: true, insertable: false)]
    #[GeneratedValue]
    private int $announcementId;

    #[Column(name: 'course_id', type: 'integer', nullable: true)]
    private ?int $courseId;

    #[Column(name: 'posted_by', type: 'integer', nullable: true)]
    private ?int $postedBy;

    #[Column(name: 'title', type: 'string', nullable: true)]
    private ?string $title;

    #[Column(name: 'message', options: ['param' => '_message'], type: 'text', nullable: true)]
    private ?string $message;

    #[Column(name: 'priority', type: 'string', nullable: true)]
    private ?string $priority;

    #[Column(name: 'status', type: 'string', nullable: true)]
    private ?string $status;

    #[Column(name: 'created_at', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->priority = 'normal';
        $this->status = 'active';
    }

    public function getAnnouncementId(): int
    {
        return $this->announcementId;
    }

    public function setAnnouncementId(int $value): static
    {
        $this->announcementId = $value;
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

    public function getPostedBy(): ?int
    {
        return $this->postedBy;
    }

    public function setPostedBy(?int $value): static
    {
        $this->postedBy = $value;
        return $this;
    }

    public function getTitle(): ?string
    {
        return HtmlDecode($this->title);
    }

    public function setTitle(?string $value): static
    {
        $this->title = RemoveXss($value);
        return $this;
    }

    public function getMessage(): ?string
    {
        return HtmlDecode($this->message);
    }

    public function setMessage(?string $value): static
    {
        $this->message = RemoveXss($value);
        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(?string $value): static
    {
        if (!in_array($value, ["normal", "important", "urgent"])) {
            throw new InvalidArgumentException("Invalid 'priority' value");
        }
        $this->priority = $value;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $value): static
    {
        if (!in_array($value, ["active", "expired", "archived"])) {
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
