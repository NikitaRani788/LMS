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
 * Entity class for 'mcq_answers' table
 */
#[Entity]
#[Table('mcq_answers')]
class McqAnswer extends BaseEntity
{
    #[Id]
    #[Column(name: 'answer_id', type: 'integer', unique: true, insertable: false)]
    #[GeneratedValue]
    private int $answerId;

    #[Column(name: 'question_id', type: 'integer', nullable: true)]
    private ?int $questionId;

    #[Column(name: 'user_id', type: 'integer', nullable: true)]
    private ?int $userId;

    #[Column(name: 'selected_option', type: 'string', nullable: true)]
    private ?string $selectedOption;

    #[Column(name: 'submitted_at', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $submittedAt;

    public function getAnswerId(): int
    {
        return $this->answerId;
    }

    public function setAnswerId(int $value): static
    {
        $this->answerId = $value;
        return $this;
    }

    public function getQuestionId(): ?int
    {
        return $this->questionId;
    }

    public function setQuestionId(?int $value): static
    {
        $this->questionId = $value;
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

    public function getSelectedOption(): ?string
    {
        return HtmlDecode($this->selectedOption);
    }

    public function setSelectedOption(?string $value): static
    {
        $this->selectedOption = RemoveXss($value);
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
}
