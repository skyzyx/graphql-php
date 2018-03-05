<?php
/**
 * Copyright (c) 2015–2018 Alexandr Viniychuk <http://youshido.com>.
 * Copyright (c) 2015–2018 Portey Vasil <https://github.com/portey>.
 * Copyright (c) 2018 Ryan Parman <https://github.com/skyzyx>.
 * Copyright (c) 2018 Ashley Hutson <https://github.com/asheliahut>.
 * Copyright (c) 2015–2018 Contributors.
 *
 * http://opensource.org/licenses/MIT
 */

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: mounter
 * Date: 8/18/16
 * Time: 2:17 PM.
 */

namespace Youshido\Tests\Performance;

use Youshido\GraphQL\Execution\Processor;
use Youshido\GraphQL\Schema\Schema;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\StringType;

class LoadTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad10k()
    {
        $time     = \microtime(true);
        $postType = new ObjectType([
            'name'   => 'Post',
            'fields' => [
                'id'      => new IdType(),
                'title'   => new StringType(),
                'authors' => [
                    'type' => new ListType(new ObjectType([
                        'name'   => 'Author',
                        'fields' => [
                            'name' => new StringType(),
                        ],
                    ])),
                ],
            ],
        ]);

        $data = [];

        for ($i = 1; $i <= 10000; $i++) {
            $authors = [];

            while (\count($authors) < \random_int(1, 4)) {
                $authors[] = [
                    'name' => 'Author ' . \mb_substr(\md5(\time()), 0, 4),
                ];
            }
            $data[] = [
                'id'      => $i,
                'title'   => 'Title of ' . $i,
                'authors' => $authors,
            ];
        }

        $p = new Processor(new Schema([
            'query' => new ObjectType([
                'name'   => 'RootQuery',
                'fields' => [
                    'posts' => [
                        'type'    => new ListType($postType),
                        'resolve' => static function () use ($data) {
                            return $data;
                        },
                    ],
                ],
            ]),
        ]));

        return true;
        $p->processPayload('{ posts { id, title, authors { name } } }');
        $res = $p->getResponseData();
        echo 'Count: ' . \count($res['data']['posts']) . "\n";
        \var_dump($res['data']['posts'][0]);
        \printf("Test Time: %04f\n", \microtime(true) - $time);
    }
}
