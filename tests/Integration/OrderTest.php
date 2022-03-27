<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $kernel = self::bootKernel();

        $this->orderRepository = $kernel->getContainer()->get('doctrine')->getRepository(Order::class);
    }

    public function testCreateOrderSuccessfully(): void
    {
        self::bootKernel();

        $order = new Order();
        $order->setOrderId('123456789');
        $order->setPartnerId('123456789');
        $order->setDeliveryDate(\DateTime::createFromFormat('Y-m-d', '2022-03-27'));

        $this->orderRepository->add($order);

        $orderFromDb = $this->orderRepository->findOneBy(['id' => $order->getId()]);
        $this->assertEquals($order->getOrderId(), $orderFromDb->getOrderId());
        $this->assertEquals($order->getPartnerId(), $orderFromDb->getPartnerId());
        $this->assertEquals($order->getDeliveryDate(), $orderFromDb->getDeliveryDate());
    }

    public function testCreateTwoSameOrders(): void
    {
        $this->expectException(UniqueConstraintViolationException::class);

        self::bootKernel();

        $order = new Order();
        $order->setOrderId('orderId');
        $order->setPartnerId('partnerId');
        $order->setDeliveryDate(\DateTime::createFromFormat('Y-m-d', '2022-03-27'));

        $this->orderRepository->add($order);

        $order = new Order();
        $order->setOrderId('orderId');
        $order->setPartnerId('partnerId');
        $order->setDeliveryDate(\DateTime::createFromFormat('Y-m-d', '2022-03-27'));
        $this->orderRepository->add($order);
    }
}
