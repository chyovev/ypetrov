<?php

namespace Tests\Feature\Exceptions;

use App\Exceptions\ExceptionWrapper;
use App\Models\Book;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class ExceptionWrapperTest extends TestCase
{

    ///////////////////////////////////////////////////////////////////////////
    public function test_authentication_exception(): void {
        $input  = new AuthenticationException;
        $output = ExceptionWrapper::wrap($input);

        $this->assertSame('Не сте влезли в системата', $output->getMessage());
        $this->assertSame(401, $output->getCode());
        $this->assertSame([
            'generic' => [
                'Не сте влезли в системата',
            ],
        ], $output->getErrors());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_token_mismatch_exception(): void {
        $input  = new TokenMismatchException('CSRF token mismatch.');
        $output = ExceptionWrapper::wrap($input);

        $this->assertSame('CSRF token mismatch.', $output->getMessage());
        $this->assertSame(401, $output->getCode());
        $this->assertSame([
            'generic' => [
                'CSRF token mismatch.',
            ],
        ], $output->getErrors());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_authorization_exception(): void {
        $input  = new AuthorizationException;
        $output = ExceptionWrapper::wrap($input);

        $this->assertSame('Нямате достъп до тази секция', $output->getMessage());
        $this->assertSame(403, $output->getCode());
        $this->assertSame([
            'generic' => [
                'Нямате достъп до тази секция',
            ],
        ], $output->getErrors());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_model_not_found_exception(): void {
        try {
            Book::findOrFail(0);
        }
        catch (ModelNotFoundException $input) {
            $output = ExceptionWrapper::wrap($input);

            $this->assertSame('Няма такъв ресурс', $output->getMessage());
            $this->assertSame(404, $output->getCode());
            $this->assertSame([
                'generic' => [
                    'Няма такъв ресурс',
                ],
            ], $output->getErrors());
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_validation_exception(): void {
        try {
            Validator::make([], ['hello' => ['required']])->validate();
        }
        catch (ValidationException $input) {
            $output = ExceptionWrapper::wrap($input);

            $this->assertSame('Неуспешна проверка на данните', $output->getMessage());
            $this->assertSame(400, $output->getCode());
            $this->assertSame([
                'hello' => [
                    'Полето hello е задължително.',
                ],
            ], $output->getErrors());
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_http_exception(): void {
        $input  = new HttpException(503, 'Test');
        $output = ExceptionWrapper::wrap($input);

        $this->assertSame('Test', $output->getMessage());
        $this->assertSame(503, $output->getCode());
        $this->assertSame([
            'generic' => [
                'Test',
            ],
        ], $output->getErrors());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_other_exception(): void {
        $input  = new Exception;
        $output = ExceptionWrapper::wrap($input);

        $this->assertSame('Възникна непредвидена грешка. Моля, опитайте по-късно.', $output->getMessage());
        $this->assertSame(500, $output->getCode());
        $this->assertSame([
            'generic' => [
                'Възникна непредвидена грешка. Моля, опитайте по-късно.',
            ],
        ], $output->getErrors());
    }

}