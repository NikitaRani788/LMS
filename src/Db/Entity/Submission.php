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
 * Entity class for 'submissions' table
 */
#[Entity]
#[Table('submissions')]
class Submission extends BaseEntity
{
    #[Id]
    #[Column(name: 'submission_id', type: 'integer', unique: true, insertable: false)]
    #[GeneratedValue]
    private int $submissionId;

    #[Column(name: 'assignment_id', type: 'integer', nullable: true)]
    private ?int $assignmentId;

    #[Column(name: 'user_id', type: 'integer', nullable: true)]
    private ?int $userId;

    #[Column(name: 'submission_path', type: 'string', nullable: true)]
    private ?string $submissionPath;

    #[Column(name: 'submitted_at', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $submittedAt;

    #[Column(name: 'attempt_no', type: 'integer', nullable: true)]
    private ?int $attemptNo;

    #[Column(name: 'submission_status', type: 'string', nullable: true)]
    private ?string $submissionStatus;

    #[Column(name: 'remarks', type: 'text', nullable: true)]
    private ?string $remarks;

    #[Column(name: 'checked_by', type: 'integer', nullable: true)]
    private ?int $checkedBy;

    #[Column(name: 'marks_obtained', type: 'integer', nullable: true)]
    private ?int $marksObtained;

    #[Column(name: 'checked_at', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $checkedAt;

    public function __construct()
    {
        $this->attemptNo = 1;
    }

    public function getSubmissionId(): int
    {
        return $this->submissionId;
    }

    public function setSubmissionId(int $value): static
    {
        $this->submissionId = $value;
        return $this;
    }

    public function getAssignmentId(): ?int
    {
        return $this->assignmentId;
    }

    public function setAssignmentId(?int $value): static
    {
        $this->assignmentId = $value;
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

    public function getSubmissionPath(): ?string
    {
        return HtmlDecode($this->submissionPath);
    }

    public function setSubmissionPath(?string $value): static
    {
        $this->submissionPath = RemoveXss($value);
        return $this;
    }

    public function getSubmittedAt(): ?DateTimeInterface
    {
        return $this->submittedAt;
    }

    public function setSubmittedAt(?DateTimeInterface $value): static
    {
        if (!$this->isInitialized('submittedAt') || !SameDateTime($this->submittedAt, $value)) {
            $this->submittedAt = $value;
        }
        return $this;
    }

    public function getAttemptNo(): ?int
    {
        return $this->attemptNo;
    }

    public function setAttemptNo(?int $value): static
    {
        $this->attemptNo = $value;
        return $this;
    }

    public function getSubmissionStatus(): ?string
    {
        return $this->submissionStatus;
    }

    public function setSubmissionStatus(?string $value): static
    {
        if (!in_array($value, ["submitted", "late", "resubmitted"])) {
            throw new InvalidArgumentException("Invalid 'submission_status' value");
        }
        $this->submissionStatus = $value;
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

    public function getCheckedBy(): ?int
    {
        return $this->checkedBy;
    }

    public function setCheckedBy(?int $value): static
    {
        $this->checkedBy = $value;
        return $this;
    }

    public function getMarksObtained(): ?int
    {
        return $this->marksObtained;
    }

    public function setMarksObtained(?int $value): static
    {
        $this->marksObtained = $value;
        return $this;
    }

    public function getCheckedAt(): ?DateTimeInterface
    {
        return $this->checkedAt;
    }

    public function setCheckedAt(?DateTimeInterface $value): static
    {
        if (!$this->isInitialized('checkedAt') || !SameDateTime($this->checkedAt, $value)) {
            $this->checkedAt = $value;
        }
        return $this;
    }
}
