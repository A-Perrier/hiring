<?php

/**
 * Here is my approach of FizzBuzz, according to scalability and maintainability principles.
 * Having a set of rules which can be changed by a setter allow us to add new rules based on
 * other divisors / output without having to handle new cases in the algorithm.
 * However, it only handles integers. We could go further, handling other data types, but
 * I didn't want to over-engineer the exercise.
 */
class FizzBuzz
{
    public function __construct(
        private array $rules = [
            3 => 'Fizz',
            5 => 'Buzz'
        ]
    ) {
    }

    /**
     * Take care to don't have duplicates keys into your $rules array,
     * otherwise only the last one will last.
     */
    public function addRules(array $rules): self
    {
        foreach ($rules as $divisor => $output) {
            if (!is_int($divisor)) {
                throw new LogicException("Please provide only integer keys.");
            }

            if (array_key_exists($divisor, $this->rules)) {
                throw new LogicException(sprintf('A rule using the divisor %s already exists.', $divisor));
            }

            $this->rules[$divisor] = $output;
        }

        return $this;
    }

    public function generate(int $limit): void
    {
        for ($i = 1; $i <= $limit; $i++) {
            $result = $this->getDivisibleOperands($i);
            if (empty($result)) {
                // Let's state that $i will not match any rules, so we'll return it as is.
                echo "$i\n";
                continue;
            }

            $output = '';
            foreach ($result as $operand) {
                // Concatenate each output for the current operand to automatically
                // output "FizzBuzz" or any combination in the future
                $output .= $this->rules[$operand];
            }
            echo "$output\n";
        }
    }

    private function getDivisibleOperands(int $number): array {
        $result = [];

        // Note that we could generate the output at this moment to avoid
        // doing another foreach back to generate method.
        // As we only handle some numbers and a basic logic in the context
        // of this exercise, I considered fine to lose a bit perfs
        // to gain readability
        foreach ($this->rules as $divisor => $output) {
            if ($number % $divisor === 0) {
                $result[] = $divisor;
            }
        }

        return $result;
    }
}

$fizzbuzz = new FizzBuzz();

// Uncomment the following to see new magic :)
//$fizzbuzz->addRules([
//   7 => 'Foo',
//]);
$fizzbuzz->generate(100);