# Token Authentication Notes API Test

Tests for Token Authentication Notes API project.

## Install

```
$ composer install
```

Set up your API url in a env variable by copying and setting `.env.example` file contents in a `.env` file.

```
API_URL="http://127.0.0.1:8000"
```

### Add a reset endpoint in your Laravel Sanctum API

Create a `DELETE /api/reset` endpoint to reset your database in your Laravel Sanctum project.

This endpoint will be call at the beginning of tests in `tests/bootstrap.php` file.

#### Example code

In `routes/api/php`, add:

```php
Route::delete('reset', [\App\Http\Controllers\ResetController::class, 'reset']);
```

In `app/Http/ResetController.php`, add:

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Note;
use Illuminate\Support\Facades\DB;

class ResetController extends Controller
{
    public function reset()
    {
        User::truncate();
        Note::truncate();
        DB::table('personal_access_tokens')->truncate();

        return response(null, 204);
    }
}
```

> Don't copy and paste blindly, adapt the code to your project.

## Usage

```bash
$ ./vendor/bin/phpunit
```
