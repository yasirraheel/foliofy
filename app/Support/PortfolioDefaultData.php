<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use RuntimeException;

class PortfolioDefaultData
{
    public static function load(): array
    {
        $path = public_path('data.js');

        if (! File::exists($path)) {
            throw new RuntimeException('Could not find public/data.js to load the portfolio defaults.');
        }

        $source = (string) File::get($path);
        $literal = self::extractObjectLiteral($source);
        $parsed = (new JavaScriptLiteralParser($literal))->parse();

        if (! is_array($parsed)) {
            throw new RuntimeException('The DEFAULT_DATA payload in public/data.js did not parse into an array.');
        }

        return $parsed;
    }

    private static function extractObjectLiteral(string $source): string
    {
        $marker = 'const DEFAULT_DATA =';
        $markerPos = strpos($source, $marker);

        if ($markerPos === false) {
            throw new RuntimeException('Could not locate DEFAULT_DATA in public/data.js.');
        }

        $start = strpos($source, '{', $markerPos);

        if ($start === false) {
            throw new RuntimeException('Could not find the start of the DEFAULT_DATA object literal.');
        }

        $depth = 0;
        $quote = null;
        $escaped = false;
        $length = strlen($source);

        for ($i = $start; $i < $length; $i++) {
            $char = $source[$i];

            if ($quote !== null) {
                if ($escaped) {
                    $escaped = false;
                    continue;
                }

                if ($char === '\\') {
                    $escaped = true;
                    continue;
                }

                if ($char === $quote) {
                    $quote = null;
                }

                continue;
            }

            if ($char === '"' || $char === "'") {
                $quote = $char;
                continue;
            }

            if ($char === '{') {
                $depth++;
                continue;
            }

            if ($char === '}') {
                $depth--;

                if ($depth === 0) {
                    return substr($source, $start, $i - $start + 1);
                }
            }
        }

        throw new RuntimeException('Could not find the end of the DEFAULT_DATA object literal.');
    }
}

class JavaScriptLiteralParser
{
    private int $position = 0;

    public function __construct(
        private readonly string $input,
    ) {
    }

    public function parse(): mixed
    {
        $value = $this->parseValue();
        $this->skipWhitespace();

        if (! $this->isEnd()) {
            throw new RuntimeException('Unexpected content after the JavaScript literal was parsed.');
        }

        return $value;
    }

    private function parseValue(): mixed
    {
        $this->skipWhitespace();
        $char = $this->peek();

        return match (true) {
            $char === '{' => $this->parseObject(),
            $char === '[' => $this->parseArray(),
            $char === '"' || $char === "'" => $this->parseString(),
            $char === '-' || ctype_digit($char) => $this->parseNumber(),
            $this->startsWith('true') => $this->consumeLiteral('true', true),
            $this->startsWith('false') => $this->consumeLiteral('false', false),
            $this->startsWith('null') => $this->consumeLiteral('null', null),
            default => throw new RuntimeException('Unexpected token near position '.$this->position.'.'),
        };
    }

    private function parseObject(): array
    {
        $this->expect('{');
        $result = [];
        $this->skipWhitespace();

        if ($this->peek() === '}') {
            $this->position++;

            return $result;
        }

        while (true) {
            $this->skipWhitespace();
            $key = in_array($this->peek(), ['"', "'"], true)
                ? (string) $this->parseString()
                : $this->parseIdentifier();

            $this->skipWhitespace();
            $this->expect(':');
            $result[$key] = $this->parseValue();

            $this->skipWhitespace();
            $char = $this->peek();

            if ($char === ',') {
                $this->position++;
                $this->skipWhitespace();

                if ($this->peek() === '}') {
                    $this->position++;

                    return $result;
                }

                continue;
            }

            $this->expect('}');

            return $result;
        }
    }

    private function parseArray(): array
    {
        $this->expect('[');
        $result = [];
        $this->skipWhitespace();

        if ($this->peek() === ']') {
            $this->position++;

            return $result;
        }

        while (true) {
            $result[] = $this->parseValue();
            $this->skipWhitespace();
            $char = $this->peek();

            if ($char === ',') {
                $this->position++;
                $this->skipWhitespace();

                if ($this->peek() === ']') {
                    $this->position++;

                    return $result;
                }

                continue;
            }

            $this->expect(']');

            return $result;
        }
    }

    private function parseString(): string
    {
        $quote = $this->peek();
        $this->position++;
        $result = '';

        while (! $this->isEnd()) {
            $char = $this->input[$this->position++];

            if ($char === $quote) {
                return $result;
            }

            if ($char !== '\\') {
                $result .= $char;
                continue;
            }

            if ($this->isEnd()) {
                throw new RuntimeException('Unexpected end of string escape sequence.');
            }

            $escape = $this->input[$this->position++];

            $result .= match ($escape) {
                '"', "'", '\\', '/' => $escape,
                'b' => "\x08",
                'f' => "\f",
                'n' => "\n",
                'r' => "\r",
                't' => "\t",
                'u' => $this->parseUnicodeEscape(),
                default => $escape,
            };
        }

        throw new RuntimeException('Unterminated string literal.');
    }

    private function parseUnicodeEscape(): string
    {
        $hex = substr($this->input, $this->position, 4);

        if (strlen($hex) !== 4 || ! ctype_xdigit($hex)) {
            throw new RuntimeException('Invalid unicode escape sequence.');
        }

        $this->position += 4;

        return json_decode('"\\u'.$hex.'"', true, 512, JSON_THROW_ON_ERROR);
    }

    private function parseNumber(): int|float
    {
        $start = $this->position;

        if ($this->peek() === '-') {
            $this->position++;
        }

        while (! $this->isEnd() && ctype_digit($this->peek())) {
            $this->position++;
        }

        if (! $this->isEnd() && $this->peek() === '.') {
            $this->position++;

            while (! $this->isEnd() && ctype_digit($this->peek())) {
                $this->position++;
            }
        }

        if (! $this->isEnd() && in_array($this->peek(), ['e', 'E'], true)) {
            $this->position++;

            if (! $this->isEnd() && in_array($this->peek(), ['+', '-'], true)) {
                $this->position++;
            }

            while (! $this->isEnd() && ctype_digit($this->peek())) {
                $this->position++;
            }
        }

        $number = substr($this->input, $start, $this->position - $start);

        return str_contains($number, '.') || str_contains($number, 'e') || str_contains($number, 'E')
            ? (float) $number
            : (int) $number;
    }

    private function parseIdentifier(): string
    {
        $start = $this->position;

        while (! $this->isEnd() && preg_match('/[A-Za-z0-9_$]/', $this->peek()) === 1) {
            $this->position++;
        }

        if ($start === $this->position) {
            throw new RuntimeException('Expected an object key near position '.$this->position.'.');
        }

        return substr($this->input, $start, $this->position - $start);
    }

    private function consumeLiteral(string $literal, mixed $value): mixed
    {
        $this->position += strlen($literal);

        return $value;
    }

    private function startsWith(string $literal): bool
    {
        return substr($this->input, $this->position, strlen($literal)) === $literal;
    }

    private function expect(string $expected): void
    {
        $actual = $this->peek();

        if ($actual !== $expected) {
            throw new RuntimeException(sprintf(
                'Expected "%s" near position %d, got "%s".',
                $expected,
                $this->position,
                $actual
            ));
        }

        $this->position++;
    }

    private function skipWhitespace(): void
    {
        while (! $this->isEnd() && preg_match('/\s/u', $this->peek()) === 1) {
            $this->position++;
        }
    }

    private function peek(): string
    {
        return $this->input[$this->position] ?? '';
    }

    private function isEnd(): bool
    {
        return $this->position >= strlen($this->input);
    }
}
