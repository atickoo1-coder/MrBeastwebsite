<?php
/**
 * File: helpers/validation.php
 * Purpose: Server-side input validation helpers
 */

class Validator
{
    private array $errors = [];

    /**
     * Validate required field
     */
    public function required(string $field, string $label, string $value): self
    {
        if (trim($value) === '') {
            $this->errors[$field] = "$label is required.";
        }
        return $this;
    }

    /**
     * Validate numeric value
     */
    public function numeric(string $field, string $label, $value): self
    {
        if (!is_numeric($value)) {
            $this->errors[$field] = "$label must be a valid number.";
        }
        return $this;
    }

    /**
     * Validate minimum value
     */
    public function min(string $field, string $label, $value, float $min): self
    {
        if (is_numeric($value) && (float)$value < $min) {
            $this->errors[$field] = "$label must be at least $min.";
        }
        return $this;
    }

    /**
     * Validate string length
     */
    public function maxLength(string $field, string $label, string $value, int $max): self
    {
        if (strlen(trim($value)) > $max) {
            $this->errors[$field] = "$label must not exceed $max characters.";
        }
        return $this;
    }

    /**
     * Validate email format
     */
    public function email(string $field, string $label, string $value): self
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "$label must be a valid email address.";
        }
        return $this;
    }

    /**
     * Validate SKU format (alphanumeric + hyphens)
     */
    public function sku(string $field, string $label, string $value): self
    {
        if (!preg_match('/^[A-Za-z0-9\-]+$/', $value)) {
            $this->errors[$field] = "$label may only contain letters, numbers, and hyphens.";
        }
        return $this;
    }

    /**
     * Validate uploaded image file
     */
    public function image(array $file, string $field = 'image'): self
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                // No file uploaded - not necessarily an error (depends on context)
                return $this;
            }
            $this->errors[$field] = 'An error occurred during file upload.';
            return $this;
        }

        // Validate file size
        if ($file['size'] > MAX_FILE_SIZE) {
            $maxMb = MAX_FILE_SIZE / 1024 / 1024;
            $this->errors[$field] = "Image must be less than {$maxMb}MB.";
            return $this;
        }

        // Validate file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
            $this->errors[$field] = 'Only JPG, PNG, WebP, and GIF images are allowed.';
        }

        return $this;
    }

    /**
     * Validate image is required (for create)
     */
    public function imageRequired(array $file, string $field = 'image'): self
    {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            $this->errors[$field] = 'Product image is required.';
            return $this;
        }
        return $this->image($file, $field);
    }

    /**
     * Check if validation passed
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }

    /**
     * Get all errors
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get first error message
     */
    public function firstError(): string
    {
        return reset($this->errors) ?: '';
    }
}
