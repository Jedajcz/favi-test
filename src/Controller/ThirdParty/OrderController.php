<?php

declare(strict_types=1);

namespace App\Controller\ThirdParty;

use App\Controller\Requests\OrderRequest;
use App\Controller\Requests\ProductRequest;
use App\Entity\Order;
use App\Entity\Product;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class OrderController extends AbstractController
{
    public function __construct(
        private OrderRepository $orderRepository,
        private ProductRepository $productRepository,
        private OrderRequest $orderRequest,
        private ProductRequest $productRequest
    ) {
    }

    #[Route('/order', name: 'store-order', methods: 'POST')]
    public function store(Request $request)
    {
        if (false !== $errors = $this->validate($request)) {
            return $this->json($this->getErrorMessages($errors), Response::HTTP_BAD_REQUEST);
        }

        $order = $this->getOrder($request);
        try {
            $this->orderRepository->add($order);
        } catch (UniqueConstraintViolationException $uniqueConstraintViolationException) {
            return $this->json(['message' => 'The order has already been saved'], Response::HTTP_BAD_REQUEST);
        }

        foreach ($request->request->all()['products'] ?? [] as $product) {
            $this->productRepository->add($this->getProduct($product, $order));
        }

        return $this->json(['message' => 'Order was stored successfully'], Response::HTTP_OK);
    }

    #[Route('/order/update-delivery-date/{partnerId}/{orderId}', name: 'update-order-delivery-date', methods: 'PATCH')]
    public function updateDeliveryDate(string $partnerId, string $orderId, Request $request)
    {
        $errors = $this->orderRequest->validate($request, true);
        if (0 < count($errors)) {
            return $this->json($this->getErrorMessages($errors), Response::HTTP_BAD_REQUEST);
        }

        if (null === $order = $this->orderRepository->findOneBy(['order_id' => $orderId, 'partner_id' => $partnerId])) {
            return $this->json(['message' => 'Order not found'], Response::HTTP_BAD_REQUEST);
        }

        $order->setDeliveryDate(\DateTime::createFromFormat('Y-m-d', $request->request->get('delivery_date')));
        $this->orderRepository->update();

        return $this->json(['message' => 'Delivery date was updated'], Response::HTTP_OK);
    }

    private function validate(Request $request): bool|ConstraintViolationListInterface
    {
        $errors = $this->orderRequest->validate($request);

        if (0 < count($errors)) {
            return $errors;
        }

        foreach ($request->request->all()['products'] ?? [] as $product) {
            $errors = $this->productRequest->validate($product);

            if (0 < count($errors)) {
                return $errors;
            }
        }

        return false;
    }

    private function getOrder($request): Order
    {
        $order = new Order();
        $order->setPartnerId($request->request->get('partner_id'));
        $order->setOrderId($request->request->get('order_id'));
        $order->setDeliveryDate(\DateTime::createFromFormat('Y-m-d', $request->request->get('delivery_date')));

        return $order;
    }

    private function getProduct($productRequest, $order): Product
    {
        $product = new Product();
        $product->setName($productRequest['name']);
        $product->setPrice((float) $productRequest['price']);
        $product->setQuantity((int) $productRequest['quantity']);
        $product->setOrder($order);

        return $product;
    }

    // @TODO can be moved to some base controller
    private function getErrorMessages($errors): array
    {
        $messages = [];
        foreach ($errors as $error) {
            $messages[$error->getPropertyPath()][] = $error->getMessage();
        }

        return $messages;
    }
}
