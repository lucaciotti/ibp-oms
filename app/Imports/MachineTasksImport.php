<?php

namespace App\Imports;

use App\Models\MachineJob;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Package;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MachineTasksImport implements ToModel, WithHeadingRow
{
    
    // use Maatwebsite\Excel\Imports\HeadingRowFormatter;
    // HeadingRowFormatter::default('none');
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try{
            $customer_name= $row['Cliente'];
            $product_name = $row['Modello'];
            $cart_name = $row['Carrello'];
            $package_name = $row['Imballo'];
            $customer = Customer::firstOrCreate(['name' => $customer_name], ['name' => $customer_name]);
            $product = Product::firstOrCreate(['name' => $product_name], ['name' => $product_name]);
            $cart = Cart::firstOrCreate(['name' => $cart_name], ['name' => $cart_name]);
            $package = Package::firstOrCreate(['name' => $package_name], ['name' => $package_name]);
            
            $data_consegna = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['Data di Consegna']));
            $matricola = $row['N° matricola'];
            $tipologia =isset($row['Tipologia']) ? $row['Tipologia']: '';
            $impianto = isset($row['Impianto']) ? $row['Impianto'] :'';
            $colonna = isset($row['Colonna']) ? $row['Colonna'] : '';
            $batteria = isset($row['Batteria']) ? $row['Batteria'] : '';
            $ruota_tastatrice = isset($row['Ruota Tastatrice']) ? $row['Ruota Tastatrice'] : '';
            $opt_cart1 = isset($row['Opt. Carrello']) ? $row['Opt. Carrello'] : '';
            $opt_cart2 = isset($row['Opt. Carrello2']) ? $row['Opt. Carrello2'] : '';
            $opt_cart3 = isset($row['Opt. Carrello3']) ? $row['Opt. Carrello3'] : '';
            $dim_imballo = isset($row['dimensioni imballo']) ? $row['dimensioni imballo'] : '';
            $note_imballo = isset($row['Note Imballo']) ? $row['Note Imballo'] : '';
            $note = isset($row['NOTE']) ? $row['NOTE'] : '';
            $ral = isset($row['RAL']) ? $row['RAL'] : '';
            $basamento = isset($row['basamento']) ? $row['basamento'] : '';
            $opt_basamento = isset($row['opt_basamento']) ? $row['opt_basamento'] : '';
            $opt_colonna = isset($row['opt_colonna']) ? $row['opt_colonna'] : '';
            $opt_pressore = isset($row['opt_pressore']) ? $row['opt_pressore'] : '';
            $opt_rampa_dime = isset($row['opt_rampa_dime']) ? $row['opt_rampa_dime'] : '';
            $opt_cart = isset($row['opt_cart']) ? $row['opt_cart'] : '';

            // dd($row);
            // dd(Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data_consegna)));

            if(MachineJob::where('matricola', $matricola)->exists()){
                $machineJob = MachineJob::where('matricola', $matricola)->first();
                Log::info("Import File -> Info: Modifica JOB: ".$matricola);
                $machineJob->customer_id = $customer->id;
                $machineJob->product_id = $product->id;
                $machineJob->cart_id = $cart->id;
                $machineJob->package_id = $package->id;
                $machineJob->data_consegna = $data_consegna;
                $machineJob->tipologia = $tipologia;
                $machineJob->impianto = $impianto;
                $machineJob->colonna = $colonna;
                $machineJob->batteria = $batteria;
                $machineJob->ruota_tastatrice = $ruota_tastatrice;
                $machineJob->opt_cart1 = $opt_cart1;
                $machineJob->opt_cart2 = $opt_cart2;
                $machineJob->dim_imballo = $dim_imballo;
                $machineJob->note_imballo = $note_imballo;
                $machineJob->note = $note;
                $machineJob->ral = $ral;
                $machineJob->basamento = $basamento;
                $machineJob->opt_basamento = $opt_basamento;
                $machineJob->opt_colonna = $opt_colonna;
                $machineJob->opt_pressore = $opt_pressore;
                $machineJob->opt_rampa_dime = $opt_rampa_dime;
                $machineJob->opt_cart = $opt_cart;
                return $machineJob;
            } else {
                Log::info("Import File -> Info: Inserimento JOB: " . $matricola);
                return new MachineJob([
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'cart_id' => $cart->id,
                    'package_id' => $package->id,
                    'data_consegna' => $data_consegna,
                    'matricola' => $matricola,
                    'tipologia' => $tipologia,
                    'impianto' => $impianto,
                    'colonna' => $colonna,
                    'batteria' => $batteria,
                    'ruota_tastatrice' => $ruota_tastatrice,
                    'opt_cart1' => $opt_cart1,
                    'opt_cart2' => $opt_cart2,
                    'dim_imballo' => $dim_imballo,
                    'note_imballo' => $note_imballo,
                    'note' => $note,
                    'ral' => $ral,
                    'basamento' => $basamento,
                    'opt_basamento' => $opt_basamento,
                    'opt_colonna' => $opt_colonna,
                    'opt_pressore' => $opt_pressore,
                    'opt_rampa_dime' => $opt_rampa_dime,
                    'opt_cart' => $opt_cart,
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Import Privacy Agreement CSV error: " . $e->getMessage());
            Log::error($row);
            // dd($row);
        }
    }
}
