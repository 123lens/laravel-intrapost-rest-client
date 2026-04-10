<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Exceptions;

class IntrapostApiException extends IntrapostException
{
    /** @var string[] */
    private array $errors;

    /**
     * @param string[] $errors
     */
    public function __construct(array $errors, int $statusCode = 0, ?\Throwable $previous = null)
    {
        $this->errors = $errors;
        $message = implode('; ', $errors);

        parent::__construct($message, $statusCode, $previous);
    }

    /** @return string[] */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
