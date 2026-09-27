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
 * Entity class for 'mcq_questions' table
 */
#[Entity]
#[Table('mcq_questions')]
class McqQuestion extends BaseEntity
{
    #[Id]
    #[Column(name: 'question_id', type: 'integer', unique: true, insertable: false)]
    #[GeneratedValue]
    private int $questionId;

    #[Column(name: 'assignment_id', type: 'integer', nullable: true)]
    private ?int $assignmentId;

    #[Column(name: 'question_text', type: 'text')]
    private string $questionText;

    #[Column(name: 'option_a', type: 'string', nullable: true)]
    private ?string $optionA;

    #[Column(name: 'option_b', type: 'string', nullable: true)]
    private ?string $optionB;

    #[Column(name: 'option_c', type: 'string', nullable: true)]
    private ?string $optionC;

    #[Column(name: 'option_d', type: 'string', nullable: true)]
    private ?string $optionD;

    #[Column(name: 'correct_option', type: 'string', nullable: true)]
    private ?string $correctOption;

    #[Column(name: 'marks', type: 'integer', nullable: true)]
    private ?int $marks;

    #[Column(name: 'created_at', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->marks = 1;
    }

    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    public function setQuestionId(int $value): static
    {
        $this->questionId = $value;
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

    public function getQuestionText(): string
    {
        return HtmlDecode($this->questionText);
    }

    public function setQuestionText(string $value): static
    {
        $this->questionText = RemoveXss($value);
        return $this;
    }

    public function getOptionA(): ?string
    {
        return HtmlDecode($this->optionA);
    }

    public function setOptionA(?string $value): static
    {
        $this->optionA = RemoveXss($value);
        return $this;
    }

    public function getOptionB(): ?string
    {
        return HtmlDecode($this->optionB);
    }

    public function setOptionB(?string $value): static
    {
        $this->optionB = RemoveXss($value);
        return $this;
    }

    public function getOptionC(): ?string
    {
        return HtmlDecode($this->optionC);
    }

    public function setOptionC(?string $value): static
    {
        $this->optionC = RemoveXss($value);
        return $this;
    }

    public function getOptionD(): ?string
    {
        return HtmlDecode($this->optionD);
    }

    public function setOptionD(?string $value): static
    {
        $this->optionD = RemoveXss($value);
        return $this;
    }

    public function getCorrectOption(): ?string
    {
        return HtmlDecode($this->correctOption);
    }

    public function setCorrectOption(?string $value): static
    {
        $this->correctOption = RemoveXss($value);
        return $this;
    }

    public function getMarks(): ?int
    {
        return $this->marks;
    }

    public function setMarks(?int $value): static
    {
        $this->marks = $value;
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
