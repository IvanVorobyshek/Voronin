<?php

namespace Voronin\CoinsPayment\Api;

use Voronin\CoinsPayment\Api\Data\CoinsInterface;

interface CoinsRepositoryInterface
{

    /**
     * Get the coin row
     *
     * @param int $id
     * @return CoinsInterface
     */
    public function get(int $id): CoinsInterface;

    /**
     * Save the coin row
     *
     * @param CoinsInterface $coins
     * @return CoinsInterface
     */
    public function save(CoinsInterface $coins): CoinsInterface;

    /**
     * Delete coin row
     *
     * @param CoinsInterface $coins
     * @return bool
     */
    public function delete(CoinsInterface $coins): bool;

    /**
     * Delete coin row by ID
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;
}
