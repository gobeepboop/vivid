<?php

namespace Beep\Vivid\Tests;

use Beep\Vivid\Database\Eloquent\Model;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Beep\Vivid\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Events\Dispatcher;
use PHPUnit\Framework\TestCase;
use Beep\Vivid\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Capsule\Manager as DB;
use Ramsey\Uuid\Uuid as RamseyUuid;

class ModelTest extends TestCase
{
    /**
     * Setup the Test.
     *
     * @return void
     */
    public function setUp(): void
    {
        Eloquent::unguard();
        Eloquent::setEventDispatcher(new Dispatcher);

        $db = new DB;
        $db->addConnection([
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);
        $db->bootEloquent();
        $db->setAsGlobal();

        $this->schema()->create('users', function (Blueprint $table): void {
            $table->randomizes();
            $table->timestamps();
        });


        $this->schema()->create('comments', function (Blueprint $table) {
            $table->randomizes();
            $table->binary('user_id', 16)->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Tear down the Test.
     *
     * @return void
     */
    public function tearDown(): void
    {
        $this->schema()->drop('users');
        $this->schema()->drop('comments');
        Eloquent::clearBootedModels();
    }

    /**
     * Tests a model can be created with an optimized uuid.
     *
     * @return void
     */
    public function test_model_is_created_with_optimized_uuid(): void
    {
        $user = new User;

        $user->save();

        $this->assertFalse(RamseyUuid::isValid($user->id));
    }

    /**
     * Tests a model can be found with an optimized uuid.
     *
     * @return void
     */
    public function test_model_can_be_found_with_optimized_uuid(): void
    {
        $user = tap(new User, function (&$user) {
            $user->save();
        });

        $uuid = RamseyUuid::fromBytes($user->id)->toString();

        $found = User::find($uuid);

        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($user->id, $found->id);
    }

    /**
     * Tests a model with a relationship can be retrieved.
     *
     * @return void
     */
    public function test_model_relationship_retrieval(): void
    {
        $user = new User;
        $user->save();

        $comment = new Comment;
        $user->comments()->save($comment);

        $this->assertInstanceOf(Comment::class, $user->comments->where('id', $comment->id)->first());
        $this->assertInstanceOf(User::class, Comment::first()->user);
        $this->assertEquals(User::find($user->id), Comment::where('user_id', $user->id)->first()->user);
    }

    /**
     * Get the Schema Builder.
     *
     * @return Builder
     */
    protected function schema(): Builder
    {
        return tap($this->connection()->getSchemaBuilder(), function ($builder) {
            $builder->blueprintResolver(function ($table, $callback) {
                return new Blueprint($table, $callback);
            });
        });
    }

    /**
     * Get the Database Connection.
     *
     * @return ConnectionInterface
     */
    protected function connection(): ConnectionInterface
    {
        return Eloquent::getConnectionResolver()->connection();
    }
}

class User extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }
}

class Comment extends Model
{
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function setUserIdAttribute($value): void
    {
        $this->setUuidAttribute('user_id', $value);
    }
}

