<?php
class hasCripting
{
    static $NOTDECODE = 0;
    static $DECODE = 1;
    static $DECODE_NORAMDOM = 2;
    private $clave;

    public function __construct(string $password)
    {
        $this->clave = $password;
    }

    public function encript(string $termino, int $option = 0): string
    {
        $palabraIncriptada = '';
        switch ($option) {
            case self::$NOTDECODE:
                $cipher = "AES-256-CBC";

                $palabraIncriptada = password_hash($termino, PASSWORD_DEFAULT);
                $palabraIncriptada = openssl_encrypt($termino, $cipher, $this->clave, OPENSSL_RAW_DATA, 'uP2c7xJzV5g9qW1R');
                $hmac = hash_hmac('sha256', $palabraIncriptada, $this->clave, true);
                $palabraIncriptada = base64_encode($hmac . $palabraIncriptada);
                break;
            case self::$DECODE:
                $cipher = "AES-256-CBC";

                //Genera un vetor de inicializacion (IV) unico y aleatorio
                $ivlen = openssl_cipher_iv_length($cipher);
                $iv = openssl_random_pseudo_bytes($ivlen);

                //Cifra el mensaje
                $ciphertext_raw = openssl_encrypt($termino, $cipher, $this->clave, OPENSSL_RAW_DATA, $iv);

                //Genera un HMAC para la integridad de los datos
                $hmac = hash_hmac('sha256', $ciphertext_raw, $this->clave, true);

                //Combinar IV, HMAC y texto cifrado, y luegocodificar en base64
                $palabraIncriptada = base64_encode($iv . $hmac . $ciphertext_raw);
                break;
            case self::$DECODE_NORAMDOM:
                $cipher = "AES-256-CBC";

                $iv = 'uP2c7xJzV5g9qW1R';

                //Cifra el mensaje
                $ciphertext_raw = openssl_encrypt($termino, $cipher, $this->clave, OPENSSL_RAW_DATA, $iv);

                //Genera un HMAC para la integridad de los datos
                $hmac = hash_hmac('sha256', $ciphertext_raw, $this->clave, true);

                //Combinar IV, HMAC y texto cifrado, y luegocodificar en base64
                $palabraIncriptada = base64_encode($iv . $hmac . $ciphertext_raw);
                break;
            default:
                throw new Exception('Opcion de Operacion de incriptacion no valida.');
                break;
        }
        return $palabraIncriptada;
    }
    public function decript(string $termino): string
    {
        $palabraDesincriptada = '';
        $cipher = "AES-256-CBC";

        //Decodifica en base64
        $datos_decodificados = base64_decode($termino);

        //Extrae el IV (16 bytes), HMAC (32 bytes) y el texto cifrado
        $iv_longitud = openssl_cipher_iv_length($cipher);
        $iv = substr($datos_decodificados, 0, $iv_longitud);
        $hmac = substr($datos_decodificados, $iv_longitud, 32);
        $texto_cifrado = substr($datos_decodificados, $iv_longitud + 32);

        //Verifica la integridad del mensaje con el HMAC
        if (hash_hmac('sha256', $texto_cifrado, $this->clave, true) == $hmac) {
            //Decifrar si la verificacion HMAC es exitosa
            $palabraDesincriptada = openssl_decrypt($texto_cifrado, $cipher, $this->clave, OPENSSL_RAW_DATA, $iv);
        } else {
            throw new Exception('Error: No se pudo desincriptar el mensaje.');
        }
        return $palabraDesincriptada;
    }
}
