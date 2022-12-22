<?php

declare(strict_types=1);

namespace Activity\Tests;

use Activity\Action;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    protected function createUser(): User
    {
        return User::create([
            'name' => 'Test user',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
    }

    /**
     * When a tracked model is created an action is saved
     * @test
     */
    public function when_a_tracked_model_is_created_an_action_is_saved(): void
    {
        $this->freezeTime();

        $user = $this->createUser();

        $this->be($user);

        $post = $user->posts()->create([
            'title' => 'Test post',
            'body' => 'Test body',
        ]);

        $action = $post->actionsSubjected->first();

        static::assertCount(1, $post->actionsSubjected);
        static::assertCount(1, $user->actionsPerformed);
        static::assertSame($this->expectedDescription($user, $action), $action->description());
        static::assertTrue($post->actionsSubjected->first()->is($user->actionsPerformed->first()));
    }

    /**
     * When a tracked model is updated an action is saved
     * @test
     */
    public function when_a_tracked_model_is_updated_an_action_is_saved(): void
    {
        $this->freezeTime();

        $user = $this->createUser();

        $this->be($user);

        $post = $user->posts()->create([
            'title' => 'Test post',
            'body' => 'Test body',
        ]);

        $post->title = 'Test title';
        $post->save();

        $action = $post->actionsSubjected->last();

        static::assertCount(2, $post->actionsSubjected);
        static::assertCount(2, $user->actionsPerformed);
        static::assertSame($this->expectedDescription($user, $action), $post->actionsSubjected->last()->description());
        static::assertTrue($post->actionsSubjected->last()->is($user->actionsPerformed->last()));
    }

    /**
     * When a tracked model is deleted an action is saved
     * @test
     */
    public function when_a_tracked_model_is_deleted_an_action_is_saved(): void
    {
        $this->freezeTime();

        $user = $this->createUser();

        $this->be($user);

        $post = $user->posts()->create([
            'title' => 'Test post',
            'body' => 'Test body',
        ]);

        $post->delete();

        $action = $post->actionsSubjected->last();

        static::assertCount(2, $user->actionsPerformed);
        static::assertSame($this->expectedDescription($user, $action), $user->actionsPerformed->last()->description());
    }

    private function expectedDescription(User $user, Action $action): string
    {
        $date = now()->toDateTimeString();
        return "$user->name (id $user->id) performed the action '$action->type' on '$action->subjectable_type' id number $action->subjectable_id at $date";
    }
}
