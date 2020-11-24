<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    private $category;
    protected function setUp(): void
    {
        parent::setUp();
        $this->category = new Category();
    }

    public function testListCategories()
    {
        $category = $this->category::create([
            'name'  => 'test1'
        ]);
        $categories = $this->category::all();
        $this->assertCount(1, $categories);
        $categoryKey = array_keys($categories->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id', 
            'name', 
            'description',
            'is_active',
            'created_at', 
            'updated_at', 
            'deleted_at'
        ], 
        $categoryKey);
    }

    public function testCreate()
    {
        $category = Category::create([
            'name' => 'test'
        ]);
        $category->refresh();
        $this->assertEquals('test', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);
        $this->assertTrue(Uuid::isValid($category->id));

        $category = Category::create([
            'name' => 'test',
            'description' => 'testando'
        ]);
        $this->assertEquals('testando',$category->description);

        $category = Category::create([
            'name' => 'test',
            'description' => null
        ]);
        $this->assertNull($category->description);


        $category = Category::create([
            'name' => 'test',
            'is_active' => true
        ]);
        $this->assertTrue($category->is_active);

        $category = Category::create([
            'name' => 'test',
            'is_active' => false
        ]);
        $this->assertFalse($category->is_active);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create([
            'description' => 'test_description'
        ])->first();
        $data =
        [
            'name' => 'test_name_updated',
            'description' => 'test_description',
            'is_active' => true
        ];
        $category->update($data);
        
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }

    public function testDelete()
    {
        $category = factory(Category::class)->create();
        $category->delete();
        $this->assertNotNull($category->deleted_at);
    }

}
