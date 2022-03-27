<?php

declare(strict_types=1);

namespace App\Controller\Requests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequest
{
    public function __construct(
        protected ValidatorInterface $validator
    ) {
    }

    public function validate(Request|array $request, bool $onlyDeliveryDate = false): ConstraintViolationListInterface
    {
        $this->setProperties($request);

        if ($onlyDeliveryDate) {
            return $this->validator->validate(value: $this, groups: ['delivery_date']);
        }

        return $this->validator->validate($this);
    }

    abstract protected function setProperties(Request|array $request): void;
}
