<?php

namespace Vampires\CardanoDB;

use Illuminate\Support\Facades\DB;

class CardanoWallet
{
    private string $address;

    /**
     * @param $address
     */
    public function __construct($address)
    {
        $this->address = $address;
    }

    public function balance()
    {
        $lovelace = DB::connection(config('cardanodb.database.connection'))
                      ->table('utxo_view')
                      ->join('stake_address', 'stake_address.id', '=', 'utxo_view.stake_address_id')
                      ->where('view', $this->address)
                      ->sum('value');

        return floatval($lovelace / 1000000);
    }
}
