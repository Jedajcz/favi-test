<?php

declare(strict_types=1);

namespace App\Controller\Requests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Contracts\Service\Attribute\Required;

class ProductRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank]
    #[Required]
    protected $name;

    #[Type('float')]
    #[NotBlank]
    #[Required]
    protected $price;

    #[Type('integer')]
    #[NotBlank]
    #[Required]
    protected $quantity;

    protected function setProperties(Request|array $request): void
    {
        if (isset($request['name'])) {
            $this->name = $this->getValueByKeyOrNull($request, 'name');
        }

        if (isset($request['price'])) {
            $this->price = (float) $this->getValueByKeyOrNull($request, 'price');
        }

        if (isset($request['quantity'])) {
            $this->quantity = (int) $this->getValueByKeyOrNull($request, 'quantity');
        }
    }

    private function getValueByKeyOrNull($request, $key)
    {
        return $request[$key] ?? null;
    }
}
