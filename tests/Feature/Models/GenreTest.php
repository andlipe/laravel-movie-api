<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use DatabaseMigrations;

    public function testListGenres()
    {
        $genre = Genre::create([
            'name'  => 'test1'
        ]);
        $genres = $genre::all();
        $this->assertCount(1, $genres);
        $genreKey = array_keys($genres->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id', 
            'name', 
            'is_active',
            'created_at', 
            'updated_at', 
            'deleted_at'
        ], 
        $genreKey);
    }

    public function testCreate()
    {
        $genre = Genre::create([
            'name' => 'test'
        ]);

        $genre->refresh();
        $this->assertEquals('test', $genre->name);
        $this->assertTrue($genre->is_active);
        $this->assertTrue(Uuid::isValid($genre->id));

        $genre = Genre::create([
            'name' => 'test',
            'is_active' => true
        ]);
        $this->assertTrue($genre->is_active);
        
        $genre = Genre::create([
            'name' => 'test',
            'is_active' => false
        ]);
        $this->assertFalse($genre->is_active);
    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create()->first();
        $data = [
            'name' => 'testUpdate',
            'is_active' => false
        ];

        $genre->update($data);

        foreach ($data as $key => $value) {
           $this->assertEquals($value, $genre->{$key});
        }
    }

    public function testDelete()
    {
        $genre = factory(Genre::class)->create();
        $genre->delete();
        $this->assertNotNull($genre->deleted_at);
    }
}