<?php

namespace Vampires\CardanoDB;

use Illuminate\Support\Facades\DB;

class CardanoWallet
{
    public string $address;
    public string $stake;

    public string $connection;
    public float $balance;
    public int $totalAssets;

    /**
     * @param $address
     */
    public function __construct($address)
    {
        $this->address = $address;
        $this->stake   = stake_address($address);

        $this->connection = config('cardanodb.database.connection');

        //$this->balance     = $this->balance();
        //$this->totalAssets = $this->totalAssets();
    }

    public function totalAssets()
    {
        $this->totalAssets = DB::connection($this->connection)
                               ->selectOne('SELECT COUNT(*) as total FROM assets_at_address(?)', [$this->stake])->total;

        return $this->totalAssets;
    }

    public function balance()
    {
        $lovelace = DB::connection($this->connection)
                      ->table('utxo_view')
                      ->join('stake_address', 'stake_address.id', '=', 'utxo_view.stake_address_id')
                      ->where('view', $this->address)
                      ->sum('value');

        $this->balance = floatval($lovelace / 1000000);

        return $this->balance;
    }
}
