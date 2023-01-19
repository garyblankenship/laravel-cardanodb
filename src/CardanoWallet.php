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
    public static function getAssets()
    {

        $address = $this->address
        $query = "
		SELECT
encode(decode(substring(concat(multi_asset.name), 3), 'hex'), 'escape') as asset_name,
tx_metadata.json as metadata, 
*

FROM utxo_view utxov

JOIN tx ON tx.id = utxov.tx_id
JOIN ma_tx_out ON ma_tx_out.tx_out_id = utxov.id
JOIN ma_tx_mint ON ma_tx_mint.ident = ma_tx_out.ident
JOIN multi_asset ON multi_asset.id = ma_tx_mint.ident
LEFT JOIN tx_metadata ON tx_metadata.tx_id = ma_tx_mint.tx_id


WHERE utxov.address = ?
		";

        $results = DB::connection($this->connection)->select($query, [$address]);

        if (!empty($results) && $results[0]->stake_address_id > 0) {
            $query = "
		SELECT
encode(decode(substring(concat(multi_asset.name), 3), 'hex'), 'escape') as asset_name,
tx_metadata.json as metadata, 
*

FROM utxo_view utxov

JOIN tx ON tx.id = utxov.tx_id
JOIN ma_tx_out ON ma_tx_out.tx_out_id = utxov.id
JOIN ma_tx_mint ON ma_tx_mint.ident = ma_tx_out.ident
JOIN multi_asset ON multi_asset.id = ma_tx_mint.ident
LEFT JOIN tx_metadata ON tx_metadata.tx_id = ma_tx_mint.tx_id


WHERE utxov.stake_address_id = ?
		";

            $results = DB::connection($this->connection)->select($query, [$results[0]->stake_address_id]);
        }
        $ret = array();
        foreach ($results as $r) {
            $reti['asset_name'] = $r->asset_name;
            $m = json_decode($r->metadata);
            foreach (get_object_vars($m) as $mdk) {
                if (is_object($mdk)) {
                    $reti['asset'] = $mdk->{$reti['asset_name']};
                }
            }
            $ret[] = $reti;
        }
        return $ret;
    }
}
// verify a transaction on db sync.
	private static function verifyTransaction(string $txID, string $paymentAddress = null, array $assets = null): array
	{
		$data = [
			'input' => [
				'assets' => $assets,
				'paymentAddress' => $paymentAddress,
				'txID' => $txID
			]
		];
        /* this part requires teh cli
		if (!is_null($paymentAddress) && !is_null($assets)) {
			$data['verifyAsset'] = self::verifyAssets($paymentAddress, $assets);

			if (!empty($verifyAsset['error'])) {
				return $verifyAsset;
			}
		}
        */

		$query = "SELECT tx.id FROM tx WHERE tx.hash = ?";
		$results = DB::connection($this->connection)->select($query, ['\x' . $txID]);

		if (empty($results[0]->id)) {
			$data['error'] = 'Failed to find transaction.';
			return $data;
		}

		$data['results'] = ['id' => $results[0]->id];

		return $data;
	}
