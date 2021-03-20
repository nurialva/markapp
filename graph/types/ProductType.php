<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class ProductType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Product',
            'description' => 'Data produk',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::nonNull(Types::string()),
                        'resolve' => function($value) {
                            return (string) $value->id;
                        }
                    ],
                    'name' => [
                        'type' => Types::string()
                    ],
                    'description' => [
                        'type' => Types::string()
                    ],
                    'img_url' => [
                        'type' => Types::string()
                    ],
                    'in_stock' => [
                        'type' => Types::int()
                    ],
                    'shop_link' => [
                        'type' => Types::string()
                    ],
                    'price' => [
                        'type' => Types::string(),
                        'description' => 'Harga dalam rupiah'
                    ],
                    'catalog' => [
                        'type' => Types::catalog(),
                        'description' => 'Katalog'
                    ],
                ];
            },
            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                if (method_exists($this, $info->fieldName)) {
                    return $this->{$info->fieldName}($value, $args, $context, $info);
                } else {
                    return is_numeric($value->{$info->fieldName})? (int) $value->{$info->fieldName} : $value->{$info->fieldName};
                }
            }
        ];
        parent::__construct($config);
    }


    public function catalog($value, $args, $context)
    {
        $pdo = $context['pdo'];
        $category_id = $value->catalog_id;
        $result = $pdo->query("select * from catalog where id = {$category_id}");
        return $result->fetchObject() ?: null;
    }

}
