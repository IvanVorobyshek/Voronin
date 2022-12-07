<?php

namespace Voronin\CoinsPayment\Api;

use Voronin\CoinsPayment\Api\Data\CoinsInterface;

interface CoinsRepositoryInterface
{

    public function get(int $id): CoinsInterface;


    public function save(CoinsInterface $coins): CoinsInterface;

    public function delete(CoinsInterface $coins): bool;

    public function deleteById(int $id): bool;
}
