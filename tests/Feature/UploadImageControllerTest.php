<?php

namespace Tests\Feature;

use App\Http\Controllers\UploadImageController;
use App\Models\Task;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class UploadImageControllerTest extends TestCase
{
    public function testUpdateFromTask()
    {
        Storage::fake('public'); // Use a fake filesystem for the 'public' disk

        list($user, $task) = $this->createUserAndTask();

        $file = UploadedFile::fake()->image('test_image.jpg');

        $response = $this->post('/upload-image', [
            'task' => $task->id,
            'image' => $file,
        ]);

        $response->assertStatus(200);
        $media = $task->getFirstMedia();
        $this->assertNotNull($media);

        // Store the media item in a class property for use in the dependent test
        return $media;
    }

    public function testUpdateFromCkeditor()
    {
        Storage::fake('public'); // Use a fake filesystem for the 'public' disk

        $file = UploadedFile::fake()->image('test_image.jpg');

        $response = $this->post('/upload-image', ['image' => $file]);

        $response->assertStatus(200);

        // Ensure the uploaded image exists in the storage
        Storage::disk('public')->assertExists($file->getClientOriginalName());

        $response = $this->delete('/upload-image', ['fileUrl' => $file->getPath()]);
        $response->assertStatus(200);
        $this->assertEquals('false', $response->getContent());

        // Clean up: Delete the fake file
        Storage::disk('public')->delete($file->getClientOriginalName());
    }


    /**
     * @depends testUpdateFromTask
     */
    public function testDestroyUploadImage($media)
    {
        $response = $this->delete('/upload-image', ['fileUrl' => $media->getFullUrl()]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('media', ['id' => $media->id]);
    }
}
