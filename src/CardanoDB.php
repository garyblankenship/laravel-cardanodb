<?php

namespace Vampires\CardanoDB;

/**
 * Class CardanoDB
 *
 * todo:
 *  CardanoDB::policy('policy_id'); // {[policy, minted, ...]}
 *  CardanoDB::policy('policy_id')->assets(); // {[token1],[token2],}
 *  CardanoDB::wallet('stakeOraddr')->balance(); // 398.35235
 *  CardanoDB::wallet('stakeOraddr')->info(); // {stake1:"..", addr1:"..", balance:398.35235, ...}
 */
class CardanoDB
{
    protected function wallet($address)
    {
        return new CardanoWallet($address);
    }

    protected function policy($policy)
    {
        return new CardanoPolicy($policy);
    }

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
}
