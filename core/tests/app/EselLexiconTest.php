<?php

use PHPUnit\Framework\TestCase;

class EselLexicomTest extends TestCase
{
    /**
     * EselLexicon.
     *
     * @var lex
     */
    private $lex;


    public function setUp()
    {
        require_once dirname(dirname(dirname(__FILE__))).'/config.inc.php';
        $this->lex = new EselLexicon();

    }

    public function tearDown()
    {
        $this->lex = null;
    }

    public function testCanGetEntry(){

        $data=$this->lex->get("smth",null,"ko","doesnt","exist");
        $this->assertEquals("smth",$data);

        $data2=$this->lex->get("smth_else",null);
        $this->assertEquals("smth_else",$data2);

    }
}
