<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Detalle;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Tipo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\User::factory(10)->create();

        DB::table('users')->delete();
        DB::table('sucursals')->delete();
        Sucursal::create(array(
            'numero'=>'1',
            'direccion'=>'Av al Valle y Calle 4',
            'imagen'=>'123456.jpg'
        ));
        Sucursal::create(array(
            'numero'=>'2',
            'direccion'=>'Av Reynaldo Vasquez Sempertegui calle E y F',
            'imagen'=>'123456.jpg'
        ));
        Sucursal::create(array(
            'numero'=>'3',
            'direccion'=>'Av España',
            'imagen'=>'123456.jpg'
        ));
		User::create(array(
			'rol' => 2,
			'nombre' => 'Joel',
			'apellido' => 'Gonzales Aguilar',
			'username' => 'master',
			'imagen'=>'20233721535.jpg',
            'sucursal_id'=>1,
			'password' => Hash::make('master123456')
		));
		User::create(array(
			'rol' => 1,
			'nombre' => 'Willma',
			'apellido' => 'Tola Rios',
			'username' => 'willma',
			'imagen'=>'202337215252.jpg',
			'password' => Hash::make('oscar123456')
		));
        Categoria::create(array(
            'grupo'=>'CALAMINA',
            'sucursal_id'=>'1'
        ));
        Categoria::create(array(
            'grupo'=>'CLAVOS',
            'sucursal_id'=>'1'
        ));
        Categoria::create(array(
            'grupo'=>'ALAMBRE',
            'sucursal_id'=>'1'
        ));
        Categoria::create(array(
            'grupo'=>'LIJAS',
            'sucursal_id'=>'1'
        ));
        Categoria::create(array(
            'grupo'=>'MALLAS',
            'sucursal_id'=>'1'
        ));
        Categoria::create(array(
            'grupo'=>'FIERRO',
            'sucursal_id'=>'1'
        ));
        Categoria::create(array(
            'grupo'=>'CALAMINA',
            'sucursal_id'=>'2'
        ));
        Categoria::create(array(
            'grupo'=>'CLAVOS',
            'sucursal_id'=>'2'
        ));
        Categoria::create(array(
            'grupo'=>'ALAMBRE',
            'sucursal_id'=>'2'
        ));
        Categoria::create(array(
            'grupo'=>'LIJAS',
            'sucursal_id'=>'2'
        ));
        Categoria::create(array(
            'grupo'=>'MALLAS',
            'sucursal_id'=>'2'
        ));
        Categoria::create(array(
            'grupo'=>'FIERRO',
            'sucursal_id'=>'2'
        ));
		Producto::create(array(
			'codigo'=>'1000',
			'descripcion'=>'PLASTICO GRUESO 1.80',
			'imagen'=>'plastico.jpg',
			'stock'=>'100',
            'cantidad_minima'=>'20',
            'precio_compra'=>'80',
            'precio_venta'=>'90',
            'categoria_id'=>'1'
		));
		Producto::create(array(
			'codigo'=>'1001',
			'descripcion'=>'PLASTICO GRUESO 2.15',
			'imagen'=>'plastico.jpg',
			'stock'=>'100',
            'cantidad_minima'=>'20',
            'precio_compra'=>'80',
            'precio_venta'=>'90',
            'categoria_id'=>'1'
		));
        Producto::create(array(
			'codigo'=>'1000',
			'descripcion'=>'PLASTICO DELGADO 1.80',
			'imagen'=>'plastico.jpg',
			'stock'=>'100',
            'cantidad_minima'=>'20',
            'precio_compra'=>'80',
            'precio_venta'=>'90',
            'categoria_id'=>'1'
		));
        Producto::create(array(
			'codigo'=>'1000',
			'descripcion'=>'PLASTICO DELGADO 2.15',
			'imagen'=>'plastico.jpg',
			'stock'=>'100',
            'cantidad_minima'=>'20',
            'precio_compra'=>'80',
            'precio_venta'=>'90',
            'categoria_id'=>'1'
		));
    }
}
