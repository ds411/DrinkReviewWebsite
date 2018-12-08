<?php
include(merrimack/debug.php);
ini_set('display_errors', 'on');
session_start();

require "vendor/autoload.php";

require "database/generated-conf/config.php";

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\Converter\StandardConverter;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Algorithm\HS256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;

$algorithm_manager = AlgorithmManager::create([
	new HS256(),
]);

$jwk = JWK::create([
	'kty' => 'oct',
	'k' => 'dzI6nbW4OcNF-AtfxGAmuyz7IpHRudBI0WgGjZWgaRJt6prBn3DARXgUR8NVwKhfL43QBIU2Un3AvCGCHRgY4TbEqhOi8-i98xxmCggNjde4oaW6wkJ2NgM3Ss9SOX9zS3lcVzdCMdum-RwVJ301kbin4UtGztuzJBeg5oVN00MGxjC2xWwyI0tgXVs-zJs5WlafCuGfX1HrVkIf5bvpE0MQCSjdJpSeVao6-RSTYDajZf7T88a2eVjeW31mMAg-jzAWfUrii61T_bYPJFOXW8kkRWoa1InLRdG6bKB9wQs9-VdXZP60Q4Yuj_WZ-lO7qV9AEFrUkkjpaDgZT86w2g',
]);

$json_converter = new StandardConverter();

$serializer = new CompactSerializer($json_converter);

$jws_builder = new JWSBuilder($json_converter, $algorithm_manager);

$payload = $json_converter->encode([
	'iat' => time(),
	'nbf' => time(),
	'exp' => time() + 3600,
	'iss' => 'My service',
	'aud' => 'Your application',
]);

$jws = $jws_builder->create()
	->withPayload($payload)
	->addSignature($jwk, ['alg' => 'HS256'])
	->build();

$token = $serializer->serialize($jws, 0);

$q = new CompanyQuery();
$company = $q->findPk(1);
echo $company->getName();


include "template.php";
?>
