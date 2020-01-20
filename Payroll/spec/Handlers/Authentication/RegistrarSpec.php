<?php

namespace spec\Payroll\Handlers\Authentication;

use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use PhpSpec\Laravel\LaravelObjectBehavior;
use Prophecy\Argument;

class RegistrarSpec extends LaravelObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Payroll\Handlers\Authentication\Registrar');
    }

    function it_should_return_the_register_view()
    {
        $this->getRegister()->shouldReturnAnInstanceOf(View::class);
    }
}
