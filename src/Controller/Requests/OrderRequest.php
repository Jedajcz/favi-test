<?php

declare(strict_types=1);

namespace App\Controller\Requests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Contracts\Service\Attribute\Required;

class OrderRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank]
    #[Required]
    protected $partner_id;

    #[Type('string')]
    #[NotBlank]
    #[Required]
    protected $order_id;

    #[Type('string')]
    #[NotBlank]
    #[Required]
    protected $delivery_date;

    #[Type('array')]
    #[Required]
    #[NotBlank]
    protected $products;

    public function setProperties(Request|array $request): void
    {
        $this->partner_id    = $request->request->get('partner_id');
        $this->order_id      = $request->request->get('order_id');
        $this->delivery_date = $request->request->get('delivery_date');
        $this->products      = $request->request->all()['products'] ?? null;
    }
}
