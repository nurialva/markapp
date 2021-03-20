	<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'vendor/autoload.php';
require_once 'config.php';


use GraphQL\GraphQL;
use GraphQL\Type\Schema as SchemaType;
use GraphQL\Error\FormattedError;
use GraphQL\Type\Definition\ObjectType;

define('BASE_URL', 'http://localhost:3000');

ini_set('display_errors', 0);

$debug = !empty($_GET['debug']);
if ($debug) {
    $phpErrors = [];
    set_error_handler(function($severity, $message, $file, $line) use (&$phpErrors) {
        $phpErrors[] = new ErrorException($message, 0, $severity, $file, $line);
    });
}


try {

     $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $appContext = [
        'user_id' => null, 
        'pdo' => $pdo 
    ];


    if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $raw = file_get_contents('php://input') ?: '';
        $data = json_decode($raw, true);
    } else {
        $data = $_REQUEST;
    }
    $data += ['query' => null, 'variables' => null];
    if (null === $data['query']) {
        $data['query'] = '{hello}';
    }

    require __DIR__ . '/types/CatalogType.php';
    require __DIR__ . '/types/ProductType.php';
    require __DIR__ . '/Types.php';

    $queryType = new ObjectType([
        'name' => 'Query',
        'fields' => [
            'hello' => [
                'description' => 'Markenyot GraphQL',
                'type' => Types::string(),
                'resolve' => function() {
                    return 'Hi, Selamat datang di GraphQL Markenyot!';
                }
            ],
            
            'products' => [
                'description' => 'Produk',
                'type' => Types::listOf(Types::product()),
                'args' => [
                    'offset' => Types::int(),
                    'limit' => Types::int()
                ],
                'resolve' => function($rootValue, $args, $context) {
                    $pdo = $context['pdo'];

                    $limit = 20;
                    if ( !empty ( $args['limit'] ) ) {
                    	$limit = $args['limit'];
	                    if ( $limit > 50 ) $limit = 50;
                    }
                    $offset = 0;
                    if ( !empty ( $args['offset'] ) ) {
                    	$offset = $args['offset'];
                    }


                    $result = $pdo->query("select * from product limit {$limit} offset {$offset}");
		     	return $result -> fetchAll(PDO::FETCH_OBJ);

                }
            ],

            'catalogs' => [
                'description' => 'Katalog produk',
                'type' => Types::listOf(Types::catalog()),
                'args' => [
                    'offset' => Types::int(),
                    'limit' => Types::int()
                ],
                'resolve' => function($rootValue, $args, $context) {
                    $pdo = $context['pdo'];

                    $limit = 20;
                    if ( !empty ( $args['limit'] ) ) {
                    	$limit = $args['limit'];
	                    if ( $limit > 50 ) $limit = 50;
                    }
                    $offset = 0;
                    if ( !empty ( $args['offset'] ) ) {
                    	$offset = $args['offset'];
                    }

                    $result = $pdo->query("select * from catalog limit {$limit} offset {$offset}");
		     	return $result -> fetchAll(PDO::FETCH_OBJ);

                }
            ],

            'productsByCatalog' => [
                'description' => 'Produk berdasarkan katalog',
                'type' => Types::listOf(Types::product()),
                'args' => [
                    'offset' => Types::int(),
                    'limit' => Types::int(),
                    'catalog_id' => Types::nonNull(Types::int())
                ],
                'resolve' => function($rootValue, $args, $context) {
                    $pdo = $context['pdo'];

                    $limit = 20;
                    if ( !empty ( $args['limit'] ) ) {
                    	$limit = $args['limit'];
	                    if ( $limit > 50 ) $limit = 50;
                    }
                    $offset = 0;
                    if ( !empty ( $args['offset'] ) ) {
                    	$offset = $args['offset'];
                    }

                    $result = $pdo->query("select * from product where catalog_id = {$args['catalog_id']} limit {$limit} offset {$offset}");
		     	return $result -> fetchAll(PDO::FETCH_OBJ);

                }
            ],

            'relatedProducts' => [
                'description' => 'Produk terkait',
                'type' => Types::listOf(Types::product()),
                'args' => [
                    'offset' => Types::int(),
                    'limit' => Types::int(),
                    'prev_product' => Types::nonNull(Types::string()),
                    'catalog_id' => Types::nonNull(Types::int())
                ],
                'resolve' => function($rootValue, $args, $context) {
                    $pdo = $context['pdo'];

                    $limit = 20;
                    if ( !empty ( $args['limit'] ) ) {
                    	$limit = $args['limit'];
	                    if ( $limit > 50 ) $limit = 50;
                    }
                    $offset = 0;
                    if ( !empty ( $args['offset'] ) ) {
                    	$offset = $args['offset'];
                    }

                    $result = $pdo->query("select * from product where catalog_id = {$args['catalog_id']} and id != {$args['prev_product']} limit {$limit} offset {$offset}");
		     	return $result -> fetchAll(PDO::FETCH_OBJ);

                }
            ],


            
        ]
    ]);

    $schema = new SchemaType([
        'query' => $queryType
        
    ]);

    $result = GraphQL::executeQuery(
        $schema,
        $data['query'],
        null,
        $appContext,
        (array) $data['variables']
    );

    if ($debug && !empty($phpErrors)) {
        $result['extensions']['phpErrors'] = array_map(
            ['GraphQL\Error\FormattedError', 'createFromPHPError'],
            $phpErrors
        );
    }
    $httpStatus = 200;
} catch (\Exception $error) {
    $httpStatus = 500;
    if (!empty($_GET['debug'])) {
        $result['extensions']['exception'] = FormattedError::createFromException($error);
    } else {
        $result['errors'] = [FormattedError::create('Unexpected Error')];
    }
}

header('Content-Type: application/json', true, $httpStatus);
echo json_encode($result);
