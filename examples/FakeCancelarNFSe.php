<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\NFSeTrans\Tools;
use NFePHP\NFSeTrans\Common\Soap\SoapFake;
use NFePHP\NFSeTrans\Common\FakePretty;

try {

    $config = [
        'cnpj'         => '99999999000191',
        'im'           => '1733160024',
        'cmun'         => '3506102', //ira determinar as urls e outros dados
        'razao'        => 'Empresa Test Ltda',
        'usuario'      => '1929292', //codigo do usuário usado no login
        'contribuinte' => 'ksjksjkjs', //codigo do contribunte usado no login
        'tipotrib'     => 1,
        'dtadesn'      => '01/01/2017',
        'alqisssn'     => 1.00,
        'tpamb'        => 2, //1-producao, 2-homologacao
        'webservice'   => 1
    ];

    $configJson = json_encode($config);

    $soap = new SoapFake();

    $tools = new Tools($configJson);
    $tools->loadSoapClass($soap);

    $cancelarGuia = 'S';
    $valor = 2000.00;
    $motivo = 'Teste de cancelamento';
    $numeronfse = 12;
    $serienfse = 1;
    $serierps = null;
    $numerorps = null;

    $response = $tools->cancelarNfse(
        $cancelarGuia, $valor, $motivo, $numeronfse, $serienfse, $serierps, $numerorps
    );

    echo FakePretty::prettyPrint($response, '');
} catch (\Exception $e) {
    echo $e->getMessage();
}

