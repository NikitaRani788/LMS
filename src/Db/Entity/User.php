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
 * Entity class for 'users' table
 */
#[Entity]
#[Table('users')]
#[UniqueEntity('email')]
class User extends BaseEntity
{
    #[Id]
    #[Column(name: 'userid', type: 'integer', unique: true, insertable: false)]
    #[GeneratedValue]
    private int $userid;

    #[Column(name: 'first_name', type: 'string')]
    private string $firstName;

    #[Column(name: 'last_name', type: 'string')]
    private string $lastName;

    #[Column(name: 'email', type: 'string', unique: true)]
    private string $email;

    #[Column(name: 'password_hash', type: 'string')]
    private string $passwordHash;

    #[Column(name: 'role', type: 'string')]
    private string $role;

    #[Column(name: 'department_id', type: 'integer', nullable: true)]
    private ?int $departmentId;

    #[Column(name: 'status', type: 'string', nullable: true)]
    private ?string $status;

    #[Column(name: 'phone', type: 'string', nullable: true)]
    private ?string $phone;

    #[Column(name: 'last_login_at', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $lastLoginAt;

    #[Column(name: 'created_at', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $createdAt;

    #[Column(name: 'updated_at', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->status = 'active';
    }

    public function getUserid(): int
    {
        return $this->userid;
    }

    public function setUserid(int $value): static
    {
        $this->userid = $value;
        return $this;
    }

    public function getFirstName(): string
    {
        return HtmlDecode($this->firstName);
    }

    public function setFirstName(string $value): static
    {
        $this->firstName = RemoveXss($value);
        return $this;
    }

    public function getLastName(): string
    {
        return HtmlDecode($this->lastName);
    }

    public function setLastName(string $value): static
    {
        $this->lastName = RemoveXss($value);
        return $this;
    }

    public function getEmail(): string
    {
        return HtmlDecode($this->email);
    }

    public function setEmail(string $value): static
    {
        $this->email = RemoveXss($value);
        return $this;
    }

    public function getPasswordHash(): string
    {
        return HtmlDecode($this->passwordHash);
    }

    public function setPasswordHash(string $value): static
    {
        $this->passwordHash = RemoveXss($value);
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $value): static
    {
        if (!in_array($value, ["admin", "teacher", "student"])) {
            throw new InvalidArgumentException("Invalid 'role' value");
        }
        $this->role = $value;
        return $this;
    }

    public function getDepartmentId(): ?int
    {
        return $this->departmentId;
    }

    public function setDepartmentId(?int $value): static
    {
        $this->departmentId = $value;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $value): static
    {
        if (!in_array($value, ["active", "blocked", "deleted"])) {
            throw new InvalidArgumentException("Invalid 'status' value");
        }
        $this->status = $value;
        return $this;
    }

    public function getPhone(): ?string
    {
        return HtmlDecode($this->phone);
    }

    public function setPhone(?string $value): static
    {
        $this->phone = RemoveXss($value);
        return $this;
    }

    public function getLastLoginAt(): ?DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?DateTimeInterface $value): static
    {
        if (!$this->isInitialized('lastLoginAt') || !SameDateTime($this->lastLoginAt, $value)) {
            $this->lastLoginAt = $value;
        }
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

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $value): static
    {
        if (!$this->isInitialized('updatedAt') || !SameDateTime($this->updatedAt, $value)) {
            $this->updatedAt = $value;
        }
        return $this;
    }
}
