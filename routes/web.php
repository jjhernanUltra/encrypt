<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/procesar', function (Request $request) {
        if ($request->type == "encrypt") {
            // Generacion de llave de encriptado
            // $key = openssl_random_pseudo_bytes(2);
            $key = env('APP_KEY');

            // dd($key);
            // dd(putenv('KEY_ENCRYPT:$key'));

            // Asignacion de variable al mensaje de la peticion
            $textPlano = $request->message;

            // Selección de metodo de encriptado
            $ivlen = openssl_cipher_iv_length($cipher="camellia-256-ctr");

            // Generación de la frase de contraseña en base al metodo de encriptado
            $iv = openssl_random_pseudo_bytes($ivlen);

            // Encriptado del texto
            $ciphertext_raw = openssl_encrypt($textPlano, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
            
            // Apicacion de hash para hacerlo texto legible
            $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
            $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw);

            return Inertia::render('Dashboard', ['textProcess' => $ciphertext, 'checked' => false]);
        } 
        // Desencriptado

        // texto encriptado
        $textoEncriptado = $request->message;

        $c = base64_decode($textoEncriptado);
        $ivlen = openssl_cipher_iv_length($cipher="camellia-256-ctr");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $key = env('APP_KEY');
        try {
            $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        } catch (\Throwable $th) {
            $original_plaintext = 'Cadena invalida';
        }
        return Inertia::render('Dashboard', ['textProcess' => $original_plaintext, 'checked' => true]);
    });
});
