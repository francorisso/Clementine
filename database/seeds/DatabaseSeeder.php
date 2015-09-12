<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Provider;
use App\Models\Product;
use App\Models\Price;
use App\Models\OrderItem;
use App\Models\Order;
use App\User;

class DatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Model::unguard();

    $this->call(UsersTableSeeder::class);

    Model::reguard();
  }
}

class OrdersTableSeeder extends Seeder {

  public function run()
  {
    for ($o=0; $o<10000; $o++) {
      $provider = Provider::orderBy(\DB::raw('RAND()'))->first();

      $days = rand(10, 170);
      $dateRandom = date('Y-m-d', strtotime('-'.$days.'days'));

      $order = new Order;
      $order->provider_id    = $provider->id;
      $order->date_required  = $dateRandom;
      $order->date_delivered = $dateRandom;
      $order->status = 'delivered';
      $order->save();

      $articles = rand(3,10);
      for ($art=0; $art<=$articles; $art++) {
        $price = Price::where('provider_id', '=', $provider->id)
          ->orderBy(\DB::raw('RAND()'))
          ->first();

        $orderItem = new OrderItem;
        $orderItem->order_id = $order->id;
        $orderItem->product_id = $price->product_id;
        $orderItem->price = $price->price;
        $orderItem->quantity = rand(2,20);
        $orderItem->save();
      }
    }
  }

}

class UsersTableSeeder extends Seeder {

  public function run()
  {
    $users = [
      [
        'name' => 'Paola',
        'email' => 'paolastariolo@gmail.com',
        'password' => 'EstaEsLaDieteticaHabitosDigital',
        'code' => uniqid(),
      ],
      [
        'name' => 'Mariana',
        'email' => 'mariana@720desarrollos.com',
        'password' => 'EstaEsLaDieteticaHabitosDigital',
        'code' => uniqid(),
      ]
    ];
    foreach ($users as $data) {
      User::create([
        'name'      => $data['name'],
        'email'     => $data['email'],
        'code'      => $data['code'],
        'password'  => bcrypt($data['password']),
      ]);
    }
  }

}