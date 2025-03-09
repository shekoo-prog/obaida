<?php

namespace App\Core;

class Validator
{
    protected $data = [];
    protected $rules = [];
    protected $errors = [];
    protected $messages = [];

    protected $defaultMessages = [
        'required' => 'The :field field is required.',
        'email' => 'The :field must be a valid email address.',
        'min' => 'The :field must be at least :param characters.',
        'max' => 'The :field must not exceed :param characters.',
        'matches' => 'The :field must match :param field.',
        'unique' => 'The :field has already been taken.',
        'numeric' => 'The :field must be a number.',
        'alpha' => 'The :field must contain only letters.',
        'alpha_numeric' => 'The :field must contain only letters and numbers.',
        'url' => 'The :field must be a valid URL.',
        'date' => 'The :field must be a valid date.',
    ];

    public function make(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        
        foreach ($rules as $field => $fieldRules) {
            $this->validateField($field, $fieldRules);
        }
        
        return empty($this->errors);
    }

    protected function validateField($field, $fieldRules)
    {
        $rules = is_string($fieldRules) ? explode('|', $fieldRules) : $fieldRules;
        
        foreach ($rules as $rule) {
            $params = [];
            
            if (strpos($rule, ':') !== false) {
                [$rule, $param] = explode(':', $rule);
                $params = explode(',', $param);
            }
            
            $method = 'validate' . ucfirst($rule);
            
            if (method_exists($this, $method)) {
                if (!$this->$method($field, $params)) {
                    $this->addError($field, $rule, $params);
                }
            }
        }
    }

    protected function validateRequired($field)
    {
        return isset($this->data[$field]) && $this->data[$field] !== '';
    }

    protected function validateEmail($field)
    {
        return filter_var($this->data[$field] ?? '', FILTER_VALIDATE_EMAIL) !== false;
    }

    protected function validateMin($field, $params)
    {
        return strlen($this->data[$field] ?? '') >= $params[0];
    }

    protected function validateMax($field, $params)
    {
        return strlen($this->data[$field] ?? '') <= $params[0];
    }

    protected function validateMatches($field, $params)
    {
        return ($this->data[$field] ?? '') === ($this->data[$params[0]] ?? '');
    }

    protected function validateUnique($field, $params)
    {
        [$table, $column] = $params;
        $value = $this->data[$field] ?? '';
        
        $db = Database::getInstance();
        $result = $db->query(
            "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?",
            [$value]
        )->fetch();
        
        return $result->count === 0;
    }

    protected function validateNumeric($field)
    {
        return is_numeric($this->data[$field] ?? '');
    }

    protected function validateAlpha($field)
    {
        return ctype_alpha(str_replace(' ', '', $this->data[$field] ?? ''));
    }

    protected function validateAlphaNumeric($field)
    {
        return ctype_alnum(str_replace(' ', '', $this->data[$field] ?? ''));
    }

    protected function validateUrl($field)
    {
        return filter_var($this->data[$field] ?? '', FILTER_VALIDATE_URL) !== false;
    }

    protected function validateDate($field)
    {
        return strtotime($this->data[$field] ?? '') !== false;
    }

    protected function addError($field, $rule, $params = [])
    {
        $message = $this->messages[$field][$rule] ?? $this->defaultMessages[$rule] ?? 'Invalid :field';
        $message = str_replace(':field', $field, $message);
        
        if (!empty($params)) {
            $message = str_replace(':param', $params[0], $message);
        }
        
        $this->errors[$field][] = $message;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setMessages(array $messages)
    {
        $this->messages = $messages;
        return $this;
    }
}