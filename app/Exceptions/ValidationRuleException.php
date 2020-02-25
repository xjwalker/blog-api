<?php

namespace App\Exceptions;

use Exception;

class ValidationRuleException extends Exception
{
    protected $attribute;
    protected $rule;
    protected $message;

    public function __construct(string $attribute, string $rule, string $message = null)
    {
        parent::__construct();
        $this->attribute = $attribute;
        $this->rule = $rule;
        $this->message = $message;
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([
            'error' => [
                'type' => 'ValidationException',
                'status' => 422,
                'errors' => [
                    $this->attribute => [
                        'code' => config('errors.' . strtolower($this->rule), 'UNKNOWN'),
                        'message' => $this->getMessageCustom(),
                    ],
                ],
            ],
        ], 422);
    }

    private function getMessageCustom()
    {
        if (empty($this->message)) {
            return trans('validation.' . strtolower($this->rule), ['attribute' => $this->attribute]);
        }

        return $this->message;
    }
}
