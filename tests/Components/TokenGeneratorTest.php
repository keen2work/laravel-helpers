<?php


namespace Components;


class TokenGeneratorTest extends \PHPUnit\Framework\TestCase
{

	public function test_tokens_are_generated_with_the_given_length()
	{
		$token = \EMedia\Helpers\TokenGenerator\TokenGenerator::generate(5);
		$this->assertEquals(strlen($token), 5);

		$token = \EMedia\Helpers\TokenGenerator\TokenGenerator::generate(10);
		$this->assertEquals(strlen($token), 10);
	}

}