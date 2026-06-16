<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class BelongsToAuthenticatedUser implements Rule
{
    protected $model;
    protected $userIdColumn;

    /**
     * Create a new rule instance.
     *
     * @param  string  $model
     * @param  string  $userIdColumn
     * @return void
     */
    public function __construct($model, $userIdColumn = 'user_id')
    {
        $this->model = $model;
        $this->userIdColumn = $userIdColumn;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $modelInstance = new $this->model;

        return $modelInstance::where('id', $value)
            ->where($this->userIdColumn, Auth::id())
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'صفحه مورد نظر شما یافت نشد.';
    }
}
