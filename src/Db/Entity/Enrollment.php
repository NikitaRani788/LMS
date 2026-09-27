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
 * Entity class for 'enrollments' table
 */
#[Entity]
#[Table('enrollments')]
class Enrollment extends BaseEntity
{
    #[Id]
    #[Column(name: 'enrollment_id', type: 'integer', unique: true, insertable: false)]
    #[GeneratedValue]
    private int $enrollmentId;

    #[Column(name: 'user_id', type: 'integer', nullable: true)]
    private ?int $userId;

    #[Column(name: 'course_id', type: 'integer', nullable: true)]
    private ?int $courseId;

    #[Column(name: 'enrollment_date', type: 'date', nullable: true)]
    private ?DateTimeInterface $enrollmentDate;

    #[Column(name: 'enrollment_status', type: 'string', nullable: true)]
    private ?string $enrollmentStatus;

    #[Column(name: 'remarks', type: 'text', nullable: true)]
    private ?string $remarks;

    public function __construct()
    {
        $this->enrollmentStatus = 'active';
    }

    public function getEnrollmentId(): int
    {
        return $this->enrollmentId;
    }

    public function setEnrollmentId(int $value): static
    {
        $this->enrollmentId = $value;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $value): static
    {
        $this->userId = $value;
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

    public function getEnrollmentDate(): ?DateTimeInterface
    {
        return $this->enrollmentDate;
    }

    public function setEnrollmentDate(?DateTimeInterface $value): static
    {
        if (!$this->isInitialized('enrollmentDate') || !SameDateTime($this->enrollmentDate, $value)) {
            $this->enrollmentDate = $value;
        }
        return $this;
    }

    public function getEnrollmentStatus(): ?string
    {
        return $this->enrollmentStatus;
    }

    public function setEnrollmentStatus(?string $value): static
    {
        if (!in_array($value, ["active", "completed", "dropped"])) {
            throw new InvalidArgumentException("Invalid 'enrollment_status' value");
        }
        $this->enrollmentStatus = $value;
        return $this;
    }

    public function getRemarks(): ?string
    {
        return HtmlDecode($this->remarks);
    }

    public function setRemarks(?string $value): static
    {
        $this->remarks = RemoveXss($value);
        return $this;
    }
}
