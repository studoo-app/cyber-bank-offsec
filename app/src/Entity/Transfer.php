<?php

namespace App\Entity;
use App\Validator\AccountNumberIsValid;
use Symfony\Component\Validator\Constraints as Assert;
class Transfer
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 4,max: 4)]
    #[Assert\Regex(pattern: '/^\d+/')]
    #[AccountNumberIsValid]
    private ?string $srcAccountNumber;
    #[Assert\NotBlank]
    #[Assert\Length(min: 4,max: 4)]
    #[Assert\Regex(pattern: '/^\d+/')]
    #[AccountNumberIsValid]
    private ?string $destAccountNumber;
    #[Assert\NotBlank]
    #[Assert\GreaterThan(0)]
    private ?int $amount;

    public function __construct()
    {
    }

    public function getSrcAccountNumber(): ?string
    {
        return $this->srcAccountNumber;
    }

    public function setSrcAccountNumber(?string $srcAccountNumber): void
    {
        $this->srcAccountNumber = $srcAccountNumber;
    }

    public function getDestAccountNumber(): ?string
    {
        return $this->destAccountNumber;
    }

    public function setDestAccountNumber(?string $destAccountNumber): void
    {
        $this->destAccountNumber = $destAccountNumber;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }




}