<?php

declare(strict_types=1);

namespace App\Controller\Requests;

use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderRequest
{
    #[Type('integer')]
    protected $price;

    #[Type('string')]
    protected $name;

    public function __construct(
        protected ValidatorInterface $validator
    ) {
    }

    public function validate(): ConstraintViolationListInterface
    {
        return $this->validator->validate($this);
    }
}
